<?php

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$message = "";


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM experiences WHERE id = ?")->execute([$id]);
    $message = "Experience deleted successfully.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $year = trim($_POST['year']);

    if (!empty($title) && !empty($description)) {
        $stmt = $pdo->prepare("INSERT INTO experiences (title, description, year) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $year]);
        $message = "Experience added successfully.";
    } else {
        $message = "error:Title and description are required.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $year = trim($_POST['year']);

    if (!empty($title) && !empty($description)) {
        $stmt = $pdo->prepare("UPDATE experiences SET title=?, description=?, year=? WHERE id=?");
        $stmt->execute([$title, $description, $year, $id]);
        $message = "Experience updated successfully.";
    } else {
        $message = "error:Title and description are required.";
    }
}


$edit_exp = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $e = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
    $e->execute([$id]);
    $edit_exp = $e->fetch(PDO::FETCH_ASSOC);
}

$experiences = $pdo->query("SELECT * FROM experiences ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Experience - Admin</title>
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
        <h1>Manage Experience</h1>
    </div>

    <?php if (!empty($message)): ?>
        <?php if (strpos($message, 'error:') === 0): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars(substr($message, 6)); ?></div>
        <?php else: ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">
        <?php echo $edit_exp ? "Edit Experience" : "Add New Experience"; ?>
    </h2>
    <div class="admin-form" style="margin-bottom:30px;">
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_exp ? 'edit' : 'add'; ?>">
            <?php if ($edit_exp): ?>
                <input type="hidden" name="id" value="<?php echo $edit_exp['id']; ?>">
            <?php endif; ?>

            <label>Title *</label>
            <input type="text" name="title" placeholder="e.g. Freelance Video Editor"
                   value="<?php echo $edit_exp ? htmlspecialchars($edit_exp['title']) : ''; ?>" required>

            <label>Description *</label>
            <textarea name="description" placeholder="Describe this experience..." required><?php echo $edit_exp ? htmlspecialchars($edit_exp['description']) : ''; ?></textarea>

            <label>Year / Period</label>
            <input type="text" name="year" placeholder="e.g. 2023 - Present"
                   value="<?php echo $edit_exp ? htmlspecialchars($edit_exp['year']) : ''; ?>">

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_exp ? "Update Experience" : "Add Experience"; ?>
                </button>
                <?php if ($edit_exp): ?>
                    <a href="experiences.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">All Experiences</h2>
    <?php if (count($experiences) > 0): ?>
    <table class="admin-table">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Year</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($experiences as $ex): ?>
        <tr>
            <td><?php echo htmlspecialchars($ex['title']); ?></td>
            <td><?php echo htmlspecialchars(substr($ex['description'], 0, 60)) . '...'; ?></td>
            <td><?php echo htmlspecialchars($ex['year']); ?></td>
            <td class="admin-links">
                <a href="experiences.php?edit=<?php echo $ex['id']; ?>" class="btn btn-secondary" style="padding:5px 10px; font-size:13px;">Edit</a>
                <a href="experiences.php?delete=<?php echo $ex['id']; ?>" class="btn btn-danger" style="padding:5px 10px; font-size:13px;"
                   onclick="return confirm('Delete this experience?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="color:#888;">No experiences yet. Add one above!</p>
    <?php endif; ?>
</div>

</body>
</html>