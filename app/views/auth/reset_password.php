<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - StudyHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/home-style.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f9; }
        .reset-card { max-width: 400px; margin: 100px auto; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-success { border-radius: 10px; padding: 10px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card reset-card border-0 p-4">
            <div class="text-center mb-4">
                <i class="fa fa-lock-open fa-3x text-success mb-3"></i>
                <h4 class="fw-bold">Reset Password</h4>
                <p class="text-muted">Enter your new password below</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger small py-2"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="<?php echo url('reset_password'); ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
