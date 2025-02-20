<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON

//Verificamos si el id recibido es el mismo de la sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $respuesta = [];
    $id = trim($_POST["id"]);
    $departamento = trim($_POST['departamento']);
    $ciudad = trim($_POST['ciudad']);
    $direccion = trim($_POST['direccion']);
    $barrio = trim($_POST['barrio']);
        // echo "id: " . $id . " / " . "departamento: "  . $departamento . " / " . "ciudad: " . $ciudad . " / " . "direccion: " . $direccion . " / " . "barrio: " . $barrio; die();       
        // TESTING**************************************************************
    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {
                        
        // die("son iguales"); // TESTING**************************************************************
            
        // Validar que los campos no estén vací­os
        if (empty($id) || empty($departamento) || empty($ciudad) || empty($direccion) || empty($barrio)) {
            $respuesta = [
                "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
                "status" => "error"
            ];
                
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // die(); TESTING**************************************************************
            $respuesta = "";
            // header("Location: ../../ver_info_cliente.php");
            exit();
        } 


        // Si todo salé bien a este punto...
            
        $stmt = $conexion->prepare("CALL editarDireccionCliente(?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id, $departamento, $ciudad, $direccion, $barrio);
                    
        // Actualizar el cliente en la base de datos
        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Usuario actualizado con éxito.",
                    "status" => "ok"
                ];
                        
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
            $respuesta = "";
            die();

        } else {
            $respuesta = [
                "mensaje" => "Se produjo un error.",
                    "status" => "error"
                ];
                        
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            $respuesta = "";
            die();
        }
                    
        $stmt->close();
        $conexion->close();
            
        // header("Location: ../../ver_info_cliente.php");
                  
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
?>
