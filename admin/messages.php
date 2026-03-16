<?php

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$id]);
    header("Location: messages.php?deleted=1");
    exit();
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY sent_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Contact Messages - Admin</title>
</head>
<body>

<nav>
    <a href="../index.php" class="logo">Gerald Belena</a>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="../index.php">View Portfolio</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="admin-container">
    <div class="admin-header">
        <h1>Contact Messages</h1>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Message deleted successfully.</div>
    <?php endif; ?>

    <?php if (count($messages) > 0): ?>
    <table class="admin-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($messages as $msg): ?>
        <tr>
            <td><?php echo htmlspecialchars($msg['name']); ?></td>
            <td><?php echo htmlspecialchars($msg['email']); ?></td>
            <td><?php echo htmlspecialchars($msg['message']); ?></td>
            <td style="font-size:13px; color:#888;"><?php echo $msg['sent_at']; ?></td>
            <td>
                <a href="messages.php?delete=<?php echo $msg['id']; ?>" class="btn btn-danger" 
                   style="padding:5px 10px; font-size:13px;"
                   onclick="return confirm('Delete this message?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="color:#888;">No messages yet.</p>
    <?php endif; ?>
</div>

</body>
</html>