<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: vote.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System - Portal</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">🔒 SecureVote System</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="results.php">Live Results</a>
            <a href="admin_login.php">Admin Access</a>
        </nav>
    </header>

    <main class="container">
        <div class="auth-wrapper">
            <div class="auth-box" id="login-box">
                <h2>Voter Login</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert alert-danger">' . $_SESSION['error'] . '</p>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<p class="alert alert-success">' . $_SESSION['success'] . '</p>';
                    unset($_SESSION['success']);
                }
                ?>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label>Voter ID</label>
                        <input type="text" name="voter_id" required placeholder="e.g. VOTE101">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sign In to Vote</button>
                </form>
                <p class="toggle-text">New Voter? <a href="#" id="show-register">Register Here</a></p>
            </div>

            <div class="auth-box hidden" id="register-box">
                <h2>Register as Voter</h2>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="action" value="register">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    <div class="form-group">
                        <label>Choose Voter ID</label>
                        <input type="text" name="voter_id" required placeholder="e.g. VOTE101">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Create Account</button>
                </form>
                <p class="toggle-text">Already Registered? <a href="#" id="show-login">Sign In</a></p>
            </div>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>
