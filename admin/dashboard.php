<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
include '../config.php';

function is_admin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main class="container">
        <h1>Panou Administrare</h1>
        <p>Bine ai venit, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
        <nav>
            <a href="adauga_anunt.php">Adaugă Anunț</a> |
            <a href="modifica_anunt.php">Modifică Anunț</a> |
            <a href="sterge_anunt.php">Șterge Anunț</a> |
            <?php if (is_admin()): ?>
                <a href="users.php">Gestionează Utilizatori</a> |
            <?php endif; ?>
            <a href="logout.php">Deconectare</a>
        </nav>

        <h2>Anunțuri existente</h2>
        <?php
        $sql = "SELECT a.*, u.username FROM anunturi a LEFT JOIN utilizatori u ON a.id_utilizator = u.id_utilizator ORDER BY data_publicare DESC";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            echo '<ul>';
            while ($r = mysqli_fetch_assoc($res)) {
                $id = (int)$r['id_anunt'];
                $title = htmlspecialchars($r['titlu'], ENT_QUOTES,'UTF-8');
                $date = htmlspecialchars($r['data_publicare'],ENT_QUOTES,'UTF-8');
                $author = htmlspecialchars($r['username'] ?? 'anonim',ENT_QUOTES,'UTF-8');
                echo '<li><strong>'.$title.'</strong> ('.$date.') - de: '.$author;
                // edit link for admin and editor
                echo ' - <a href="modifica_anunt.php?id='.$id.'">Modifică</a>';
                // delete link only for admin
                if (is_admin()) {
                    echo ' | <a href="sterge_anunt.php?id='.$id.'" onclick="return confirm(\'Ștergi anunț?\')">Șterge</a>';
                }
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nu există anunțuri.</p>';
        }
        ?>
    </main>
</body>
</html>
