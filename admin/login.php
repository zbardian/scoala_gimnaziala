<?php
session_start();
include '../config.php';

if (isset($_SESSION['username'])) {
    header('Location: dashboard.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $parola = $_POST['parola'] ?? '';

    if ($username === '' || $parola === '') {
        $error = 'Completați username și parolă.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id_utilizator, username, parola, rol FROM utilizatori WHERE username = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($parola, $user['parola'])) {
            session_regenerate_id(true);
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['rol'];
            $_SESSION['user_id'] = $user['id_utilizator'];
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Date de autentificare incorecte.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <main style="max-width:600px;margin:40px auto;">
        <h2>Autentificare Administrare</h2>
        <?php if ($error): ?><p style="color:red"><?php echo htmlspecialchars($error, ENT_QUOTES,'UTF-8'); ?></p><?php endif; ?>
        <form method="post">
            <label>Username:<br><input type="text" name="username" required></label><br><br>
            <label>Parolă:<br><input type="password" name="parola" required></label><br><br>
            <button type="submit">Autentificare</button>
        </form>
    </main>
</body>
</html>
