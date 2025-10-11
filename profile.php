<?php
session_start();
include("includes/db.php");

// Security: must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: main_index.php#login");
    exit();
}

$userId = intval($_SESSION['user_id']);

// Messages
$profile_message = "";
$profile_error = "";
$password_message = "";
$password_error = "";

// Fetch current user info
$stmt = $conn->prepare("SELECT name, email, role, points, password FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name']);
    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
        $stmt->bind_param("si", $new_name, $userId);
        $stmt->execute();

        $_SESSION['user_name'] = $new_name;
        $user['name'] = $new_name;
        $profile_message = " Profile updated successfully!";
    } else {
        $profile_error = " Name cannot be empty.";
    }
}

// Handle Password Update
if (isset($_POST['update_password'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if (empty($new_pass) || empty($confirm_pass)) {
        $password_error = " Please enter and confirm your new password.";
    } elseif ($new_pass !== $confirm_pass) {
        $password_error = " New password and confirm password do not match.";
    } else {
        $errors = [];
        if (strlen($new_pass) < 8) $errors[] = " Password must be at least 8 characters.";
        if (!preg_match("/[A-Z]/", $new_pass)) $errors[] = " Must include 1 uppercase letter.";
        if (!preg_match("/[a-z]/", $new_pass)) $errors[] = " Must include 1 lowercase letter.";
        if (!preg_match("/[0-9]/", $new_pass)) $errors[] = " Must include 1 number.";
        if (!preg_match("/[@$!%*?&#]/", $new_pass)) $errors[] = " Must include 1 special character.";

        if (!empty($errors)) {
            $password_error = implode("<br>", $errors);
        } else {
            if (password_verify($current_pass, $user['password'])) {
                $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $stmt->bind_param("si", $hashed, $userId);
                $stmt->execute();
                $password_message = " Password updated successfully!";
            } else {
                $password_error = " Current password is incorrect.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="assets/css/profile.css">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav flex-column">
            <li><a href="user_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
            <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
            <li><a href="upload.php" class="nav-link"><i class="fa fa-upload me-2"></i>Upload Notes</a></li>
            <li><a href="leaderboard.php" class="nav-link"><i class="fa fa-trophy me-2"></i>Leaderboard</a></li>
            <li class="active"><a href="show_uploaded.php" class="nav-link"><i class="fa fa-file me-2"></i>Uploaded Notes</a></li>
            <li><a href="profile.php" class="nav-link active"><i class="fa fa-user me-2"></i>Profile</a></li>
        </ul>
        <div class="logout mt-auto px-3 pb-3">
            <a href="logout.php" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1">
        <!-- Topbar -->
        <header class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn text-white p-0 border-0">
                    <i class="fa fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-semibold">My Profile - <?php echo htmlspecialchars($_SESSION['user_name']); ?></h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark fw-bold"><?php echo ucfirst($user['role']); ?></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </header>

        <!-- Profile & Password Sections -->
        <div class="container py-2">
            <div class="row g-4">
                <!-- Profile Info -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <?php if (!empty($profile_message)) echo "<div class='alert alert-success'>$profile_message</div>"; ?>
                            <?php if (!empty($profile_error)) echo "<div class='alert alert-danger'>$profile_error</div>"; ?>

                            <h5 class="card-title mb-3"><i class="fa fa-user me-2 text-primary"></i>Profile Info</h5>
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Points</label>
                                    <input type="text" class="form-control" value="<?php echo intval($user['points']); ?>" disabled>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary w-100">Save Profile</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Password Update -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <?php if (!empty($password_message)) echo "<div class='alert alert-success'>$password_message</div>"; ?>
                            <?php if (!empty($password_error)) echo "<div class='alert alert-danger'>$password_error</div>"; ?>

                            <h5 class="card-title mb-3"><i class="fa fa-lock me-2 text-warning"></i>Change Password</h5>
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                                </div>
                                <button type="submit" name="update_password" class="btn btn-warning w-100 text-white">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin_dashboard.js"></script>
</body>
</html>
