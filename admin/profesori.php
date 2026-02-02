<?php
session_start();
include '../config.php';
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
// allow admin and editor to manage professors
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','editor'])) die('Acces interzis');

$errors = [];
$success = '';

// create profesor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $nume = trim($_POST['nume'] ?? '');
    $disciplina = trim($_POST['disciplina'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($nume === '' || $disciplina === '') $errors[] = 'Completați nume și disciplină.';
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO profesori (nume, disciplina, email) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sss', $nume, $disciplina, $email);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Profesor adăugat.';
        } else { $errors[] = 'Eroare la adăugare.'; }
        mysqli_stmt_close($stmt);
    }
}

// delete
if (isset($_GET['delete'])) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die('Acces interzis');
    $id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, 'DELETE FROM profesori WHERE id_profesor = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: profesori.php'); exit;
}

// list
$profesori = [];
$res = mysqli_query($conn, 'SELECT id_profesor, nume, disciplina, email FROM profesori ORDER BY nume ASC');
if ($res) while($p = mysqli_fetch_assoc($res)) $profesori[] = $p;

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Gestionează Profesori</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main class="container">
        <h1>Profesori</h1>
        <?php if ($success) echo '<p style="color:green">'.htmlspecialchars($success,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <?php if ($errors) echo '<p style="color:red">'.htmlspecialchars(implode("; ",$errors),ENT_QUOTES,'UTF-8').'</p>'; ?>

        <h2>Adaugă profesor</h2>
        <form method="post">
            <label>Nume:<br><input name="nume" required></label><br><br>
            <label>Disciplina:<br><input name="disciplina" required></label><br><br>
            <label>Email:<br><input name="email" type="email"></label><br><br>
            <button name="create" type="submit">Adaugă</button>
        </form>

        <h2>Lista profesorilor</h2>
        <ul>
        <?php foreach($profesori as $p): ?>
            <li><?php echo htmlspecialchars($p['nume'],ENT_QUOTES,'UTF-8'); ?> - <?php echo htmlspecialchars($p['disciplina'],ENT_QUOTES,'UTF-8'); ?> - <?php echo htmlspecialchars($p['email'],ENT_QUOTES,'UTF-8'); ?> - <?php if ($_SESSION['role'] === 'admin') { ?> <a href="?delete=<?php echo $p['id_profesor']; ?>" onclick="return confirm('Ștergi profesor?')">Șterge</a> <?php } ?></li>
        <?php endforeach; ?>
        </ul>

        <p><a href="dashboard.php">Înapoi</a></p>
    </main>
</body>
</html>
