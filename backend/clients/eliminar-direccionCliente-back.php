<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON


//Verificamos si el id recibido es el mismo de la sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta = [];
    $id = trim($_POST["id"]);
    // echo $id; die(); TESTING**************************************************************

    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {
               
        // echo "id: " . $id; die();TESTING**************************************************************

        if (empty($id)) {
            $respuesta = [
                "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            $respuesta = "";
            // die();TESTING**************************************************************
            // header("Location: ../../ver_info_cliente.php");
            exit();
        }


          
        // Realizando la consulta a la base de datos
        $stmt = $conexion->prepare("CALL eliminarDireccionCliente(?)");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Dirección eliminada con éxito.",
                "status" => "ok"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // die();TESTING**************************************************************
            $respuesta = "";
            // header("Location: formulario-registro-cliente.php");
            exit();
        }

    $stmt->close();
    $conexion->close();

    } else {
        $respuesta = [
        "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error"
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        $respuesta = "";
        exit();
    }

    

} else {
    echo 'No se recibieron datos';
}   