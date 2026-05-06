<?php

abstract class BaseController
{
    protected $settings = [];

    public function __construct()
    {
        $this->settings = Setting::getAll();
    }

    protected function render($view, $data = [], $layout = 'main')
    {
        extract($data);
        $settings = $this->settings;
        $csrfToken = $this->generateCsrfToken();

        if ($layout === 'admin') {
            require APP_PATH . '/views/layouts/admin_header.php';
            require APP_PATH . '/views/' . $view . '.php';
            require APP_PATH . '/views/layouts/admin_footer.php';
            return;
        }

        require APP_PATH . '/views/layouts/header.php';
        require APP_PATH . '/views/' . $view . '.php';
        require APP_PATH . '/views/layouts/footer.php';
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    protected function sanitize($value)
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    protected function getPost($key, $default = '')
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken($token)
    {
        return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    protected function adminOnly()
    {
        if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_user'])) {
            $this->redirect('index.php?page=admin_login');
        }
    }

    protected function studentOnly()
    {
        if (empty($_SESSION['student_logged_in']) || empty($_SESSION['student_id'])) {
            $this->redirect('index.php?page=apply');
        }
    }

    protected function flash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash($key)
    {
        if (!empty($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
