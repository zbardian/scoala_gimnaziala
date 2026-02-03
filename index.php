<?php
include 'config.php';
$pageTitle = 'Acasă - Școala Gimnazială Locală';
include 'includes/header.php';
?>

    <h2>Bine ați venit la Școala Gimnazială Locală!</h2>
    <p>Aici găsiți informații despre școală, cadre didactice și ultimele noutăți.</p>

    <div style="margin:18px 0;">
        <img src="/assets/school-landscape.svg" alt="Școală" style="width:100%;max-width:1100px;border-radius:8px;box-shadow:0 8px 20px rgba(10,30,80,0.06)">
    </div>

    <!-- Sectiune: Despre școală -->
    <section id="despre-scoala" style="margin:24px 0; display:flex; gap:24px; align-items:flex-start; flex-wrap:wrap;">
        <div style="flex:1; min-width:260px;">
            <h3>Despre școala noastră</h3>
            <p>Școala Gimnazială Locală oferă educație de calitate pentru elevi din clasele I–VIII, cu accent pe dezvoltare personală, incluziune și rezultate școlare. Oferim programe curriculare și extra-curriculare susținute de o echipă de profesori dedicați.</p>
            <p><a href="contact.php">Contactează-ne</a> pentru informații despre înscrieri, tururi și activități.</p>
        </div>
        <div style="width:320px; background:#f7fbff; border:1px solid #e1efff; padding:14px; border-radius:8px;">
            <h4 style="margin-top:0;">Informații utile</h4>
            <p style="margin:6px 0;"><strong>Adresa:</strong> Str. Exemplu 12, Localitate</p>
            <p style="margin:6px 0;"><strong>Telefon:</strong> <a href="tel:+40123456789">+40 123 456 789</a></p>
            <p style="margin:6px 0;"><strong>Email:</strong> <a href="mailto:secretariat@scoala.ro">secretariat@scoala.ro</a></p>
            <p style="margin:6px 0;"><strong>Program:</strong> Luni–Vineri, 08:00–16:00</p>
        </div>
    </section>

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