<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON


//Verificamos si el id recibido es el mismo de la sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta = [];
    $id = trim($_POST["id"]);
    $rol_nuevo = trim($_POST['rol_nuevo']);
    // echo $id; die(); TESTING**************************************************************

    if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] != $id && $_SESSION['rol_usuario'] != "vendedor") {
               
        // echo "id: " . $id; die();TESTING**************************************************************

        if (empty($id)) {
            $respuesta = [
                "mensaje" => "Falta el ID del usuario a editar.",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // header("Location: ../../ver_info_cliente.php");
            exit;
        }

                 
        // Realizando la consulta a la base de datos
        $stmt = $conexion->prepare("CALL cambiarRolUsuario(?, ?)");
        $stmt->bind_param("is", $id, $rol_nuevo);
        
        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Rol de usuario cambiado con éxito.",
                "status" => "success"
            ];
            
        } else {
            $respuesta = [
                "mensaje" => "Ocurrió un error.",
                "status" => "error"
            ];
        }

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
         
        $stmt->close();
        $conexion->close();
        exit();

    } else {
        $respuesta = [
        "mensaje" => "Usuario activo no tiene privilegios suficientes.",
        "status" => "error"
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        exit;
    }

} else {
    echo 'No se recibieron datos';
}   