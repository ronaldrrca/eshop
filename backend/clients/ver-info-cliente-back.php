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
if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id ) {

    $respuesta = [];
    // echo "sesión cliente: " . $_SESSION['id_cliente'] . " es igual al id recibido: " . $id; die(); TESTING************************************************************** 
    // Preparar la consulta
    $stmt = $conexion->prepare("CALL verInfoCliente(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cliente = $resultado->fetch_assoc();

    // echo json_encode($cliente, JSON_UNESCAPED_UNICODE); die(); TESTING**************************************************************

    //Verificamos si el id recibido es el mismo de la sesión
    if ($cliente && $cliente['id_cliente'] == $_SESSION['id_cliente']) {
        $respuesta = [
            "mensaje" => "Cliente encontrado.",
            "status" => "ok",
            "nombre" => $cliente['nombre_cliente'],
            "email" => $cliente['email_cliente'],
            "telefono" => $cliente['telefono_cliente'],
            "direccion" => $cliente['direccion_cliente']
        ];
    } else {// Redundancia de seguridas, suponiendo que en el condicional padre no debió pasar la condición para llegar hasta acá.
        $respuesta = [
            "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión..",
            "status" => "error",
        ];
    }

    // Enviar respuesta en JSON
    // echo json_encode($respuesta, JSON_UNESCAPED_UNICODE); die(); TESTING**************************************************************
    $respuesta = "";

    // Cerrar conexión
    $stmt->close();
    $conexion->close();

    header("Location: ../../ver_info_cliente.php");
    exit();

} else {
    $respuesta = [
        "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error",
    ];

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    $respuesta = "";
}

?>


