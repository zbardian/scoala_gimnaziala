<?php
include 'config.php';
$pageTitle = 'Acasă - Școala Gimnazială Locală';
include 'includes/header.php';
?>

    <h2>Bine ați venit la Școala Gimnazială Locală!</h2>
    <p>Aici găsiți informații despre școală, cadre didactice și ultimele noutăți.</p>

    <!-- exemplu sectiune noutati scurte -->
    <section>
        <h3>Ultimele anunțuri</h3>
        <?php
        $sql = "SELECT id_anunt, titlu, data_publicare FROM anunturi ORDER BY data_publicare DESC LIMIT 3";
        $res = mysqli_query($conn, $sql);
        if($res && mysqli_num_rows($res) > 0) {
            echo '<ul>';
            while($row = mysqli_fetch_assoc($res)) {
                echo '<li><a href="anunturi.php#anunt-'.intval($row['id_anunt']).'">'.htmlspecialchars($row['titlu'], ENT_QUOTES, 'UTF-8').' ('.htmlspecialchars($row['data_publicare'], ENT_QUOTES, 'UTF-8').')</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nu există anunțuri momentan.</p>';
        }
        ?>
    </section>

<?php include 'includes/footer.php'; ?>