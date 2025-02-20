<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $respuesta = [];
    $id = trim($_POST["id"]);
    $email = trim($_POST['email']);
    $password_actual = trim($_POST['password_actual']);
    $password_nuevo = trim($_POST['password_nuevo']);
    $password_confirmacion = trim($_POST['password_confirmacion']);

    // die("id: " . $id . " / " . "email: " . $email . " / " . "pasword actual: " . $password_actual . " / " . "paswword nuevo: " . 
    //     $password_nuevo . " / " . "password validacion: " . $password_confirmacion);

    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {

        // die("La sesión y el id enviado son iguales"); TESTING**************************************************************

        // Verifivamos que todos los campos están llenos
        if (empty($id) || empty($email) || empty($password_actual) || empty($password_nuevo) || empty($password_confirmacion)) {
            $respuesta = [
                "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // die();TESTING**************************************************************
            $respuesta = "";
            exit();
        }

        // Verificamos que el nuevo password y el password de confirmación, sean iguales
        if ($password_nuevo != $password_confirmacion) {
            $respuesta = [
                "mensaje" => "La nueva contraseña y su confirmación no coindicen",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            $respuesta = "";
            // exit();TESTING**************************************************************        
        }


        // Verificamos las credenciales del cliente    
        $stmt = $conexion->prepare("CALL loginCliente(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $cliente = $resultado->fetch_assoc();

        // echo json_encode($cliente, JSON_UNESCAPED_UNICODE);die();TESTING**************************************************************
        
        $stmt->close(); // Cerrar el statement
        
        if (password_verify($password_actual, $cliente["password_cliente"])) {
            
            // Hashear la contrasheda antes de guardarla
            $passwordHasheada = password_hash($password_nuevo, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare("CALL cambiarPasswordCliente(?, ?, ?)");
            $stmt->bind_param("iss", $id, $email, $passwordHasheada);
           
            if ($stmt->execute()) {
                $respuesta = [
                    "mensaje" => "Contraseña cambiada exitosamente",
                    "status" => "ok"
                ];
                
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
                $respuesta = "";
                // die();TESTING**************************************************************
            } else {
                $respuesta = [
                    "mensaje" => "Ocurrió un error y no se generó la actualización",
                    "status" => "error"
                ];
                
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
                $respuesta = "";
                exit();
            }

            $conexion->close();
            
        } else {
            $respuesta = [
                "mensaje" => "Contraseña inválida. Intente nuevamente",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            $respuesta = "";
            // die();TESTING**************************************************************
        }


    } else {
        $respuesta = [
        "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error",
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        $respuesta = "";
        die();
    }

} else {
    $respuesta = [
        "mensaje" => "No se recibieron datos.",
        "status" => "error"
    ];
    
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
    $respuesta = "";
    // header("Location: ../../ver_info_cliente.php");
}