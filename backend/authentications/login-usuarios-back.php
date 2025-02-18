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
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);

    // Validar que no estén vacíos
    if (empty($usuario) || empty($password)) {
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
    $sql = "CALL loginUsuario(?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    
    if ($usuario && password_verify($password, $usuario["password_usuario"])) {
        // Iniciar sesión con los datos del usuario
        $_SESSION["usuario"] = $usuario["nombre_usuario"];
                   
        $respuesta = [
            "mensaje" => "Inicio de sesión exitoso.",
            "status" => "ok"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        $respuesta = "";
        header("Location: ../../index.php"); // Redirigir al panel del cliente
        exit();
      

    } else {
        $respuesta = [
            "mensaje" => "Datos inválidos, intente nuevamente.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        die();
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
