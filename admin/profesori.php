<?php
session_start();
include '../config.php';
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
// allow admin and editor to manage professors
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','editor'])) die('Acces interzis');

$errors = [];
$success = '';

// edit flow
$editing = false;
$edit_prof = null;
if (isset($_GET['edit'])) {
    $editing = true;
    $id_edit = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, 'SELECT id_profesor, nume, disciplina, email FROM profesori WHERE id_profesor = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id_edit);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $edit_prof = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    if (!$edit_prof) {
        $errors[] = 'Profesor inexistent.';
        $editing = false;
    }
}

// update profesor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id_profesor'] ?? 0);
    $nume = trim($_POST['nume'] ?? '');
    $disciplina = trim($_POST['disciplina'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($nume === '' || $disciplina === '') $errors[] = 'Completați nume și disciplină.';
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, 'UPDATE profesori SET nume = ?, disciplina = ?, email = ? WHERE id_profesor = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'sssi', $nume, $disciplina, $email, $id);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Profesor actualizat.';
            // refresh list and reset edit mode
            $editing = false;
            $edit_prof = null;
        } else { $errors[] = 'Eroare la actualizare.'; }
        mysqli_stmt_close($stmt);
    }
}

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
        <nav class="admin-nav">
            <a href="dashboard.php">Înapoi la Panou</a>
            <a href="/sg/index.php">Pagina principală</a>
        </nav>
        <h1>Profesori</h1>
        <?php if ($success) echo '<p class="success">'.htmlspecialchars($success,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <?php if ($errors) echo '<p class="errors">'.htmlspecialchars(implode("; ",$errors),ENT_QUOTES,'UTF-8').'</p>'; ?>

        <div class="stat-card">
        <?php if ($editing && $edit_prof): ?>
            <h2>Modifică profesor</h2>
            <form method="post">
                <input type="hidden" name="id_profesor" value="<?php echo (int)$edit_prof['id_profesor']; ?>">
                <label>Nume:<br><input name="nume" required value="<?php echo htmlspecialchars($edit_prof['nume'],ENT_QUOTES,'UTF-8'); ?>"></label><br><br>
                <label>Disciplina:<br><input name="disciplina" required value="<?php echo htmlspecialchars($edit_prof['disciplina'],ENT_QUOTES,'UTF-8'); ?>"></label><br><br>
                <label>Email:<br><input name="email" type="email" value="<?php echo htmlspecialchars($edit_prof['email'],ENT_QUOTES,'UTF-8'); ?>"></label><br><br>
                <button class="btn btn-edit" name="update" type="submit">Salvează modificările</button>
                <a class="btn" href="profesori.php">Anulează</a>
            </form>
        <?php else: ?>
            <h2>Adaugă profesor</h2>
            <form method="post">
                <label>Nume:<br><input name="nume" required></label><br><br>
                <label>Disciplina:<br><input name="disciplina" required></label><br><br>
                <label>Email:<br><input name="email" type="email"></label><br><br>
                <button class="btn btn-edit" name="create" type="submit">Adaugă</button>
            </form>
        <?php endif; ?>
        </div>

        <h2>Lista profesorilor</h2>
        <ul>
        <?php foreach($profesori as $p): ?>
            <li>
                <?php echo htmlspecialchars($p['nume'],ENT_QUOTES,'UTF-8'); ?> - <?php echo htmlspecialchars($p['disciplina'],ENT_QUOTES,'UTF-8'); ?> - <?php echo htmlspecialchars($p['email'],ENT_QUOTES,'UTF-8'); ?>
                - <a class="btn btn-edit" href="?edit=<?php echo $p['id_profesor']; ?>">Modifică</a>
                <?php if ($_SESSION['role'] === 'admin') { ?> | <a class="btn btn-delete" href="?delete=<?php echo $p['id_profesor']; ?>" onclick="return confirm('Ștergi profesor?')">Șterge</a> <?php } ?>
            </li>
        <?php endforeach; ?>
        </ul>

        <p><a href="dashboard.php">Înapoi</a></p>
    </main>
</body>
</html>
