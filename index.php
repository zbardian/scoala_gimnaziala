<?php
include 'config.php';
$pageTitle = 'Acasă - Școala Gimnazială Locală';
include 'includes/header.php';
?>

    <section class="hero">
        <div class="hero-content">
            <h2>Bine ați venit la Școala Gimnazială Locală!</h2>
            <p class="lead">Aici găsiți informații despre școală, cadre didactice și ultimele noutăți.</p>
            <div class="cta-row">
                <a class="cta-button" href="anunturi.php">Vezi toate anunțurile</a>
                <a class="cta-button secondary" href="contact.php">Contact</a>
            </div>
        </div>
        <img src="/assets/school-illustration.svg" alt="Școală" style="max-width:220px;opacity:0.95;">
    </section>

    <!-- exemplu sectiune noutati scurte -->
    <section>
        <h3>Ultimele anunțuri</h3>
        <div class="section-cards">
        <?php
        $sql = "SELECT id_anunt, titlu, data_publicare, SUBSTRING(continut,1,160) AS excerpt FROM anunturi ORDER BY data_publicare DESC LIMIT 3";
        $res = mysqli_query($conn, $sql);
        if($res && mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) {
                echo '<div class="section-card"><h3>'.htmlspecialchars($row['titlu'], ENT_QUOTES, 'UTF-8').'</h3><p>'.htmlspecialchars($row['excerpt'], ENT_QUOTES, 'UTF-8').'...</p><p style="margin-top:8px;"><a href="anunturi.php#anunt-'.intval($row['id_anunt']).'">Citește mai mult</a></p></div>';
            }
        } else {
            echo '<p>Nu există anunțuri momentan.</p>';
        }
        ?>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>