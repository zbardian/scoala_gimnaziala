<?php
include 'config.php';
$pageTitle = 'Contact - Școala Gimnazială Locală';
include 'includes/header.php';
$errors = [];
$success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mesaj = trim($_POST['mesaj'] ?? '');

    if($nume === '') $errors[] = 'Numele este obligatoriu.';
    if($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalid.';
    if($mesaj === '') $errors[] = 'Mesajul este obligatoriu.';

    if(empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_mesaje (nume, email, mesaj, data_trimitere) VALUES (?, ?, ?, ?)");
        $data = date('Y-m-d H:i:s');
        mysqli_stmt_bind_param($stmt, 'ssss', $nume, $email, $mesaj, $data);
        if(mysqli_stmt_execute($stmt)) {
            $success = 'Mesajul a fost trimis. Mulțumim!';
        } else {
            $errors[] = 'Eroare la trimiterea mesajului.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

    <h2>Trimite-ne un mesaj</h2>

    <?php if(!empty($errors)): ?>
        <div class="errors">
            <ul>
            <?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e, ENT_QUOTES, 'UTF-8').'</li>'; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <p class="success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nume:<br><input type="text" name="nume" required value="<?php echo htmlspecialchars($_POST['nume'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label><br><br>
        <label>Email:<br><input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></label><br><br>
        <label>Mesaj:<br><textarea name="mesaj" rows="6" required><?php echo htmlspecialchars($_POST['mesaj'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></label><br><br>
        <button type="submit">Trimite</button>
    </form>

    <h3>Informații contact</h3>
    <p>Adresa: Str. Exemplu nr. 1, Localitate</p>
    <p>Telefon: 0123 456 789</p>

<?php include 'includes/footer.php'; ?>
