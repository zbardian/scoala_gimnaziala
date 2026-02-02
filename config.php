<?php
// Config conexiune DB (folosește variabile de mediu dacă sunt setate)
$servername = getenv('DB_HOST') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASS') ?: '';
$dbname     = getenv('DB_NAME') ?: 'scoala_gimnaziala';

// Mediu: 'production' sau 'development'
$env = getenv('APP_ENV') ?: 'development';

// Arată erori doar în development
if ($env === 'production') {
    ini_set('display_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// Activează raportarea excepțiilor pentru mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Creare conexiune
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Set charset
    if ($conn) {
        mysqli_set_charset($conn, 'utf8mb4');
    }
} catch (mysqli_sql_exception $e) {
    // Log pentru dezvoltator (fișier de log pe server)
    error_log('DB connection error: ' . $e->getMessage());
    // Mesaj generic pentru utilizator (proiect școlar)
    die('Conexiunea la baza de date a eșuat.');
}
?>