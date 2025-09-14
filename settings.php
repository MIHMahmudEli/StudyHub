<?php
session_start();
include("includes/db.php");

// Security: only admin or moderator allowed
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'moderator'])) {
    header("Location: index.php#login");
    exit();
}

$role = $_SESSION['role'];

// Messages for sections
$nameMessage = "";
$passMessage = "";
$modMessage = "";
$nameError = "";
$passError = "";

// --- Handle Name Update ---
if (isset($_POST['update_name'])) {
    $new_name = trim($_POST['name']);
    if (!empty($new_name)) {
        $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
        $stmt->bind_param("si", $new_name, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $new_name;
            $nameMessage = "Name updated successfully!";
        } else {
            $nameError = "Failed to update name.";
        }
    } else {
        $nameError = "Name cannot be empty.";
    }
}

// --- Handle Password Update ---
if (isset($_POST['update_password'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if ($new_pass !== $confirm_pass) {
        $passError = "New password and confirm password do not match.";
    } else {
        // Get current hashed password
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
            if ($stmt->execute()) {
                $passMessage = "Password updated successfully!";
            } else {
                $passError = "Failed to update password.";
            }
        } else {
            $passError = "Current password is incorrect.";
        }
    }
}

// --- Handle Demote Moderator ---
if (isset($_POST['demote_user'])) {
    $mod_id = intval($_POST['mod_id']);
    $stmt = $conn->prepare("UPDATE users SET role='student' WHERE id=? AND role='moderator'");
    $stmt->bind_param("i", $mod_id);
    if ($stmt->execute()) {
        $modMessage = "Moderator demoted to student successfully!";
    } else {
        $modMessage = "Failed to demote moderator. Try again.";
    }
}

// --- Get All Moderators ---
$moderators = $conn->query("SELECT id, name, email FROM users WHERE role='moderator'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings - StudyHub</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="assets/css/settings.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i> <span>StudyHub</span>
        </div>
        <ul class="nav">
            <li><a href="admin_dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="pending_notes.php"><i class="fa fa-file"></i> <span>Notes</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="manage_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
            <?php } ?>

            <li><a href="trending_subjects.php"><i class="fa fa-chart-bar"></i> <span>Analytics</span></a></li>

            <?php if ($role === 'admin') { ?>
                <li><a href="#"><i class="fa fa-file-alt"></i> <span>Reports</span></a></li>
            <?php } ?>
            <li><a href="home.php"><i class="fa fa-book"></i> <span>Browse Notes</span></a></li>
            <li><a href="show_uploaded.php"><i class="fa fa-upload"></i> <span>Uploaded Notes</span></a></li>
            <li class="active"><a href="settings.php"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
        </ul>
        <div class="logout">
            <a href="logout.php" ><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-toggle">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>Settings</h2>
            </div>
            <div class="topbar-right">
                <span class="role"><?php echo $role ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </header>

        <!-- Name Section -->
        <section class="settings-section">
            <h3>👤 Update Name</h3>
            <?php if (!empty($nameMessage)) echo "<div class='success'>$nameMessage</div>"; ?>
            <?php if (!empty($nameError)) echo "<div class='error'>$nameError</div>"; ?>
            <form method="post">
                <label>New Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                <button type="submit" name="update_name" class="btn btn-primary">Save Name</button>
            </form>
        </section>

        <!-- Password Section -->
        <section class="settings-section">
            <h3>🔒 Change Password</h3>
            <?php if (!empty($passMessage)) echo "<div class='success'>$passMessage</div>"; ?>
            <?php if (!empty($passError)) echo "<div class='error'>$passError</div>"; ?>
            <form method="post">
                <label>Current Password:</label>
                <input type="password" name="current_password" placeholder="Enter current password">
                <label>New Password:</label>
                <input type="password" name="new_password" placeholder="Enter new password">
                <label>Confirm New Password:</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password">
                <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
            </form>
        </section>

        <!-- Demote Moderators Section -->
    <?php if ($role === 'admin') { ?>
        <section class="settings-section">
            <h3>🧑‍⚖️ Demote Moderator</h3>
            <?php if (!empty($modMessage)) echo "<div class='success'>$modMessage</div>"; ?>
            <?php if (empty($moderators)) { ?>
                <p>No moderators found.</p>
            <?php } else { ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($moderators as $mod) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($mod['name']); ?></td>
                            <td><?php echo htmlspecialchars($mod['email']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="mod_id" value="<?php echo $mod['id']; ?>">
                                    <button type="submit" name="demote_user" class="btn btn-danger">Demote</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </section>
    <?php } ?>
    </main>
</body>
</html>
