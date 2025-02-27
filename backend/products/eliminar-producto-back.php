<?php
session_start();
require_once '../conexion.php';
header('Content-Type: application/json');

$respuesta = [];

// Validemos que se cuente con los privilegios necesario con rol de admin o superadmin
if (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] == 'superadmin' || $_SESSION['rol_usuario'] == 'admin') {
            
    // Validamos que se reciban datos por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_producto = trim($_POST['id_producto']);
           
    } else {
        $respuesta = [
            "mensaje" => "No se recibieron datos.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
    }

    // Validamos que se reciban todos los datos necesarios
    if (empty($id_producto)) {
        $respuesta = [
            "mensaje" => "No se recibió el ID del producto a eliminar.",
            "status" => "error"
        ];
            
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
    }

    
    // Verificar si el producto ya tiene ventas
    $consulta = $conexion->prepare("CALL revisarPosiblesVenta(?)");
    $consulta->bind_param("i", $id_producto);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        // Producto ya tiene ventas
        $respuesta = [
            "mensaje" => "Producto ya tiene ventas, no se puede eliminar.",
            "status" => "error"
        ];

        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Liberar resultados y cerrar la consulta anterior
        $consulta->free_result();
        $consulta->close();

        // Insertar el producto porque no existe
        $stmt = $conexion->prepare("CALL eliminarProducto(?)");
        $stmt->bind_param("i", $id_producto);

        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Producto eliminado con éxito.",
                "status" => "success"
            ];

        } else {
            $respuesta = [
                "mensaje" => "Error en la ejecución: " . $stmt->error,
                "status" => "error"
            ];
        }
        
        if ($stmt) {  // Solo cerramos si $stmt es válido
            $stmt->close();
        }
        

        // Cerrar la conexión
        $conexion->close();

        // Enviar respuesta JSON
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

    }

} else {
    $respuesta = [
        "mensaje" => "No tiene suficientes privilegio.",
        "status" => "error"
    ];

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
}


?>
