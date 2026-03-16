<?php

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$project_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$skill_count = $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$exp_count = $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn();
$msg_count = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin Dashboard - Portfolio</title>
</head>
<body>

<nav>
    <a href="../index.php" class="logo">Gerald Belena</a>
    <ul>
        <li><a href="../index.php">View Portfolio</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="admin-container">
    <div class="admin-header">
        <h1>Admin Dashboard</h1>
        <span style="color:#888;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
    </div>

  
    <div class="card-grid" style="margin-bottom:30px;">
        <div class="card" style="text-align:center;">
            <h3><?php echo $project_count; ?></h3>
            <p>Projects</p>
        </div>
        <div class="card" style="text-align:center;">
            <h3><?php echo $skill_count; ?></h3>
            <p>Skills</p>
        </div>
        <div class="card" style="text-align:center;">
            <h3><?php echo $exp_count; ?></h3>
            <p>Experiences</p>
        </div>
        <div class="card" style="text-align:center;">
            <h3><?php echo $msg_count; ?></h3>
            <p>Messages</p>
        </div>
    </div>

  
    <h2 style="color:#b84bea; margin-bottom:15px; font-size:22px;">Manage Portfolio</h2>
    <div class="admin-nav">
        <a href="projects.php" class="btn btn-primary">Manage Projects</a>
        <a href="skills.php" class="btn btn-primary">Manage Skills</a>
        <a href="experiences.php" class="btn btn-primary">Manage Experience</a>
        <a href="messages.php" class="btn btn-secondary">View Messages</a>
    </div>
</div>

</body>
</html>