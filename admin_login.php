<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voter_id = trim($_POST['voter_id']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE voter_id = ? AND role = 'admin'");
    $stmt->bind_param("s", $voter_id);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['full_name'] = $admin['full_name'];
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    } else {
        $error = "Invalid Admin Credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container">
        <div class="auth-wrapper">
            <div class="auth-box">
                <h2>Admin Login Portal</h2>
                <?php if (isset($error)) echo '<p class="alert alert-danger">'.$error.'</p>'; ?>
                <form action="admin_login.php" method="POST">
                    <div class="form-group">
                        <label>Admin Voter ID</label>
                        <input type="text" name="voter_id" required placeholder="ADMIN001">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Admin Login</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
