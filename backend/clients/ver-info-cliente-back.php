<?php
session_start();
require_once '../conexion.php';
header("Content-Type: application/json");

// Verificar conexión
if ($conexion->connect_error) {
    echo json_encode(["mensaje" => "Error de conexión a la base de datos", "status" => "error"]);
    exit;
}

// Determinar el ID del cliente
$id = (isset($_POST['id']) ? trim($_POST['id']) : "");
// echo "id recibido: " . $id; die(); TESTING**************************************************************

//Verificamos si el id recibido es el mismo de la sesión
if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id ) {
    // echo "sesión cliente: " . $_SESSION['id_cliente'] . " es igual al id recibido: " . $id; die(); TESTING************************************************************** 
    $respuesta = [];
    
    // Preparar la consulta
    $stmt = $conexion->prepare("CALL verInfoCliente(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // echo json_encode($cliente, JSON_UNESCAPED_UNICODE); die(); TESTING**************************************************************

    // Volvemos a verificar, si hay un resultado, que el id recibido es el mismo de la sesión
    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {
        
        // Inicializar array del data
        $data = [];
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila;
        }

        $respuesta = [
            "mensaje" => "Cliente encontrado.",
            "status" => "ok",
            "data" => $data
        ];
        
    } else {// Redundancia de seguridas, suponiendo que en el condicional padre no debió pasar la condición para llegar hasta acá.
        $respuesta = [
            "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión..",
            "status" => "error",
        ];
    }

    // echo json_encode($respuesta, JSON_UNESCAPED_UNICODE); die(); TESTING**************************************************************
    
    // Cerrar conexión
    $stmt->close();
    $conexion->close();

} else {
    $respuesta = [
        "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error",
    ];

    exit();
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);


?>


