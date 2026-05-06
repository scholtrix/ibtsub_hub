<?php

class HomeController extends BaseController
{
    public function index()
    {
        $courseModel = new Course();
        $courses = $courseModel->getAll(true);
        $settings = $this->settings;
        $this->render('public/home', compact('courses', 'settings'));
    }

    public function about()
    {
        $settings = $this->settings;
        $this->render('public/about', compact('settings'));
    }

    public function courses()
    {
        $courseModel = new Course();
        $courses = $courseModel->getAll(true);
        $settings = $this->settings;
        $this->render('public/courses', compact('courses', 'settings'));
    }

    public function contact()
    {
        $settings = $this->settings;
        $this->render('public/contact', compact('settings'));
    }

    public function apply()
    {
        $courseModel = new Course();
        $courses = $courseModel->getAll(true);
        $settings = $this->settings;
        $errors = [];
        $old = [];
        if (!empty($_GET['course_id'])) {
            $old['course_id'] = (int)$_GET['course_id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = [
                'full_name' => $this->getPost('full_name'),
                'email' => $this->getPost('email'),
                'phone' => $this->getPost('phone'),
                'course_id' => (int)$this->getPost('course_id'),
                'address' => $this->getPost('address'),
                'gender' => $this->getPost('gender'),
                'csrf_token' => $this->getPost('csrf_token'),
            ];
            $old = $postData;

            if (!$this->verifyCsrfToken($postData['csrf_token'])) {
                $errors[] = 'Invalid form submission. Please try again.';
            }

            if (empty($postData['full_name'])) {
                $errors[] = 'Full name is required.';
            }
            if (empty($postData['email']) || !filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email address is required.';
            }
            if (empty($postData['phone'])) {
                $errors[] = 'Phone number is required.';
            }
            if (empty($postData['course_id'])) {
                $errors[] = 'Please select your course.';
            }
            if (empty($postData['address'])) {
                $errors[] = 'Address is required.';
            }
            if (!isset($_FILES['passport_photo']) || $_FILES['passport_photo']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Passport photo upload is required.';
            }

            if (empty($errors)) {
                $studentModel = new Student();
                $registrationModel = new Registration();
                $courseModel = new Course();
                $course = $courseModel->findById($postData['course_id']);

                if (!$course) {
                    $errors[] = 'Selected course does not exist.';
                }
            }

            if (empty($errors)) {
                $photo = $_FILES['passport_photo'];
                if ($photo['size'] > MAX_UPLOAD_SIZE || !in_array($photo['type'], ALLOWED_IMAGE_TYPES, true)) {
                    $errors[] = 'Passport photo must be JPG or PNG and under 2MB.';
                }
            }

            if (empty($errors)) {
                $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $fileName = 'passport_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                $uploadDir = UPLOAD_PATH . '/passports';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                move_uploaded_file($photo['tmp_name'], $uploadDir . '/' . $fileName);

                $studentData = [
                    'student_id' => '',
                    'full_name' => $postData['full_name'],
                    'email' => $postData['email'],
                    'phone' => $postData['phone'],
                    'address' => $postData['address'],
                    'gender' => $postData['gender'],
                    'passport_photo' => 'passports/' . $fileName,
                    'status' => 'Pending',
                ];

                $studentId = $studentModel->create($studentData);
                $student = $studentModel->findById($studentId);
                $studentCode = $studentModel->generateStudentId($studentId);
                $studentModel->update($studentId, array_merge($student, ['student_id' => $studentCode]));

                $registrationModel->create([
                    'student_id' => $studentId,
                    'course_id' => $postData['course_id'],
                    'status' => 'Pending',
                    'payment_status' => 'Unpaid',
                    'payment_reference' => null,
                ]);

                $_SESSION['student_logged_in'] = true;
                $_SESSION['student_id'] = $studentCode;
                $this->redirect('index.php?page=student_dashboard');
            }
        }

        $this->render('public/apply', compact('courses', 'settings', 'errors', 'old'));
    }
}
