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
if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {
    $respuesta = [];
    
    // Preparar la consulta
    $stmt = $conexion->prepare("CALL verCarrito(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Inicializar array del data
    $data = [];
    while ($fila = $resultado->fetch_assoc()) {
        $data[] = $fila;
    }

    // Verificar si el data está vacío
    if (empty($data)) {
        $respuesta = [
            "mensaje" => "No hay productos en el data.",
            "status" => "error"
        ];
    } else {
        $respuesta = [
            "mensaje" => "Productos obtenidos correctamente.",
            "status" => "success",
            "data" => $data
        ];
    }

    // Cerrar conexión antes de terminar
    $stmt->close();
    $conexion->close();

    // Enviar respuesta en JSON
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit();

} else {
    $respuesta = [
        "mensaje" => "No existe una sesión iniciada, o el id enviado no corresponde al id de la sesión.",
        "status" => "error",
    ];

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit();
}

?>


