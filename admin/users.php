<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
include '../config.php';

// only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Acces interzis');
}

// create or update user
$errors = [];
$success = '';
$editing = false;
$edit_user = null;

// Load user for editing
if (isset($_GET['edit'])) {
    $editing = true;
    $id_edit = intval($_GET['edit']);
    $stmt = mysqli_prepare($conn, 'SELECT id_utilizator, username, rol FROM utilizatori WHERE id_utilizator = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $id_edit);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $edit_user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    if (!$edit_user) {
        $errors[] = 'Utilizator inexistent.';
        $editing = false;
    }
}

// Update existing user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id_utilizator'] ?? 0);
    $username_up = trim($_POST['username'] ?? '');
    $role_up = $_POST['role'] ?? 'editor';
    $newpass = $_POST['password'] ?? '';

    if ($username_up === '') $errors[] = 'Completați username.';

    // prevent demoting last admin / self-demotion
    if ($id === intval($_SESSION['user_id']) && $role_up !== 'admin') {
        $errors[] = 'Nu poți elimina rolul de admin pentru propriul cont.';
    }

    // check username uniqueness
    $stmt = mysqli_prepare($conn, 'SELECT id_utilizator FROM utilizatori WHERE username = ? AND id_utilizator != ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'si', $username_up, $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_fetch_assoc($res)) $errors[] = 'Username deja folosit.';
    mysqli_stmt_close($stmt);

    if (empty($errors)) {
        if ($newpass !== '') {
            $hash = password_hash($newpass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, 'UPDATE utilizatori SET username = ?, parola = ?, rol = ? WHERE id_utilizator = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'sssi', $username_up, $hash, $role_up, $id);
        } else {
            $stmt = mysqli_prepare($conn, 'UPDATE utilizatori SET username = ?, rol = ? WHERE id_utilizator = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'ssi', $username_up, $role_up, $id);
        }
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Utilizator actualizat cu succes.';
            // if we updated current session username, update session
            if ($id === intval($_SESSION['user_id'])) {
                $_SESSION['username'] = $username_up;
                $_SESSION['role'] = $role_up;
            }
        } else {
            $errors[] = 'Eroare la actualizare utilizator.';
        }
        mysqli_stmt_close($stmt);
    }
}

// create user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    if ($username === '' || $password === '') $errors[] = 'Completați username și parolă.';

    // check unique username
    $stmt = mysqli_prepare($conn, 'SELECT id_utilizator FROM utilizatori WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_fetch_assoc($res)) $errors[] = 'Username deja folosit.';
    mysqli_stmt_close($stmt);

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
        <nav class="admin-nav">
            <a href="dashboard.php">Înapoi la Panou</a>
            <a href="/sg/index.php">Pagina principală</a>
        </nav>
        <h1>Utilizatori</h1>
        <?php if ($success) echo '<p class="success">'.htmlspecialchars($success,ENT_QUOTES,'UTF-8').'</p>'; ?>
        <?php if ($errors) echo '<p class="errors">'.htmlspecialchars(implode("; ",$errors),ENT_QUOTES,'UTF-8').'</p>'; ?>

        <div class="stat-card">
        <?php if ($editing && $edit_user): ?>
            <h2>Modifică utilizator</h2>
            <form method="post">
                <input type="hidden" name="id_utilizator" value="<?php echo (int)$edit_user['id_utilizator']; ?>">
                <label>Username:<br><input name="username" required value="<?php echo htmlspecialchars($edit_user['username'],ENT_QUOTES,'UTF-8'); ?>"></label><br><br>
                <label>Parolă nouă (lasă necompletat pentru a păstra):<br><input type="password" name="password"></label><br><br>
                <label>Rol:<br>
                    <select name="role">
                        <option value="editor" <?php echo $edit_user['rol']==='editor' ? 'selected' : ''; ?>>Editor</option>
                        <option value="admin" <?php echo $edit_user['rol']==='admin' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </label><br><br>
                <button class="btn btn-edit" name="update" type="submit">Salvează</button>
                <a class="btn" href="users.php">Anulează</a>
            </form>
        <?php else: ?>
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
                <button class="btn btn-edit" name="create" type="submit">Creează</button>
            </form>
        <?php endif; ?>
        </div>

        <h2>Lista utilizatorilor</h2>
        <ul class="admin-list">
        <?php foreach($users as $u): ?>
            <li>
                <div>
                    <strong><?php echo htmlspecialchars($u['username'],ENT_QUOTES,'UTF-8'); ?></strong><br>
                    <span class="kv"><?php echo htmlspecialchars($u['rol'],ENT_QUOTES,'UTF-8'); ?></span>
                </div>
                <div class="item-actions">
                    <a class="btn btn-edit" href="?edit=<?php echo $u['id_utilizator']; ?>">Modifică</a>
                    <a class="btn btn-delete" href="delete_user.php?id=<?php echo $u['id_utilizator']; ?>" onclick="return confirm('Ștergi utilizator?')">Șterge</a>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>

        <div class="form-actions">
            <a class="btn btn-secondary" href="dashboard.php">Înapoi</a>
        </div>
    </main>
</body>
</html>
