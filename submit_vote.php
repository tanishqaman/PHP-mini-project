<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate_id'])) {
    $user_id = $_SESSION['user_id'];
    $candidate_id = intval($_POST['candidate_id']);

    // Verify user has not voted
    $stmt = $conn->prepare("SELECT has_voted FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res['has_voted'] == 0) {
        $conn->begin_transaction();
        try {
            // Update user status
            $stmt1 = $conn->prepare("UPDATE users SET has_voted = 1, voted_candidate_id = ? WHERE id = ?");
            $stmt1->bind_param("ii", $candidate_id, $user_id);
            $stmt1->execute();

            // Increment candidate vote count
            $stmt2 = $conn->prepare("UPDATE candidates SET vote_count = vote_count + 1 WHERE id = ?");
            $stmt2->bind_param("i", $candidate_id);
            $stmt2->execute();

            // Insert into votes audit log
            $stmt3 = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)");
            $stmt3->bind_param("ii", $user_id, $candidate_id);
            $stmt3->execute();

            $conn->commit();
            $_SESSION['has_voted'] = 1;
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Voting transaction failed.";
        }
    }
    header("Location: vote.php");
    exit();
}
?>
