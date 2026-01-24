<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
date_default_timezone_set('Asia/Dhaka');
define('ROOT_PATH', dirname(__DIR__)); // Define the root project path

require_once '../app/core/Router.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/functions.php'; // Add this line
require_once '../app/config/config.php'; // Ensure config is loaded too

// Instantiate Router
$router = new Router();

require_once '../app/controllers/HomeController.php';

// Define Routes
$router->add('GET', '', 'HomeController', 'index');
$router->add('GET', 'home', 'HomeController', 'index');
$router->add('GET', 'home/certificate', 'HomeController', 'certificate');
$router->add('GET', 'auth', 'HomeController', 'auth');
$router->add('GET', 'login', 'AuthController', 'login');
$router->add('POST', 'login', 'AuthController', 'login');
$router->add('GET', 'register', 'AuthController', 'register');
$router->add('POST', 'register', 'AuthController', 'register');
$router->add('GET', 'logout', 'AuthController', 'logout');
$router->add('GET', 'otp/verify', 'AuthController', 'showVerifyOtp');
$router->add('POST', 'otp/verify', 'AuthController', 'verifyOtp');

$router->add('GET', 'forgot_password/resend', 'AuthController', 'resendOtp');
$router->add('GET', 'forgot_password', 'AuthController', 'showForgotPassword');
$router->add('POST', 'forgot_password', 'AuthController', 'forgotPassword');
$router->add('GET', 'reset_password', 'AuthController', 'showResetPassword');
$router->add('POST', 'reset_password', 'AuthController', 'resetPassword');

$router->add('GET', 'home/dashboard', 'HomeController', 'dashboard');
$router->add('POST', 'home/bookmark', 'NoteController', 'toggleBookmark');
$router->add('GET', 'admin/dashboard', 'AdminController', 'dashboard');
$router->add('GET', 'admin/users', 'AdminController', 'users');
$router->add('POST', 'admin/user/role', 'AdminController', 'updateUserRole');
$router->add('GET', 'admin/user/delete', 'AdminController', 'deleteUser');
$router->add('GET', 'admin/analytics', 'AdminController', 'analytics');
$router->add('GET', 'admin/resource_analytics', 'AdminController', 'resourceAnalytics');
$router->add('GET', 'admin/active_users', 'AdminController', 'activeUsers');
$router->add('GET', 'admin/reports', 'AdminController', 'reports');
$router->add('GET', 'admin/awards', 'AdminController', 'awards');
$router->add('POST', 'admin/sendCertificate', 'AdminController', 'sendCertificate');

$router->add('GET', 'upload', 'NoteController', 'create');
$router->add('POST', 'note/store', 'NoteController', 'store');
$router->add('GET', 'preview/note', 'NoteController', 'show');
$router->add('GET', 'note/download', 'NoteController', 'download'); 
$router->add('POST', 'note/rate', 'NoteController', 'rate');

$router->add('GET', 'resources', 'ResourceController', 'index');
$router->add('GET', 'resources/subject', 'ResourceController', 'subject');
$router->add('GET', 'resources/list', 'ResourceController', 'list');
$router->add('GET', 'admin/manage_resources', 'AdminController', 'manageResources');
$router->add('POST', 'admin/resources/upload', 'AdminController', 'uploadResource');
$router->add('POST', 'admin/resources/delete', 'AdminController', 'deleteResource');
$router->add('POST', 'admin/resources/status', 'AdminController', 'updateResourceStatus');

$router->add('GET', 'admin/pending_notes', 'AdminController', 'pendingNotes');
$router->add('GET', 'admin/note/approve', 'AdminController', 'approveNote');
$router->add('GET', 'admin/note/reject', 'AdminController', 'rejectNote');

$router->add('GET', 'profile', 'UserController', 'profile');
$router->add('POST', 'user/update_profile', 'UserController', 'updateProfile');
$router->add('POST', 'user/update_password', 'UserController', 'updatePassword');

$router->add('GET', 'settings', 'UserController', 'settings');
$router->add('POST', 'settings/update_name', 'UserController', 'updateSettingsName');
$router->add('POST', 'settings/update_password', 'UserController', 'updateSettingsPassword');
$router->add('POST', 'settings/update_password', 'UserController', 'updateSettingsPassword');
$router->add('POST', 'settings/demote', 'UserController', 'demoteModerator');

$router->add('GET', 'user/dashboard', 'UserController', 'dashboard');
$router->add('GET', 'leaderboard', 'LeaderboardController', 'index');
$router->add('GET', 'note/my_notes', 'NoteController', 'myNotes');
$router->add('POST', 'note/update', 'NoteController', 'update');

// Dispatch
$uri = isset($_GET['url']) ? $_GET['url'] : '';
$router->dispatch($uri);
