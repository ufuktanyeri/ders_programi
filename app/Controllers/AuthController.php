<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../config/google-oauth.php';

class AuthController extends Controller {

    public function loginForm() {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if ($this->getCurrentUser()) {
            $this->redirect('/dashboard');
        }

        $googleConfigured = false;
        $googleAuthUrl = '';

        // Google OAuth client oluştur (Optional - hata durumunda skip)
        try {
            if (function_exists('isGoogleConfigured') && isGoogleConfigured()) {
                $client = getGoogleClient();
                $googleAuthUrl = $client->createAuthUrl();
                $googleConfigured = true;
            }
        } catch (Exception $e) {
            error_log('Google OAuth Error: ' . $e->getMessage());
            $googleConfigured = false;
        }

        $this->view('auth/login', [
            'title' => 'Giriş Yap - Ders Programı Sistemi',
            'googleAuthUrl' => $googleAuthUrl,
            'googleConfigured' => $googleConfigured,
            'error' => $_GET['error'] ?? null
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
        }

        $username = clean($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic authentication
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_type'] = 'local';

            // Admin kullanıcısını bul veya oluştur
            $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE email = 'admin@localhost'");
            $stmt->execute();
            $adminUser = $stmt->fetch();

            if (!$adminUser) {
                $stmt = $this->db->prepare("INSERT INTO admin_users (email, name, role, status, approval_status) VALUES ('admin@localhost', 'System Admin', 'super_admin', 'active', 'approved')");
                $stmt->execute();
                $_SESSION['admin_user_id'] = $this->db->lastInsertId();
            } else {
                $_SESSION['admin_user_id'] = $adminUser['id'];
            }

            $this->redirect('/dashboard');
        } else {
            $this->redirect('/auth/login?error=invalid_credentials');
        }
    }

    public function googleAuth() {
        if (!isGoogleConfigured()) {
            $this->redirect('/auth/login?error=google_not_configured');
        }

        try {
            $client = getGoogleClient();
            $authUrl = $client->createAuthUrl();
            header('Location: ' . $authUrl);
            exit;
        } catch (Exception $e) {
            error_log('Google OAuth Error: ' . $e->getMessage());
            $this->redirect('/auth/login?error=google_auth_failed');
        }
    }

    public function googleCallback() {
        if (!isGoogleConfigured()) {
            $this->redirect('/auth/login?error=google_not_configured');
        }

        if (!isset($_GET['code'])) {
            $this->redirect('/auth/login?error=google_auth_failed');
        }

        try {
            $client = getGoogleClient();
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                throw new Exception('Token error: ' . $token['error']);
            }

            $client->setAccessToken($token);

            // Get user info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            $google_id = $google_account_info->id;
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            $picture = $google_account_info->picture;

            // Check if user exists
            $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE google_id = ? OR email = ?");
            $stmt->execute([$google_id, $email]);
            $user = $stmt->fetch();

            if (!$user) {
                // Create new user with approval system
                $default_role = (stripos($email, 'ufuk') !== false && stripos($email, 'tanyeri') !== false) ? 'super_admin' : 'guest';
                $approval_status = ($default_role === 'super_admin') ? 'approved' : 'pending';

                $stmt = $this->db->prepare("INSERT INTO admin_users (google_id, email, name, picture, role, approval_status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$google_id, $email, $name, $picture, $default_role, $approval_status]);
                $user_id = $this->db->lastInsertId();

                // Get user data for session
                $stmt = $this->db->prepare("SELECT * FROM admin_users WHERE id = ?");
                $stmt->execute([$user_id]);
                $user = $stmt->fetch();
            } else {
                // Update existing user
                $user_id = $user['id'];
                $stmt = $this->db->prepare("UPDATE admin_users SET name = ?, picture = ?, last_login = NOW() WHERE id = ?");
                $stmt->execute([$name, $picture, $user_id]);
                $user['name'] = $name;
                $user['picture'] = $picture;
            }

            // Check if user is approved
            if ($user['approval_status'] !== 'approved') {
                $this->redirect('/auth/login?error=pending_approval&email=' . urlencode($email));
            }

            // Set session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user_id'] = $user_id;
            $_SESSION['admin_username'] = $name;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_picture'] = $picture;
            $_SESSION['login_type'] = 'google';

            $this->redirect('/dashboard');

        } catch (Exception $e) {
            error_log('Google OAuth Callback Error: ' . $e->getMessage());
            $this->redirect('/auth/login?error=google_auth_failed&msg=' . urlencode($e->getMessage()));
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/auth/login?msg=logged_out');
    }
}
?>