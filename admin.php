<?php
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Add Candidate
if (isset($_POST['add_candidate'])) {
    $name = trim($_POST['name']);
    $party = trim($_POST['party']);
    $manifesto = trim($_POST['manifesto']);

    $stmt = $conn->prepare("INSERT INTO candidates (name, party, manifesto) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $party, $manifesto);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// Delete Candidate
if (isset($_GET['delete_cand'])) {
    $id = intval($_GET['delete_cand']);
    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}

// Stats
$voters_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'voter'")->fetch_assoc()['c'];
$voted_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'voter' AND has_voted = 1")->fetch_assoc()['c'];
$candidates = $conn->query("SELECT * FROM candidates ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">🔒 Admin Dashboard</div>
        <div>
            Logged as Admin: <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </header>

    <main class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Registered Voters</h3>
                <p class="stat-num"><?php echo $voters_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Votes Cast</h3>
                <p class="stat-num"><?php echo $voted_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Voter Turnout</h3>
                <p class="stat-num"><?php echo $voters_count > 0 ? round(($voted_count/$voters_count)*100, 1) : 0; ?>%</p>
            </div>
        </div>

        <div class="admin-grid">
            <div class="card">
                <h3>Add New Candidate</h3>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="add_candidate" value="1">
                    <div class="form-group">
                        <label>Candidate Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Party / Organization</label>
                        <input type="text" name="party" required>
                    </div>
                    <div class="form-group">
                        <label>Manifesto Summary</label>
                        <textarea name="manifesto" required rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Candidate</button>
                </form>
            </div>

            <div class="card">
                <h3>Manage Candidates & Votes</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Party</th>
                            <th>Votes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($c = $candidates->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['name']); ?></td>
                            <td><?php echo htmlspecialchars($c['party']); ?></td>
                            <td><strong><?php echo $c['vote_count']; ?></strong></td>
                            <td><a href="admin.php?delete_cand=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete candidate?')">Remove</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
