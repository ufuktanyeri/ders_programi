<?php
/**
 * Google OAuth Configuration
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Google OAuth JSON dosyası yolu
define('GOOGLE_CLIENT_CONFIG', __DIR__ . '/../client_secret_65073108955-vrglob496nnhp8rsq8s7ia3buhsuqdud.apps.googleusercontent.com.json');

function getGoogleClient() {
    if (!file_exists(GOOGLE_CLIENT_CONFIG)) {
        throw new Exception('Google Client configuration file not found');
    }

    $client = new Google_Client();
    $client->setAuthConfig(GOOGLE_CLIENT_CONFIG);
    $client->addScope('email');
    $client->addScope('profile');

    return $client;
}

function isGoogleConfigured() {
    return file_exists(GOOGLE_CLIENT_CONFIG);
}
?>