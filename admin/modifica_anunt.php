<?php
session_start();
include '../config.php';
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
// allow admin and editor to edit
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','editor'])) die('Acces interzis');

if (!isset($_GET['id'])) die('Missing id');
$id = intval($_GET['id']);

// fetch existing
$stmt = mysqli_prepare($conn, 'SELECT id_anunt, titlu, continut, data_publicare FROM anunturi WHERE id_anunt = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$anunt = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
if (!$anunt) die('Anunț inexistent');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlu = trim($_POST['titlu'] ?? '');
    $continut = trim($_POST['continut'] ?? '');
    $data = $_POST['data_publicare'] ?? $anunt['data_publicare'];
    if ($titlu === '' || $continut === '') {
        $error = 'Completați titlu și conținut.';
    } else {
        $stmt = mysqli_prepare($conn, 'UPDATE anunturi SET titlu = ?, continut = ?, data_publicare = ? WHERE id_anunt = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'sssi', $titlu, $continut, $data, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Eroare la actualizare anunț.';
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Modifică Anunț</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main class="container">
        <h1>Modifică Anunț</h1>
        <?php if ($error) echo '<p style="color:red">'.htmlspecialchars($error,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <form method="post">
            <label>Titlu:<br><input name="titlu" value="<?php echo htmlspecialchars($anunt['titlu'],ENT_QUOTES,'UTF-8'); ?>" required></label><br><br>
            <label>Conținut:<br><textarea name="continut" rows="8" required><?php echo htmlspecialchars($anunt['continut'],ENT_QUOTES,'UTF-8'); ?></textarea></label><br><br>
            <label>Data publicare:<br><input type="date" name="data_publicare" value="<?php echo htmlspecialchars($anunt['data_publicare'],ENT_QUOTES,'UTF-8'); ?>"></label><br><br>
            <button type="submit">Salvează</button>
        </form>
        <p><a href="dashboard.php">Înapoi</a></p>
    </main>
</body>
</html>
