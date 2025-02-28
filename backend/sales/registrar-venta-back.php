<?php
session_start();
require_once '../conexion.php';
header('Content-Type: application/json');

$respuesta = [];

// Validemos que se cuente con los privilegios necesario con rol de admin o superadmin
if (isset($_SESSION['rol_usuario']) && $_SESSION['rol_usuario'] == 'superadmin' || $_SESSION['rol_usuario'] == ' admin') {
    // Se inicializan las variables para que estén disponibles
    $id_cliente = 0;
    $medio_pago = "";
    $numero_referencia_pago = "";
      

    // Validamos que se reciban los datos por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_cliente = trim($_POST['id_cliente']);
        $medio_pago = trim($_POST['medio_pago']);
        $numero_referencia_pago = trim($_POST['numero_referencia_pago']);
        $id_producto = $_POST['id_producto'];
        $cantidades = $_POST['cantidad'];
        $precio_venta = $_POST['precio'];
       
    } else {
        $respuesta = [
            "mensaje" => "No se recibieron datos.",
            "status" => "error"
        ];
        
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
    }

    //Validamos que se reciban todos los datos necesarios
    if (empty($id_cliente) || empty($medio_pago) || empty($numero_referencia_pago) || empty($id_producto)|| empty($cantidades) || empty($precio_venta)) {
        $respuesta = [
            "mensaje" => "Hay campo(s) vacío(s) en el formulario.",
            "status" => "error"
        ];
            
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
        // header("Location: formulario-registro-cliente.php");
        exit();
    }




    // Iniciar la transacción
$conexion->begin_transaction();

// Preparamos la consulta para registrar la venta
$registroVenta = $conexion->prepare("CALL registrarVenta(?, ?, ?, @idVenta)");
$registroVenta->bind_param("iss", $id_cliente, $medio_pago, $numero_referencia_pago);

// Ejecutamos la consulta
if ($registroVenta->execute()) {
    $registroVenta->close();

    // Recuperamos el ID de la venta registrada
    $resultado = $conexion->query("SELECT @idVenta AS idVenta");
    $fila = $resultado->fetch_assoc();
    $idVenta_registrado = $fila['idVenta'] ?? null; // Validamos que tenga un valor

    // Verificar que se obtuvo un ID válido
    if (!$idVenta_registrado) {
        $conexion->rollback();
        die("Error: No se pudo recuperar el ID de la venta");
    }

    // Preparamos la consulta para registrar cada fila de la venta
    $registroLineasVentas = $conexion->prepare("CALL registrarFilaVenta(?, ?, ?, ?)");

    if ($registroLineasVentas) {
        $error = false;

        foreach ($id_producto as $index => $producto) {
            // Enlazamos los parámetros correctamente
            $registroLineasVentas->bind_param("iiii", $idVenta_registrado, $producto, $cantidades[$index], $precio_venta[$index]);

            if (!$registroLineasVentas->execute()) {
                $error = true;
                break;
            }
        }

        if ($error) {
            $conexion->rollback();
            echo "Error: Se canceló la transacción";
        } else {
            $conexion->commit();
            echo "Venta y detalles registrados correctamente";
        }

        $registroLineasVentas->close();
    } else {
        $conexion->rollback();
        echo "Error en la consulta de detalles";
    }
} else {
    $conexion->rollback();
    echo "Error en la inserción de la venta";
}

// Cerrar conexión
$conexion->close();


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