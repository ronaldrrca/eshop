<?php
session_start();

if (!isset($_SESSION["cliente"])) {
    $_SESSION["redirect_to"] = basename($_SERVER["PHP_SELF"]); // Guarda la pÃ¡gina actual para redireccionar nuevamente al origen.
    // Si el usuario no estÃ¡ autenticado, redirigirlo a la pÃ¡gina de login
    header("Location: login-cliente.php");
    exit();
}
?>
