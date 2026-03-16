<?php

require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();

$message = "";


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
    $message = "Project deleted successfully.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tech = trim($_POST['tech_used']);
    $url = trim($_POST['project_url']);

    if (!empty($title) && !empty($description)) {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, tech_used, project_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $tech, $url]);
        $message = "Project added successfully.";
    } else {
        $message = "error:Title and description are required.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $tech = trim($_POST['tech_used']);
    $url = trim($_POST['project_url']);

    if (!empty($title) && !empty($description)) {
        $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, tech_used=?, project_url=? WHERE id=?");
        $stmt->execute([$title, $description, $tech, $url, $id]);
        $message = "Project updated successfully.";
    } else {
        $message = "error:Title and description are required.";
    }
}


$edit_project = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_project = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $edit_project->execute([$id]);
    $edit_project = $edit_project->fetch(PDO::FETCH_ASSOC);
}


$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Projects - Admin</title>
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
        <h1>Manage Projects</h1>
    </div>

    <?php if (!empty($message)): ?>
        <?php if (strpos($message, 'error:') === 0): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars(substr($message, 6)); ?></div>
        <?php else: ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    <?php endif; ?>

  
    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">
        <?php echo $edit_project ? "Edit Project" : "Add New Project"; ?>
    </h2>
    <div class="admin-form" style="margin-bottom:30px;">
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_project ? 'edit' : 'add'; ?>">
            <?php if ($edit_project): ?>
                <input type="hidden" name="id" value="<?php echo $edit_project['id']; ?>">
            <?php endif; ?>

            <label>Project Title *</label>
            <input type="text" name="title" placeholder="Enter project title" 
                   value="<?php echo $edit_project ? htmlspecialchars($edit_project['title']) : ''; ?>" required>

            <label>Description *</label>
            <textarea name="description" placeholder="Describe the project..." required><?php echo $edit_project ? htmlspecialchars($edit_project['description']) : ''; ?></textarea>

            <label>Technologies Used</label>
            <input type="text" name="tech_used" placeholder="e.g. PHP, MySQL, HTML, CSS"
                   value="<?php echo $edit_project ? htmlspecialchars($edit_project['tech_used']) : ''; ?>">

            <label>Project URL</label>
            <input type="text" name="project_url" placeholder="https://github.com/..."
                   value="<?php echo $edit_project ? htmlspecialchars($edit_project['project_url']) : ''; ?>">

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_project ? "Update Project" : "Add Project"; ?>
                </button>
                <?php if ($edit_project): ?>
                    <a href="projects.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

  
    <h2 style="color:#b84bea; font-size:20px; margin-bottom:15px;">All Projects</h2>
    <?php if (count($projects) > 0): ?>
    <table class="admin-table">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Tech Used</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($projects as $p): ?>
        <tr>
            <td><?php echo htmlspecialchars($p['title']); ?></td>
            <td><?php echo htmlspecialchars(substr($p['description'], 0, 60)) . '...'; ?></td>
            <td><?php echo htmlspecialchars($p['tech_used']); ?></td>
            <td class="admin-links">
                <a href="projects.php?edit=<?php echo $p['id']; ?>" class="btn btn-secondary" style="padding:5px 10px; font-size:13px;">Edit</a>
                <a href="projects.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger" style="padding:5px 10px; font-size:13px;"
                   onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p style="color:#888;">No projects yet. Add one above!</p>
    <?php endif; ?>
</div>

</body>
</html>