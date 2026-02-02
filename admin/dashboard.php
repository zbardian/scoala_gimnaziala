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
                echo '<li><strong>'.htmlspecialchars($r['titlu'], ENT_QUOTES,'UTF-8')."</strong> (".htmlspecialchars($r['data_publicare'],ENT_QUOTES,'UTF-8').") - de: ".htmlspecialchars($r['username'] ?? 'anonim',ENT_QUOTES,'UTF-8').'</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nu există anunțuri.</p>';
        }
        ?>
    </main>
</body>
</html>
