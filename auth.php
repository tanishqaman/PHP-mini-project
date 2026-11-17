<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $voter_id = trim($_POST['voter_id']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check unique voter_id or email
        $stmt = $conn->prepare("SELECT id FROM users WHERE voter_id = ? OR email = ?");
        $stmt->bind_param("ss", $voter_id, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Voter ID or Email already registered!";
            header("Location: index.php");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO users (voter_id, full_name, email, password, role) VALUES (?, ?, ?, ?, 'voter')");
        $stmt->bind_param("ssss", $voter_id, $full_name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please login.";
        } else {
            $_SESSION['error'] = "Error registering voter.";
        }
        header("Location: index.php");
        exit();

    } elseif ($action === 'login') {
        $voter_id = trim($_POST['voter_id']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE voter_id = ?");
        $stmt->bind_param("s", $voter_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['voter_id'] = $user['voter_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['has_voted'] = $user['has_voted'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: vote.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid Voter ID or Password!";
            header("Location: index.php");
            exit();
        }
    }
}
?>
