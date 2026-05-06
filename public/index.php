<?php
require_once __DIR__ . '/../config/config.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'about':
        (new HomeController())->about();
        break;
    case 'courses':
        (new HomeController())->courses();
        break;
    case 'contact':
        (new HomeController())->contact();
        break;
    case 'apply':
        (new HomeController())->apply();
        break;
    case 'student_dashboard':
        (new StudentController())->dashboard();
        break;
    case 'student_edit':
        (new StudentController())->edit();
        break;
    case 'student_logout':
        (new StudentController())->logout();
        break;
    case 'student_verify':
        (new StudentController())->verifyPayment();
        break;
    case 'admin_login':
        (new AdminController())->login();
        break;
    case 'admin_logout':
        (new AdminController())->logout();
        break;
    case 'admin_dashboard':
        (new AdminController())->dashboard();
        break;
    case 'admin_students':
        (new AdminController())->students();
        break;
    case 'admin_student_edit':
        (new AdminController())->studentEdit();
        break;
    case 'admin_student_delete':
        (new AdminController())->studentDelete();
        break;
    case 'admin_courses':
        (new AdminController())->courses();
        break;
    case 'admin_course_edit':
        (new AdminController())->courseEdit();
        break;
    case 'admin_course_delete':
        (new AdminController())->courseDelete();
        break;
    case 'admin_registrations':
        (new AdminController())->registrations();
        break;
    case 'admin_settings':
        (new AdminController())->settings();
        break;
    case 'admin_account':
        (new AdminController())->account();
        break;
    default:
        http_response_code(404);
        echo '<h1>404 Not Found</h1><p>The requested page was not found.</p>';
}
