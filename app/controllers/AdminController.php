<?php

class AdminController extends BaseController
{
    public function login()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid login request.';
            }

            $username = $this->getPost('username');
            $password = $this->getPost('password');

            if (empty($username) || empty($password)) {
                $errors[] = 'Username and password are required.';
            }

            if (empty($errors)) {
                $userModel = new User();
                $user = $userModel->authenticate($username, $password);
                if ($user) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_user'] = $user;
                    $this->redirect('index.php?page=admin_dashboard');
                }
                $errors[] = 'Invalid username or password.';
            }
        }

        $settings = $this->settings;
        $this->render('admin/login', compact('errors', 'settings'), 'admin');
    }

    public function logout()
    {
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_user']);
        $this->redirect('index.php?page=admin_login');
    }

    public function dashboard()
    {
        $this->adminOnly();
        $studentModel = new Student();
        $courseModel = new Course();
        $registrationModel = new Registration();

        $students = $studentModel->countAll();
        $courses = count($courseModel->getAll());
        $registrations = count($registrationModel->listAll());
        $latestStudents = $studentModel->listAll();
        $courseList = $courseModel->getAll();
        $settings = $this->settings;

        $courseCounts = [];
        foreach ($courseList as $course) {
            $courseCounts[] = [
                'title' => $course['title'],
                'count' => $registrationModel->countByCourse($course['id']),
            ];
        }

        $this->render('admin/dashboard', compact('students', 'courses', 'registrations', 'latestStudents', 'courseCounts', 'settings'), 'admin');
    }

    public function students()
    {
        $this->adminOnly();
        $studentModel = new Student();
        $registrationModel = new Registration();
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $students = $studentModel->listAll($status);
        $settings = $this->settings;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $this->verifyCsrfToken($this->getPost('csrf_token'))) {
            if ($_POST['action'] === 'update_status' && !empty($_POST['registration_id']) && !empty($_POST['status'])) {
                $registrationModel->updateStatus((int)$_POST['registration_id'], $_POST['status']);
            }
        }

        $this->render('admin/students', compact('students', 'registrationModel', 'settings', 'status'), 'admin');
    }

    public function studentEdit()
    {
        $this->adminOnly();
        $studentModel = new Student();
        $registrationModel = new Registration();
        $student = null;
        $errors = [];
        $success = null;

        if (!empty($_GET['id'])) {
            $student = $studentModel->findById((int)$_GET['id']);
        }

        if (!$student) {
            $this->redirect('index.php?page=admin_students');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid request. Please try again.';
            }

            $data = [
                'full_name' => $this->getPost('full_name'),
                'email' => $this->getPost('email'),
                'phone' => $this->getPost('phone'),
                'address' => $this->getPost('address'),
                'gender' => $this->getPost('gender'),
                'status' => $this->getPost('status'),
                'passport_photo' => $student['passport_photo'],
            ];

            if (!empty($_FILES['passport_photo']['name'])) {
                $photo = $_FILES['passport_photo'];
                if ($photo['size'] <= MAX_UPLOAD_SIZE && in_array($photo['type'], ALLOWED_IMAGE_TYPES, true)) {
                    $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                    $fileName = 'passport_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                    $uploadDir = UPLOAD_PATH . '/passports';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    move_uploaded_file($photo['tmp_name'], $uploadDir . '/' . $fileName);
                    $data['passport_photo'] = 'passports/' . $fileName;
                } else {
                    $errors[] = 'Passport photo must be JPG or PNG and under 2MB.';
                }
            }

            if (empty($errors)) {
                $studentModel->update($student['id'], $data);
                $success = 'Student profile updated successfully.';
                $student = $studentModel->findById($student['id']);
            }
        }

        $settings = $this->settings;
        $this->render('admin/student_edit', compact('student', 'errors', 'success', 'settings'), 'admin');
    }

    public function studentDelete()
    {
        $this->adminOnly();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->verifyCsrfToken($this->getPost('csrf_token')) && !empty($_POST['id'])) {
            $studentModel = new Student();
            $studentModel->delete((int)$_POST['id']);
        }
        $this->redirect('index.php?page=admin_students');
    }

    public function courses()
    {
        $this->adminOnly();
        $courseModel = new Course();
        $courses = $courseModel->getAll();
        $settings = $this->settings;
        $this->render('admin/courses', compact('courses', 'settings'), 'admin');
    }

    public function courseEdit()
    {
        $this->adminOnly();
        $courseModel = new Course();
        $course = null;
        $errors = [];
        $success = null;

        if (!empty($_GET['id'])) {
            $course = $courseModel->findById((int)$_GET['id']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid request.';
            }

            $data = [
                'title' => $this->getPost('title'),
                'slug' => $this->getPost('slug') ?: strtolower(str_replace(' ', '-', $this->getPost('title'))),
                'description' => $this->getPost('description'),
                'price' => $this->getPost('price'),
                'duration' => $this->getPost('duration'),
                'is_active' => $this->getPost('is_active') === '1' ? 1 : 0,
            ];

            if (empty($data['title']) || empty($data['description']) || empty($data['price'])) {
                $errors[] = 'Title, description and price are required.';
            }

            if (empty($errors)) {
                if ($course) {
                    $courseModel->update($course['id'], $data);
                    $success = 'Course updated successfully.';
                    $course = $courseModel->findById($course['id']);
                } else {
                    $courseModel->create($data);
                    $success = 'Course created successfully.';
                    $course = null;
                }
            }
        }

        $settings = $this->settings;
        $this->render('admin/course_edit', compact('course', 'errors', 'success', 'settings'), 'admin');
    }

    public function courseDelete()
    {
        $this->adminOnly();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->verifyCsrfToken($this->getPost('csrf_token')) && !empty($_POST['id'])) {
            $courseModel = new Course();
            $courseModel->delete((int)$_POST['id']);
        }
        $this->redirect('index.php?page=admin_courses');
    }

    public function registrations()
    {
        $this->adminOnly();
        $registrationModel = new Registration();
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $registrations = $registrationModel->listAll($status);
        $settings = $this->settings;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->verifyCsrfToken($this->getPost('csrf_token'))) {
            if (!empty($_POST['id']) && !empty($_POST['status'])) {
                $registrationModel->updateStatus((int)$_POST['id'], $_POST['status']);
            }
        }

        $this->render('admin/registrations', compact('registrations', 'settings', 'status'), 'admin');
    }

    public function account()
    {
        $this->adminOnly();
        $user = $_SESSION['admin_user'];
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid request.';
            }

            $password = $this->getPost('password');
            $confirm = $this->getPost('confirm_password');

            if (empty($password) || empty($confirm)) {
                $errors[] = 'Both password fields are required.';
            } elseif ($password !== $confirm) {
                $errors[] = 'Passwords do not match.';
            } elseif (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            }

            if (empty($errors)) {
                $userModel = new User();
                $userModel->updatePassword($user['id'], $password);
                $success = 'Admin password updated successfully.';
            }
        }

        $settings = $this->settings;
        $this->render('admin/account', compact('settings', 'errors', 'success', 'user'), 'admin');
    }

    public function settings()
    {
        $this->adminOnly();
        $errors = [];
        $success = null;
        $siteTitle = getSettingValue('site_title');
        $siteTagline = getSettingValue('site_tagline');
        $contactEmail = getSettingValue('contact_email');
        $paystackPublic = getSettingValue('paystack_public_key');
        $paystackSecret = getSettingValue('paystack_secret_key');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid request.';
            }

            $siteTitle = $this->getPost('site_title');
            $siteTagline = $this->getPost('site_tagline');
            $contactEmail = $this->getPost('contact_email');
            $paystackPublic = $this->getPost('paystack_public_key');
            $paystackSecret = $this->getPost('paystack_secret_key');

            if (empty($siteTitle)) {
                $errors[] = 'Site title is required.';
            }
            if (empty($contactEmail) || !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid contact email is required.';
            }

            if (empty($errors)) {
                Setting::saveValues([
                    'site_title' => $siteTitle,
                    'site_tagline' => $siteTagline,
                    'contact_email' => $contactEmail,
                    'paystack_public_key' => $paystackPublic,
                    'paystack_secret_key' => $paystackSecret,
                ]);
                $success = 'Settings saved successfully.';
            }
        }

        $settings = $this->settings;
        $this->render('admin/settings', compact('settings', 'errors', 'success', 'siteTitle', 'siteTagline', 'contactEmail', 'paystackPublic', 'paystackSecret'), 'admin');
    }
}
