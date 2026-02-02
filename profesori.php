<?php
include 'config.php';
$pageTitle = 'Profesori - Școala Gimnazială Locală';
include 'includes/header.php';
?>

<?php
$sql = "SELECT * FROM profesori ORDER BY nume ASC";
$result = mysqli_query($conn, $sql);

if($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='profesor'>";
        echo "<h3>".htmlspecialchars($row['nume'], ENT_QUOTES, 'UTF-8')."</h3>";
        echo "<p>Disciplina: ".htmlspecialchars($row['disciplina'], ENT_QUOTES, 'UTF-8')."</p>";
        if(!empty($row['email'])) {
            echo "<p>Email: <a href='mailto:".htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8')."</a></p>";
        }
        echo "</div><hr>";
    }
} else {
    echo "<p>Nu există profesori în baza de date.</p>";
}
?>

<?php include 'includes/footer.php'; ?>