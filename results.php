<?php
require_once 'db.php';

$res = $conn->query("SELECT * FROM candidates ORDER BY vote_count DESC");
$total_res = $conn->query("SELECT SUM(vote_count) as total FROM candidates")->fetch_assoc();
$total_votes = $total_res['total'] ? $total_res['total'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Election Results</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">🔒 SecureVote System</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="results.php" class="active">Live Results</a>
        </nav>
    </header>

    <main class="container">
        <h2>Live Election Results & Analytics</h2>
        <p class="subtitle">Real-time vote count breakdown across registered candidates.</p>

        <div class="results-wrapper">
            <?php 
            $rank = 1;
            while($cand = $res->fetch_assoc()): 
                $percentage = round(($cand['vote_count'] / $total_votes) * 100, 1);
            ?>
                <div class="result-bar-card">
                    <div class="result-meta">
                        <span class="cand-name">#<?php echo $rank++; ?> <?php echo htmlspecialchars($cand['name']); ?> (<?php echo htmlspecialchars($cand['party']); ?>)</span>
                        <span class="vote-nums"><strong><?php echo $cand['vote_count']; ?> Votes</strong> (<?php echo $percentage; ?>%)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>
