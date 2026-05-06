<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ASSETS_URL', '/assets');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('UPLOAD_URL', '/uploads');

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'ibtsub_hub');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_DEFAULT_TITLE', 'Ibtsub Hub');

define('SITE_DEFAULT_TAGLINE', 'Physical Technology Training Center');

define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024);

define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);

spl_autoload_register(function ($class) {
    $candidates = [
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
    ];

    foreach ($candidates as $candidate) {
        if (file_exists($candidate)) {
            require_once $candidate;
            return;
        }
    }
});

require_once APP_PATH . '/models/Database.php';
require_once APP_PATH . '/models/Model.php';

function getSettingValue($key, $default = '')
{
    $setting = Setting::findByKey($key);
    return $setting ? $setting['setting_value'] : $default;
}

function siteTitle()
{
    return getSettingValue('site_title', SITE_DEFAULT_TITLE);
}

function siteTagline()
{
    return getSettingValue('site_tagline', SITE_DEFAULT_TAGLINE);
}

function currentUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
}
