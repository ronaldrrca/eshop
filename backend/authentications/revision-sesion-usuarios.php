<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    // Si el usuario está autenticado, redirigirlo a la página de admin-panel.php
    header("Location: login-usuarios.php");
    exit();
}
?>
