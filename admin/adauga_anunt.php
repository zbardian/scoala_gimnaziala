<?php
session_start();
include '../config.php';

if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
// allow admin and editor to add announcements
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','editor'])) {
    die('Acces interzis');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlu = trim($_POST['titlu'] ?? '');
    $continut = trim($_POST['continut'] ?? '');
    $data = $_POST['data_publicare'] ?? date('Y-m-d');

    if ($titlu === '' || $continut === '') {
        $error = 'Completați titlu și conținut.';
    } else {
        $stmt = mysqli_prepare($conn, 'INSERT INTO anunturi (titlu, continut, data_publicare, id_utilizator) VALUES (?, ?, ?, ?)');
        $userId = intval($_SESSION['user_id']);
        mysqli_stmt_bind_param($stmt, 'sssi', $titlu, $continut, $data, $userId);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Eroare la salvare anunț.';
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Adaugă Anunț</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main class="container">
        <nav class="admin-nav">
            <a href="dashboard.php">Înapoi la Panou</a>
            <a href="/sg/index.php">Pagina principală</a>
        </nav>
        <h1>Adaugă Anunț</h1>
        <?php if ($error) echo '<p class="errors">'.htmlspecialchars($error,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <div class="stat-card">
            <form method="post">
                <label>Titlu:<br><input name="titlu" required></label><br><br>
                <label>Conținut:<br><textarea name="continut" rows="8" required></textarea></label><br><br>
                <label>Data publicare:<br><input type="date" name="data_publicare" value="<?php echo date('Y-m-d'); ?>"></label><br><br>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Salvează</button>
                    <a class="btn btn-secondary" href="dashboard.php">Anulează</a>
                </div>
             </form>
         </div>
     </main>
 </body>
 </html>
