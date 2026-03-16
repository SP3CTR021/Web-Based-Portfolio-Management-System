<?php

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$message = "";


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM skills WHERE id = ?")->execute([$id]);
    $message = "Skill deleted successfully.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $skill_name = trim($_POST['skill_name']);
    $category = trim($_POST['category']);

    if (!empty($skill_name)) {
        $stmt = $pdo->prepare("INSERT INTO skills (skill_name, category) VALUES (?, ?)");
        $stmt->execute([$skill_name, $category]);
        $message = "Skill added successfully.";
    } else {
        $message = "error:Skill name is required.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $skill_name = trim($_POST['skill_name']);
    $category = trim($_POST['category']);

    if (!empty($skill_name)) {
        $stmt = $pdo->prepare("UPDATE skills SET skill_name=?, category=? WHERE id=?");
        $stmt->execute([$skill_name, $category, $id]);
        $message = "Skill updated successfully.";
    } else {
        $message = "error:Skill name is required.";
    }
}


$edit_skill = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $s = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $s->execute([$id]);
    $edit_skill = $s->fetch(PDO::FETCH_ASSOC);
}

$skills = $pdo->query("SELECT * FROM skills ORDER BY category, skill_name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Skills - Admin</title>
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
        <h1>Manage Skills</h1>
    </div>

    <?php if (!empty($message)): ?>
        <?php if (strpos($message, 'error:') === 0): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars(substr($message, 6)); ?></div>
        <?php else: ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    <?php endif; ?>

 
    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">
        <?php echo $edit_skill ? "Edit Skill" : "Add New Skill"; ?>
    </h2>
    <div class="admin-form" style="margin-bottom:30px;">
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_skill ? 'edit' : 'add'; ?>">
            <?php if ($edit_skill): ?>
                <input type="hidden" name="id" value="<?php echo $edit_skill['id']; ?>">
            <?php endif; ?>

            <label>Skill Name *</label>
            <input type="text" name="skill_name" placeholder="e.g. PHP, MySQL, Photoshop"
                   value="<?php echo $edit_skill ? htmlspecialchars($edit_skill['skill_name']) : ''; ?>" required>

            <label>Category</label>
            <input type="text" name="category" placeholder="e.g. Web Development, Design, Database"
                   value="<?php echo $edit_skill ? htmlspecialchars($edit_skill['category']) : ''; ?>">

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_skill ? "Update Skill" : "Add Skill"; ?>
                </button>
                <?php if ($edit_skill): ?>
                    <a href="skills.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">All Skills</h2>
    <?php if (count($skills) > 0): ?>
    <table class="admin-table">
        <tr>
            <th>Skill Name</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($skills as $sk): ?>
        <tr>
            <td><?php echo htmlspecialchars($sk['skill_name']); ?></td>
            <td><?php echo htmlspecialchars($sk['category']); ?></td>
            <td class="admin-links">
                <a href="skills.php?edit=<?php echo $sk['id']; ?>" class="btn btn-secondary" style="padding:5px 10px; font-size:13px;">Edit</a>
                <a href="skills.php?delete=<?php echo $sk['id']; ?>" class="btn btn-danger" style="padding:5px 10px; font-size:13px;"
                   onclick="return confirm('Delete this skill?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="color:#888;">No skills yet. Add one above!</p>
    <?php endif; ?>
</div>

</body>
</html>