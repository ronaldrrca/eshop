<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON


//Verificamos si se están recibiendo datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = trim($_POST["id_cliente"]);
    $id_producto = trim($_POST["id_producto"]);
    $respuesta = [];

    // Verifivamos que todos los campos están llenos
    if (empty($id_cliente) || empty($id_producto)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        exit;
    }


    // Verifivamos si el id de la sesión es el  mismo del id recibido
    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id_cliente) {
        
        // Preparar la consulta
        $stmt = $conexion->prepare("CALL eliminarDelCarrito(?, ?)");
        $stmt->bind_param("ii", $id_cliente, $id_producto);
        
        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Se eliminó el item del carrito.",
                "status" => "success",
                ];
        
        } else {
            $respuesta = [
                "mensaje" => "Ocurrió un error, no se eliminó el item del carrito.",
                "status" => "error",
                ];    
        }

        $stmt->close();
        $conexion->close();

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        exit;

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
        "status" => "error",
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
}