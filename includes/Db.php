<?php


$host = "localhost";
$dbname = "belena_portfolio";
$username = "root";
$password = "";

try {

    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        tech_used VARCHAR(200),
        project_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS skills (
        id INT AUTO_INCREMENT PRIMARY KEY,
        skill_name VARCHAR(100) NOT NULL,
        category VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS experiences (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        year VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");


    $check = $pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    if ($check == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO admin_users (username, password) VALUES ('admin', '$hash')");
    }

  
    if ($pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO projects (title, description, tech_used, project_url) VALUES
            ('Biography Website', 'A simple personal biography website created using HTML and CSS only.', 'HTML, CSS', 'https://github.com/SP3CTR021'),
            ('Portfolio Management System', 'A dynamic web-based portfolio system built with PHP and MySQL.', 'PHP, MySQL, HTML, CSS', 'https://github.com/SP3CTR021')
        ");
    }

    if ($pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
            ('HTML', 'Web Development'),
            ('CSS', 'Web Development'),
            ('PHP', 'Web Development'),
            ('MySQL', 'Database'),
            ('Video Editing', 'Creative'),
            ('JavaScript', 'Web Development')
        ");
    }

    if ($pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO experiences (title, description, year) VALUES
            ('IT Student', 'Currently studying IT at school, learning programming, databases, and web development.', '2024 - Present'),
            ('Freelance Video Editor', 'Working as a freelance video editor for clients, handling video production and editing tasks.', '2023 - Present')
        ");
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>