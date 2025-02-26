<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $respuesta = [];
    $id = trim($_POST["id"]);
    $usuario = trim($_POST['usuario']);
    $password_actual = trim($_POST['password_actual']);
    $password_nuevo = trim($_POST['password_nuevo']);
    $password_confirmacion = trim($_POST['password_confirmacion']);
    
    // die("id: " . $id . " / " . "usuario: " . $usuario . " / " . "pasword actual: " . $password_actual . " / " . "paswword nuevo: " . 
    //     $password_nuevo . " / " . "password confirmacion: " . $password_confirmacion);

    if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $id) {

        // die("La sesión y el id enviado son iguales"); 
        // TESTING**************************************************************

        // Verifivamos que todos los campos están llenos
        if (empty($id) || empty($password_nuevo) || empty($usuario) || empty($password_actual) || empty($password_confirmacion)) {
            $respuesta = [
                "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            exit;
        }

        // Verificamos que el nuevo password y el password de confirmación, sean iguales
        if ($password_nuevo != $password_confirmacion) {
            $respuesta = [
                "mensaje" => "La nueva contraseña y su confirmación no coindicen",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            exit;   
        }


        // Verificamos las credenciales del cliente    
        $stmt = $conexion->prepare("CALL loginUsuario(?)");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario_resultado = $resultado->fetch_assoc();

        // echo json_encode($cliente, JSON_UNESCAPED_UNICODE);die();TESTING**************************************************************
        
        $stmt->close(); // Cerrar el statement
        
        if (password_verify($password_actual, $usuario_resultado["password_usuario"])) {
            
            // Hashear la contrasheda antes de guardarla
            $passwordHasheada = password_hash($password_nuevo, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare("CALL cambiarPasswordUsuario(?, ?, ?)");
            $stmt->bind_param("iss", $id, $usuario, $passwordHasheada);
//             var_dump($id, $usuario, $passwordHasheada, $usuario_resultado["password_usuario"]);
// exit();

           
            if ($stmt->execute()) {
                $respuesta = [
                    "mensaje" => "Contraseña cambiada exitosamente",
                    "status" => "succes"
                ];
                
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
                exit;

            } else {
                $respuesta = [
                    "mensaje" => "Ocurrió un error y no se generó la actualización",
                    "status" => "error"
                ];
                
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
                exit();
            }

            $conexion->close();
            
        } else {
            $respuesta = [
                "mensaje" => "Contraseña inválida. Intente nuevamente",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            exit;
        }

    } else {
        $respuesta = [
        "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error",
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        exit;
    }

} else {
    $respuesta = [
        "mensaje" => "No se recibieron datos.",
        "status" => "error"
    ];
    
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
    
    // header("Location: ../../ver_info_cliente.php");
}