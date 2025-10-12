<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: main_index.php#login");
    exit();
}

$role = $_SESSION['role'];

// Messages
$nameMessage = $nameError = $passMessage = $passError = $modMessage = "";

// --- Handle Name Update ---
if (isset($_POST['update_name'])) {
    $new_name = trim($_POST['name']);
    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
        $stmt->bind_param("si", $new_name, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $new_name;
            $nameMessage = "Name updated successfully!";
        } else $nameError = "Failed to update name.";
    } else $nameError = "Name cannot be empty.";
}

// --- Handle Password Update ---
if (isset($_POST['update_password'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    // Server-side password policy: 8+ chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($pattern, $new_pass)) {
        $passError = "Password must be at least 8 characters long and include one uppercase, one lowercase, one number, and one special character.";
    } elseif ($new_pass !== $confirm_pass) {
        $passError = "New password and confirm password do not match.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($db_pass);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_pass, $db_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed, $_SESSION['user_id']);
            if ($stmt->execute()) $passMessage = "Password updated successfully!";
            else $passError = "Failed to update password.";
        } else $passError = "Current password is incorrect.";
    }
}

// --- Handle Demote Moderator ---
if (isset($_POST['demote_user'])) {
    $mod_id = intval($_POST['mod_id']);
    $stmt = $conn->prepare("UPDATE users SET role='student' WHERE id=? AND role='moderator'");
    $stmt->bind_param("i", $mod_id);
    if ($stmt->execute()) $modMessage = "Moderator demoted to student successfully!";
    else $modMessage = "Failed to demote moderator. Try again.";
}

// --- Get All Moderators ---
$moderators = $conn->query("SELECT id, name, email FROM users WHERE role='moderator'")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Settings - StudyHub</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="icon" type="image/svg+xml" href="favicon.svg">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Custom Styles -->
<link rel="stylesheet" href="assets/css/admin_dashboard.css?v=3.0">
<link rel="stylesheet" href="assets/css/settings.css?v=3.0">
</head>
<body>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="logo">
        <i class="fa fa-graduation-cap me-2"></i><span>StudyHub</span>
    </div>
    <ul class="nav flex-column px-2">
        <li><a href="admin_dashboard.php" class="nav-link"><i class="fa fa-home me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a href="pending_notes.php" class="nav-link"><i class="fa fa-file me-2"></i>Pending Notes</a></li>
        <?php if ($role === 'admin') { ?>
            <li><a href="manage_users.php" class="nav-link"><i class="fa fa-users me-2"></i>Users</a></li>
        <?php } ?>
        <li><a href="trending_subjects.php" class="nav-link"><i class="fa fa-chart-bar me-2"></i>Analytics</a></li>
        <?php if ($role === 'admin') { ?>
            <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-file-alt me-2"></i>Reports</a></li>
        <?php } ?>
        <li><a href="home.php" class="nav-link"><i class="fa fa-book me-2"></i>Browse Notes</a></li>
        <li><a href="show_uploaded.php" class="nav-link"><i class="fa fa-upload me-2"></i>Uploaded Notes</a></li>
        <li><a href="settings.php" class="nav-link active"><i class="fa fa-cog me-2"></i>Settings</a></li>
    </ul>
    <div class="logout px-3 mt-auto pb-3">
        <a href="logout.php" class="btn btn-light w-100"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
    </div>
</aside>

<!-- Main -->
<main class="main-content">
    <!-- Topbar -->
    <header class="topbar d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-toggle btn text-white p-0 border-0"><i class="fa fa-bars"></i></button>
            <h5 class="mb-0 fw-semibold">Settings</h5>
        </div>
        <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
            <span class="badge bg-light text-dark text-uppercase"><?php echo $role; ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </header>

    <!-- Settings Sections -->
    <section class="container-fluid py-4 px-lg-5">
        <div class="row g-4">
            <!-- Update Name -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3"><i class="fa fa-user text-primary me-2"></i>Update Name</h5>
                        <?php if ($nameMessage) echo "<div class='alert alert-success'>$nameMessage</div>"; ?>
                        <?php if ($nameError) echo "<div class='alert alert-danger'>$nameError</div>"; ?>
                        <form method="post" novalidate>
                            <div class="mb-3">
                                <label class="form-label">New Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                            </div>
                            <button type="submit" name="update_name" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3"><i class="fa fa-lock text-warning me-2"></i>Change Password</h5>
                        <?php if ($passMessage) echo "<div class='alert alert-success'>$passMessage</div>"; ?>
                        <?php if ($passError) echo "<div class='alert alert-danger'>$passError</div>"; ?>
                        <form method="post" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                                <div class="form-text text-muted">
                                    Must be 8+ characters with uppercase, lowercase, number & special character.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Demote Moderators -->
            <?php if ($role === 'admin') { ?>
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3"><i class="fa fa-user-shield text-danger me-2"></i>Manage Moderators</h5>
                        <?php if ($modMessage) echo "<div class='alert alert-info'>$modMessage</div>"; ?>
                        <?php if (empty($moderators)) { ?>
                            <p class="text-muted">No moderators found.</p>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($moderators as $mod) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($mod['name']); ?></td>
                                                <td><?php echo htmlspecialchars($mod['email']); ?></td>
                                                <td class="text-center">
                                                    <form method="post">
                                                        <input type="hidden" name="mod_id" value="<?php echo $mod['id']; ?>">
                                                        <button type="submit" name="demote_user" class="btn btn-sm btn-danger">Demote</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/admin_dashboard.js?v=3.0"></script>
</body>
</html>
