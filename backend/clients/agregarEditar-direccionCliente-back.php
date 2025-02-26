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
        $departamento = trim($_POST['departamento']);
        $cuidad = trim($_POST['cuidad']);
        $direccion_envio = trim($_POST['direccion_envio']);
        $barrio = trim($_POST['barrio']);
        
        // echo "id: " . $id . " / " . "departamento: " . $departamento . " / " . "cuidad: " . $cuidad . " / " . "direccion: " 
        // . $direccion_envio . " / " . "barrio: " . $barrio; die();
        // TESTING**************************************************************

        if (empty($id) || empty($departamento) || empty($cuidad) || empty($direccion_envio) || empty($barrio)) {
            $respuesta = [
                "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
                "status" => "error"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // header("Location: ../../ver_info_cliente.php");
            exit();
        }


        // Realizando la consulta a la base de datos
        $stmt = $conexion->prepare("CALL agregarDireccionCliente(?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id, $departamento, $cuidad, $barrio, $direccion_envio);
        
        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Dirección registrada con éxito.",
                "status" => "success"
            ];
            
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
            // die();TESTING**************************************************************
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
        exit();
    }

    

} else {
    echo 'No se recibieron datos';
}   