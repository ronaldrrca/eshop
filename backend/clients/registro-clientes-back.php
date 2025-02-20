<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON

$respuesta = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = ucwords(strtolower(trim($_POST["nombre"])));
    $email = trim($_POST["email"]);
    $email_confirmacion = trim($_POST['email_confirmacion']);
    $telefono = isset($_POST['telefono']) ? trim($_POST["telefono"]) : "";
    $password = trim($_POST["password"]);
    $password_confirmacion = trim($_POST["password_confirmacion"]);

    // Validar que los campos no estén vací­os
    if (empty($nombre) || empty($email) || empty($password)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-cliente.php");
        exit();
    }


    // Validar que los emails ingresados sean iguales
    if ($email != $email_confirmacion) {
        $respuesta = [
            "mensaje" => "Los emails son diferentes.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-cliente.php");
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
        header("Location: formulario-registro-cliente.php");
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
        header("Location: formulario-registro-cliente.php");
        exit();
    }


    // Verificar si el email ya está registrado
    $stmt = $conexion->prepare("CALL validarEmail(?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $respuesta = [
            "mensaje" => "El correo ya se encuentra registrado.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-cliente.php");
        exit();
    }

    $stmt->close();


    // Si todo salé bien a este punto...

    // Hashear la contrasheda antes de guardarla
    $passwordHasheada = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el cliente en la base de datos
    $stmt = $conexion->prepare("CALL registrarCliente(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $telefono, $passwordHasheada);

    if ($stmt->execute()) {
        // Obtener el ID del cliente reciénn registrado
        $id_cliente = $conexion->insert_id;

        // Iniciar sesiónn automáticamente
        $_SESSION["cliente"] = $nombre;
        
        $respuesta = [
            "mensaje" => "Registrado con éxito.",
            "status" => "ok"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
        $respuesta = "";
;        
        if (isset($_SESSION['redirect_to'])) {
            header("Location: ../../" . $_SESSION['redirect_to']); // Redirigir al panel del cliente
            exit();
        } else {
            header("Location: ../../index.php"); // Redirigir al panel del cliente
            exit();
        }

    } else {
        $respuesta = [
            "mensaje" => "Se produjo un error.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        $respuesta = "";
        header("Location: formulario-registro-cliente.php");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    echo 'No se recibieron datos';
}
?>
