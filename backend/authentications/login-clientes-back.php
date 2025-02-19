<?php
session_start();
require_once '../conexion.php';
header("Content-Type: application/json");

$respuesta = "";

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(["Error de conexión a la base de datos"]);
    exit;
}


// Recibir datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validar que no estén vacíos
    if (empty($email) || empty($password)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-login-cliente.php");
        exit();
    }

    // Preparar la consulta
    $sql = "CALL loginCliente(?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente = $resultado->fetch_assoc();
    
    if ($cliente && password_verify($password, $cliente["password_cliente"])) {
        // Iniciar sesión con los datos del usuario
        $_SESSION["cliente"] = $cliente["nombre_cliente"];
        $_SESSION['id_cliente'] = $cliente['id_cliente'];

        $respuesta = [
            "mensaje" => "Inicio de sesión exitoso.",
            "status" => "ok"
        ];
    
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        $respuesta = "";
        
        // Obtener la URL de redirección y luego eliminar la sesión
        if (isset($_SESSION['redirect_to'])) {
            $redirect_to = $_SESSION['redirect_to'];
            unset($_SESSION['redirect_to']);
            header("Location: ../../" . $redirect_to); // Redirigir al panel del cliente
            exit();
        } else {
            header("Location: ../../index.php"); // Redirigir al panel del cliente
            exit();
        }

    } else {
        $respuesta = [
            "mensaje" => "Datos inválidos, intente nuevamente.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        $respuesta = "";
        header("Location: ../../login-cliente.php"); // Redirigir al panel del cliente
        exit();
    } 

// Cerrar conexiÃ³n
$stmt->close();
$conexion->close();

} else {
    echo 'No se recibieron datos';
}

?>
