<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON

$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = ucwords(strtolower(trim($_POST["nombre"])));
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["password"]);
    $password_confirmacion = trim($_POST["password_confirmacion"]);
    $rol = trim($_POST['rol']);

       // Validar que los campos no estén vací­os
    if (empty($nombre) || empty($usuario) || empty($password) || empty($password_confirmacion) || empty($rol)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-usuario.php");
        exit();
    }


     // Validar que el password de confirmación sea igual al password
    if ($password != $password_confirmacion) {
        $respuesta = [
            "mensaje" => "Las contraseñas no coindicen.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-usuario.php");
        exit();
    }


     // Validar que las contraseñas tengan el número de caracteres requerido
    if (strlen($password) < 6 || strlen($password) > 8) {
        $respuesta = [
            "mensaje" => "La contraseña debe tener entre 6 y 8 caracteres.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-usuario.php");
        exit();
    }


     // Validar que el usuario tenga el número de caracteres requerido
     if (strlen($usuario) < 6 || strlen($usuario) > 8) {
        $respuesta = [
            "mensaje" => "El usuario debe tener entre 6 y 8 caracteres.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        die();
        $respuesta = "";
        header("Location: formulario-registro-usuario.php");
        exit();
    }


    // Si todo salé bien a este punto...

    // Hashear la contrasheda antes de guardarla
    $passwordHasheada = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el cliente en la base de datos
    $stmt = $conexion->prepare("CALL registrarUsuario(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $usuario, $passwordHasheada, $rol);

    if ($stmt->execute()) {
        // Obtener el ID del cliente reciénn registrado
        $id_usuario = $conexion->insert_id;

        // Iniciar sesiónn automáticamente
        $_SESSION["usuario"] = $nombre;
        
        $respuesta = [
            "mensaje" => "Registrado con éxito.",
            "status" => "ok"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        $respuesta = "";
;        
        header("Location: ../../index.php"); // Redirigir al panel del cliente
        exit();
       
    } else {
        $respuesta = [
            "mensaje" => "Se produjo un error.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-usuario.php");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    echo 'No se recibieron datos';
}
?>
