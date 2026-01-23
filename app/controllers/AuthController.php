<?php

require_once '../app/models/User.php';
require_once '../app/core/Mailer.php';

class AuthController extends Controller {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user) {
                if ($user['verified'] == 0) {
                    $_SESSION['error'] = "⚠ Please verify your email before logging in. Check your inbox.";
                    $this->redirect('auth'); // Redirect to home/login
                }

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['points'] = $user['points'];

                    $userModel->logEvent($user['id'], 'view');

                    if ($user['role'] === 'admin' || $user['role'] === 'moderator') {
                        $this->redirect('admin/dashboard');
                    } else {
                        $this->redirect('home/dashboard'); // Helper redirect uses url()
                    }
                } else {
                    $_SESSION['error'] = "Invalid email or password.";
                    $this->redirect('auth');
                }
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                $this->redirect('auth');
            }
        } else {
            // If GET, just show home which has login form
            $this->redirect('auth');
        }
    }

    public function register() {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

            $_SESSION['reg_form_data'] = [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ];

            if ($password !== $confirmPassword) {
                $_SESSION['reg_error'] = "Passwords do not match.";
                $this->redirect('#register');
            }

            $userModel = new User();
            $existingUser = $userModel->findByEmail($email);

            if ($existingUser) {
                $_SESSION['reg_error'] = "This email is already registered.";
                $this->redirect('#register');
            }

            // Generate OTP
            $_SESSION['otp'] = rand(100000, 999999);
            $_SESSION['otp_context'] = 'register';

            if (Mailer::sendOTP($email, $name, $_SESSION['otp'], 'register')) {
                $this->redirect('otp/verify');
            } else {
                $_SESSION['reg_error'] = "Failed to send OTP email.";
                $this->redirect('#register');
            }
        } else {
            $this->redirect('#register');
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth');
    }

    public function showVerifyOtp() {
        if (!isset($_SESSION['reg_form_data']) || !isset($_SESSION['otp'])) {
            $this->redirect('register');
        }
        $this->view('auth/verify_otp');
    }

    public function verifyOtp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if (isset($_POST['verify'])) {
                $entered_otp = $_POST['otp'];
                $session_otp = $_SESSION['otp'];

                if ($entered_otp == $session_otp) {
                    $context = $_SESSION['otp_context'] ?? 'register';

                    if ($context === 'register') {
                        $data = $_SESSION['reg_form_data'];
                        $userModel = new User();
                        if ($userModel->create($data['name'], $data['email'], $data['password'])) {
                            unset($_SESSION['reg_form_data']);
                            unset($_SESSION['otp']);
                            unset($_SESSION['otp_context']);
                            $_SESSION['reg_success'] = "Registration successful! Please login.";
                            $this->redirect('#login');
                        } else {
                            $err = "Database error or email exists.";
                            $this->view('auth/verify_otp', ['error' => $err]);
                        }
                    } elseif ($context === 'forgot_password') {
                        // OTP verified for reset
                        $_SESSION['otp_verified'] = true;
                        $this->redirect('reset_password');
                    }
                } else {
                    $this->view('auth/verify_otp', ['error' => "Invalid OTP"]);
                }
             }
        } else {
            $this->showVerifyOtp();
        }
    }

    public function showForgotPassword() {
        $this->view('auth/forgot_password');
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars(trim($_POST['email']));
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user) {
                $otp = rand(100000, 999999);
                $_SESSION['fp_email'] = $email;
                $_SESSION['fp_otp'] = $otp;
                $_SESSION['fp_otp_time'] = time();
                
                if (Mailer::sendOTP($email, $user['name'], $otp, 'forgot')) {
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        echo json_encode(['status' => 'success', 'message' => 'OTP sent to your email!']);
                        exit;
                    }
                    $_SESSION['fp_success'] = "OTP sent to your email!";
                    $this->redirect('forgot_password#forgot-password-step2');
                } else {
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to send reset OTP.']);
                        exit;
                    }
                    $_SESSION['fp_error'] = "Failed to send reset OTP.";
                    $this->redirect('forgot_password');
                }
            } else {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
                    exit;
                }
                $_SESSION['fp_error'] = "Email not found.";
                $this->redirect('forgot_password');
            }
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp_input = trim($_POST['verification_code']);
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (!isset($_SESSION['fp_email']) || !isset($_SESSION['fp_otp'])) {
                $_SESSION['fp_error'] = "Session expired. Try again.";
                $this->redirect('forgot_password');
            }

            if ($otp_input != $_SESSION['fp_otp']) {
                $_SESSION['fp_error'] = "Incorrect OTP!";
                $this->redirect('forgot_password#forgot-password-step2');
            }

            if ($new_password !== $confirm_password) {
                $_SESSION['fp_error'] = "Passwords do not match!";
                $this->redirect('forgot_password#forgot-password-step2');
            }

            $userModel = new User();
            if ($userModel->updatePasswordByEmail($_SESSION['fp_email'], $new_password)) {
                unset($_SESSION['fp_email'], $_SESSION['fp_otp'], $_SESSION['fp_otp_time']);
                $_SESSION['reg_success'] = "Password reset successfully. Please login.";
                $this->redirect('#login');
            } else {
                $_SESSION['fp_error'] = "Failed to update password.";
                $this->redirect('forgot_password#forgot-password-step2');
            }
        }
    }

    public function resendOtp() {
        if (isset($_SESSION['fp_email'])) {
            $email = $_SESSION['fp_email'];
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            
            $otp = rand(100000, 999999);
            $_SESSION['fp_otp'] = $otp;
            $_SESSION['fp_otp_time'] = time();

            if (Mailer::sendOTP($email, $user['name'], $otp, 'forgot')) {
                echo json_encode(['status' => 'success', 'message' => 'OTP resent successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Session expired.']);
        }
    }
}
