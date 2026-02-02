<?php
include 'config.php';
$pageTitle = 'Anunțuri - Școala Gimnazială Locală';
include 'includes/header.php';
?>

<?php
$sql = "SELECT * FROM anunturi ORDER BY data_publicare DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='anunt' id='anunt-".intval($row['id_anunt'])."'>";
        echo "<h3>".htmlspecialchars($row['titlu'], ENT_QUOTES, 'UTF-8')."</h3>";
        echo "<p>".nl2br(htmlspecialchars($row['continut'], ENT_QUOTES, 'UTF-8'))."</p>";
        echo "<small>Publicat la: ".htmlspecialchars($row['data_publicare'], ENT_QUOTES, 'UTF-8')."</small>";
        echo "</div><hr>";
    }
} else {
    echo "<p>Nu există anunțuri momentan.</p>";
}
?>

<?php include 'includes/footer.php'; ?>