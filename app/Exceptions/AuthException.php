<?php

namespace App\Exceptions;

/**
 * Authentication Exception
 * 
 * Kimlik doğrulama ve yetkilendirme hataları için
 */
class AuthException extends AppException
{
    protected $statusCode = 401;
    protected $errorCode = 'AUTH_ERROR';
    
    /**
     * Create unauthorized exception
     * 
     * @return static
     */
    public static function unauthorized()
    {
        return new static('Yetkisiz erişim. Lütfen giriş yapın.');
    }
    
    /**
     * Create forbidden exception
     * 
     * @return static
     */
    public static function forbidden()
    {
        $exception = new static('Bu işlem için yetkiniz yok.');
        $exception->statusCode = 403;
        $exception->errorCode = 'FORBIDDEN';
        return $exception;
    }
    
    /**
     * Create invalid credentials exception
     * 
     * @return static
     */
    public static function invalidCredentials()
    {
        return new static('Kullanıcı adı veya şifre hatalı.');
    }
    
    /**
     * Create pending approval exception
     * 
     * @return static
     */
    public static function pendingApproval()
    {
        $exception = new static('Hesabınız onay bekliyor. Lütfen admin ile iletişime geçin.');
        $exception->errorCode = 'PENDING_APPROVAL';
        return $exception;
    }
    
    /**
     * Create account rejected exception
     * 
     * @param string $reason
     * @return static
     */
    public static function accountRejected($reason = '')
    {
        $message = 'Hesap başvurunuz reddedildi.';
        if ($reason) {
            $message .= ' Sebep: ' . $reason;
        }
        $exception = new static($message);
        $exception->errorCode = 'ACCOUNT_REJECTED';
        return $exception;
    }
}
