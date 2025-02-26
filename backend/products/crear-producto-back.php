<?php
session_start();
require_once '../conexion.php';
header('Content-Type: application/json');

$respuesta = [];

// Validemos que se cuente con los privilegios necesario con rol de admin o superadmin
if (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] == 'superadmin' || $_SESSION['rol_usuario'] == 'admin') {
    // Se inicializan las variables para que estén disponibles
    $nombre_producto = "";
    $descripcion_producto = "";
    $categoria_producto = "";
    $codigo_barras_producto = "";
    $marca_producto = "";
    $precio_producto = "";
    $stock_producto = ""; 

    // Validamos que se reciban datos por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre_producto = trim($_POST['nombre_producto']);
        $descripcion_producto = trim($_POST['descripcion_producto']);
        $categoria_producto = trim($_POST['categoria_producto']);
        $codigo_barras_producto = trim($_POST['codigo_barras_producto']);
        $marca_producto = trim($_POST['marca_producto']);
        $precio_producto = trim($_POST['precio_producto']);
        $stock_producto = trim($_POST['stock_producto']);
    
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
    if (empty($nombre_producto) || empty($descripcion_producto) || empty($categoria_producto) || empty($codigo_barras_producto)|| empty($marca_producto) || empty($precio_producto) || empty($stock_producto)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
            
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
    }


    // Verificar si el código de barras ya existe
    $consulta = $conexion->prepare("CALL validarCodigoDeBarras(?)");
    $consulta->bind_param("s", $codigo_barras_producto);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        // Código de barras ya existe
        $respuesta = [
            "mensaje" => "Código de barras del producto ya existe en la base de datos.",
            "status" => "error"
        ];
    } else {
        // Liberar resultados y cerrar la consulta anterior
        $consulta->free_result();
        $consulta->close();
        
        // Insertar el producto porque no existe
        $stmt = $conexion->prepare("CALL crearProducto(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssii", $nombre_producto, $descripcion_producto, $categoria_producto, $codigo_barras_producto, $marca_producto, $precio_producto, $stock_producto);

        if ($stmt->execute()) {
            $respuesta = [
                "mensaje" => "Producto creado con éxito.",
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
    }

    // Cerrar la conexión
    $conexion->close();

    // Enviar respuesta JSON
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);

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
