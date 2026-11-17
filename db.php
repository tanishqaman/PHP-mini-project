<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "online_voting_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Auto-create 2 Admins (Tanishq & Barnali) if they don't exist
$default_admins = [
    ['Tanishq', 'ADMIN001', 'tanishq@college.edu', 'tanishq123'],
    ['Barnali', 'ADMIN002', 'barnali@college.edu', 'barnali123']
];

foreach ($default_admins as $adm) {
    $check = $conn->prepare("SELECT id FROM users WHERE voter_id = ?");
    $check->bind_param("s", $adm[1]);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        $hash = password_hash($adm[3], PASSWORD_BCRYPT);
        $ins = $conn->prepare("INSERT INTO users (full_name, voter_id, email, password, role, has_voted) VALUES (?, ?, ?, ?, 'admin', 0)");
        $ins->bind_param("ssss", $adm[0], $adm[1], $adm[2], $hash);
        $ins->execute();
        $ins->close();
    }
    $check->close();
}
?>