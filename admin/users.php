<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
include '../config.php';

// only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Acces interzis');
}

// create user
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    if ($username === '' || $password === '') $errors[] = 'Completați username și parolă.';
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO utilizatori (username, parola, rol) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sss', $username, $hash, $role);
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Utilizator creat cu succes.';
        } else { $errors[] = 'Eroare la creare utilizator.'; }
        mysqli_stmt_close($stmt);
    }
}

// list users
$users = [];
$res = mysqli_query($conn, 'SELECT id_utilizator, username, rol FROM utilizatori ORDER BY username ASC');
if ($res) while($u = mysqli_fetch_assoc($res)) $users[] = $u;
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Gestionează Utilizatori</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main class="container">
        <h1>Utilizatori</h1>
        <?php if ($success) echo '<p style="color:green">'.htmlspecialchars($success,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <?php if ($errors) echo '<p style="color:red">'.htmlspecialchars(implode("; ",$errors),ENT_QUOTES,'UTF-8').'</p>'; ?>

        <h2>Creare utilizator</h2>
        <form method="post">
            <label>Username:<br><input name="username" required></label><br><br>
            <label>Parolă:<br><input type="password" name="password" required></label><br><br>
            <label>Rol:<br>
                <select name="role">
                    <option value="editor">Editor (poate crea/edita anunțuri)</option>
                    <option value="admin">Administrator (drepturi complete)</option>
                </select>
            </label><br><br>
            <button name="create" type="submit">Creează</button>
        </form>

        <h2>Lista utilizatorilor</h2>
        <ul>
        <?php foreach($users as $u): ?>
            <li><?php echo htmlspecialchars($u['username'],ENT_QUOTES,'UTF-8'); ?> - <?php echo htmlspecialchars($u['rol'],ENT_QUOTES,'UTF-8'); ?> - <a href="delete_user.php?id=<?php echo $u['id_utilizator']; ?>" onclick="return confirm('Ștergi utilizator?')">Șterge</a></li>
        <?php endforeach; ?>
        </ul>

        <p><a href="dashboard.php">Înapoi</a></p>
    </main>
</body>
</html>
