<?php

require_once 'includes/db.php';


$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills ORDER BY category, skill_name")->fetchAll(PDO::FETCH_ASSOC);
$experiences = $pdo->query("SELECT * FROM experiences ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);


$contact_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        $contact_msg = "success";
    } else {
        $contact_msg = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Gerald Belena - Portfolio</title>
</head>
<body>


<nav>
    <a href="index.php" class="logo">Gerald Belena</a>
    <ul>
        <li><a href="#about">About</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#skills">Skills</a></li>
        <li><a href="#experience">Experience</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="admin/login.php">Admin</a></li>
    </ul>
</nav>

<div class="hero" id="about">
    <img src="assets/images/gerald1.jpg" alt="Gerald Belena">
    <h1>Gerald Belena</h1>
    <p>Hello, I'm <strong>Gerald</strong>, a curious and driven student who's passionate about learning <strong>programming</strong>, <strong>tech</strong>, and <strong>practical problem solving</strong> while balancing school, personal projects, side hustles, and self improvement.</p>
</div>


<div class="section" id="projects">
    <h2>My Projects</h2>
    <?php if (count($projects) > 0): ?>
        <div class="card-grid">
            <?php foreach ($projects as $project): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                <p><?php echo htmlspecialchars($project['description']); ?></p>
                <?php if (!empty($project['tech_used'])): ?>
                    <small>Tech: <?php echo htmlspecialchars($project['tech_used']); ?></small><br>
                <?php endif; ?>
                <?php if (!empty($project['project_url'])): ?>
                    <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank">View Project &rarr;</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No projects added yet.</p>
    <?php endif; ?>
</div>

<div class="section" id="skills">
    <h2>My Skills</h2>
    <?php if (count($skills) > 0): ?>
        <ul class="skills-list">
            <?php foreach ($skills as $skill): ?>
                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No skills added yet.</p>
    <?php endif; ?>
</div>


<div class="section" id="experience">
    <h2>My Experience</h2>
    <?php if (count($experiences) > 0): ?>
        <div class="card-grid">
            <?php foreach ($experiences as $exp): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($exp['title']); ?></h3>
                <p><?php echo htmlspecialchars($exp['description']); ?></p>
                <?php if (!empty($exp['year'])): ?>
                    <small><?php echo htmlspecialchars($exp['year']); ?></small>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No experience added yet.</p>
    <?php endif; ?>
</div>


<div class="section">
    <h2>My Hobbies</h2>
    <ul class="hobbies-list">
        <li>Going to the Gym</li>
        <li>Running / Jogging</li>
        <li>Hiking</li>
        <li>Basketball</li>
        <li>Watching Movies</li>
    </ul>
</div>


<div class="section">
    <h2>My Favorite Quote</h2>
    <div class="quote-box">
        "It's not about the destination, It's the journey." &mdash; Ralph Waldo Emerson
    </div>
</div>


<div class="section" id="contact">
    <h2>Contact Me</h2>

    <?php if ($contact_msg === "success"): ?>
        <div class="alert alert-success">Message sent successfully! I'll get back to you soon.</div>
    <?php elseif ($contact_msg === "error"): ?>
        <div class="alert alert-error">Please fill in all fields before submitting.</div>
    <?php endif; ?>

    <div class="contact-form">
        <form method="POST" action="#contact">
            <label for="name">Your Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>

            <label for="email">Your Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="message">Your Message</label>
            <textarea name="message" id="message" placeholder="Write your message here..." required></textarea>

            <input type="submit" name="contact_submit" value="Send Message">
        </form>
    </div>
</div>


<footer>
    <p>&copy; <?php echo date('Y'); ?> Gerald Belena &nbsp;|&nbsp; 
    <a href="https://github.com/SP3CTR021" target="_blank">GitHub Profile</a></p>
</footer>

</body>
</html>