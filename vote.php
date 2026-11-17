<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'voter') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT has_voted, voted_candidate_id FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();

// Fetch candidates
$candidates = $conn->query("SELECT * FROM candidates ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting Portal - Cast Vote</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">🔒 SecureVote System</div>
        <div class="user-badge">
            Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong> (ID: <?php echo htmlspecialchars($_SESSION['voter_id']); ?>)
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </header>

    <main class="container">
        <h2>Official Election Ballot</h2>
        
        <?php if ($user_info['has_voted']): ?>
            <div class="card status-card">
                <div class="voted-badge">✅ VOTE SUBMITTED</div>
                <h3>Thank you for exercising your right to vote!</h3>
                <p>Your vote has been securely recorded in the system.</p>
                <a href="results.php" class="btn btn-primary">View Live Results</a>
            </div>
        <?php else: ?>
            <p class="subtitle">Select your preferred candidate below and click "Cast Vote". Note: You can only vote once.</p>
            
            <form action="submit_vote.php" method="POST" id="vote-form">
                <div class="candidates-grid">
                    <?php while($cand = $candidates->fetch_assoc()): ?>
                        <div class="candidate-card">
                            <div class="candidate-header">
                                <h3><?php echo htmlspecialchars($cand['name']); ?></h3>
                                <span class="party-tag"><?php echo htmlspecialchars($cand['party']); ?></span>
                            </div>
                            <p class="manifesto">"<?php echo htmlspecialchars($cand['manifesto']); ?>"</p>
                            <label class="radio-container">
                                <input type="radio" name="candidate_id" value="<?php echo $cand['id']; ?>" required>
                                <span class="checkmark">Select Candidate</span>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="text-center margin-top-20">
                    <button type="submit" class="btn btn-success btn-lg">Cast Final Vote</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <script src="js/script.js"></script>
</body>
</html>
