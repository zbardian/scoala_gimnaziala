<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit; }
include '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') die('Acces interzis');
if (!isset($_GET['id'])) die('Missing id');
$id = intval($_GET['id']);
$stmt = mysqli_prepare($conn, 'DELETE FROM utilizatori WHERE id_utilizator = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
header('Location: users.php');
exit;
?>
