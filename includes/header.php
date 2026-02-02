<?php
if(!defined('PROJECT_INIT')) {
    define('PROJECT_INIT', true);
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Școala Gimnazială Locală', ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1>Școala Gimnazială Locală</h1>
            <nav class="main-nav">
                <a href="/index.php">Acasă</a>
                <a href="/anunturi.php">Anunțuri</a>
                <a href="/profesori.php">Profesori</a>
                <a href="/contact.php">Contact</a>
            </nav>
        </div>
    </header>
    <main class="container">
