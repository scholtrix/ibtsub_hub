<?php

class StudentController extends BaseController
{
    public function dashboard()
    {
        $this->studentOnly();
        $studentModel = new Student();
        $registrationModel = new Registration();

        $student = $studentModel->findByStudentId($_SESSION['student_id']);
        $registration = $registrationModel->findByStudentId($student['id']);
        $settings = $this->settings;
        $paystackKey = getSettingValue('paystack_public_key');
        $this->render('student/dashboard', compact('student', 'registration', 'settings', 'paystackKey'));
    }

    public function edit()
    {
        $this->studentOnly();
        $studentModel = new Student();
        $student = $studentModel->findByStudentId($_SESSION['student_id']);
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrfToken($this->getPost('csrf_token'))) {
                $errors[] = 'Invalid request. Please try again.';
            }

            $fullName = $this->getPost('full_name');
            $email = $this->getPost('email');
            $phone = $this->getPost('phone');
            $address = $this->getPost('address');
            $gender = $this->getPost('gender');

            if (empty($fullName)) {
                $errors[] = 'Full name is required.';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email is required.';
            }
            if (empty($phone)) {
                $errors[] = 'Phone number is required.';
            }
            if (empty($address)) {
                $errors[] = 'Address is required.';
            }

            $passportPath = $student['passport_photo'];
            if (!empty($_FILES['passport_photo']['name'])) {
                $photo = $_FILES['passport_photo'];
                if ($photo['size'] > MAX_UPLOAD_SIZE || !in_array($photo['type'], ALLOWED_IMAGE_TYPES, true)) {
                    $errors[] = 'Passport photo must be JPG or PNG and under 2MB.';
                } else {
                    $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
                    $fileName = 'passport_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                    $uploadDir = UPLOAD_PATH . '/passports';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    move_uploaded_file($photo['tmp_name'], $uploadDir . '/' . $fileName);
                    $passportPath = 'passports/' . $fileName;
                }
            }

            if (empty($errors)) {
                $studentModel->updateProfile($student['id'], [
                    'full_name' => $fullName,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'gender' => $gender,
                    'passport_photo' => $passportPath,
                ]);
                $success = 'Profile updated successfully.';
                $student = $studentModel->findByStudentId($_SESSION['student_id']);
            }
        }

        $settings = $this->settings;
        $this->render('student/profile_edit', compact('student', 'settings', 'errors', 'success'));
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('index.php?page=home');
    }

    public function verifyPayment()
    {
        $this->studentOnly();

        $reference = $this->getPost('reference', $this->getPost('reference'));
        $registrationId = (int)($this->getPost('registration_id', $this->getPost('registration_id')));

        if (empty($reference) || empty($registrationId)) {
            $this->flash('error', 'Missing payment reference or registration information.');
            $this->redirect('index.php?page=student_dashboard');
        }

        $secretKey = getSettingValue('paystack_secret_key');
        if (empty($secretKey)) {
            $this->flash('error', 'Paystack secret key is not configured.');
            $this->redirect('index.php?page=student_dashboard');
        }

        $url = 'https://api.paystack.co/transaction/verify/' . urlencode($reference);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $secretKey,
            'Cache-Control: no-cache',
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $error) {
            $this->flash('error', 'Unable to verify payment at the moment.');
            $this->redirect('index.php?page=student_dashboard');
        }

        $data = json_decode($response, true);
        if (!$data || empty($data['data']) || $data['data']['status'] !== 'success') {
            $this->flash('error', 'Payment verification failed.');
            $this->redirect('index.php?page=student_dashboard');
        }

        $registrationModel = new Registration();
        $registrationModel->updatePaymentStatus($registrationId, 'Paid', $reference);
        $this->flash('success', 'Payment verified successfully.');
        $this->redirect('index.php?page=student_dashboard');
    }
}
