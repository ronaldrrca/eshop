<?php
session_start();

if (!isset($_SESSION["cliente"])) {
    $_SESSION["redirect_to"] = basename($_SERVER["PHP_SELF"]); // Guarda la página actual para redireccionar nuevamente al origen.
    // Si el cliente no está autenticado, redirigirlo a la página de login
    header("Location: login-clientes.php");
    exit();
}
?>
