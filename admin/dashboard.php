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
        <div class="admin-layout">
            <aside class="admin-sidebar">
                <h3><a>Panou</a></h3>
                <a href="/sg/index.php">Pagina principală</a>
                <a href="adauga_anunt.php">Adaugă Anunț</a>
                <a href="profesori.php">Gestionează Profesori</a>
                <?php if (is_admin()): ?>
                    <a href="users.php">Gestionează Utilizatori</a>
                <?php endif; ?>
                <a href="logout.php">Deconectare</a>
            </aside>

            <div class="admin-content">

        <div class="stats-row">
            <div class="stat-card">
                <h3><?php
                $countA = mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM anunturi'))[0] ?? 0;
                echo (int)$countA;
                ?></h3>
                <p>Anunțuri</p>
            </div>
            <div class="stat-card">
                <h3><?php
                $countP = mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM profesori'))[0] ?? 0;
                echo (int)$countP;
                ?></h3>
                <p>Profesori</p>
            </div>
            <div class="stat-card">
                <h3><?php
                $countU = mysqli_fetch_row(mysqli_query($conn, 'SELECT COUNT(*) FROM utilizatori'))[0] ?? 0;
                echo (int)$countU;
                ?></h3>
                <p>Utilizatori</p>
            </div>
        </div>

        <h2>Anunțuri existente</h2>
        <?php
        $sql = "SELECT a.*, u.username FROM anunturi a LEFT JOIN utilizatori u ON a.id_utilizator = u.id_utilizator ORDER BY data_publicare DESC";
        $res = mysqli_query($conn, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            echo '<ul class="announcements">';
            while ($r = mysqli_fetch_assoc($res)) {
                $id = (int)$r['id_anunt'];
                $title = htmlspecialchars($r['titlu'], ENT_QUOTES,'UTF-8');
                $date = htmlspecialchars($r['data_publicare'],ENT_QUOTES,'UTF-8');
                $author = htmlspecialchars($r['username'] ?? 'anonim',ENT_QUOTES,'UTF-8');
                echo '<li class="announcement-item">';
                echo '<div><strong>'.$title.'</strong><div class="announcement-meta">'.$date.' - de: '.$author.'</div></div>';
                echo '<div class="announcement-actions">';
                echo '<a class="btn btn-edit" href="modifica_anunt.php?id='.$id.'">Modifică</a>';
                if (is_admin()) echo ' <a class="btn btn-delete" href="sterge_anunt.php?id='.$id.'" onclick="return confirm(\'Ștergi anunț?\')">Șterge</a>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nu există anunțuri.</p>';
        }
        ?>

            </div> <!-- .admin-content -->
        </div> <!-- .admin-layout -->
    </main>
</body>
</html>
