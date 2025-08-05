<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Redirige al login o muestra error si no es admin
    header("Location: login.php");
    exit();
}
?>
