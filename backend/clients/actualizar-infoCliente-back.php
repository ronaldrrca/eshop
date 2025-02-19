<?php
session_start();
require_once "../conexion.php"; 
header('Content-Type: application/json');  // Indicar que la respuesta es JSON

$respuesta = [];

//Verificamos si el id recibido es el mismo de la sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = trim($_POST["id"]);
        // echo $id; die();TESTING**************************************************************
    if (isset($_SESSION['id_cliente']) && $_SESSION['id_cliente'] == $id) {
            $email_actual = trim($_POST["email_actual"]);// Lo recibimos como campo oculto del html, el valor original para comparlo con el que llegó por el formulario
            $email_nuevo = trim($_POST["email_nuevo"]); 
            $email_confirmacion = trim($_POST['email_confirmacion']);
            $telefono = isset($_POST['telefono']) ? trim($_POST["telefono"]) : "";
            $direccion = isset($_POST['direccion']) ? trim($_POST["direccion"]) : "";
            $password = trim($_POST['password']);
            // echo "sesion id cliente: " . $_SESSION['id_cliente'] . " / " . "email actual: " . $email_actual . " / " . "email nuevo: " . $email_nuevo . " / " 
            // . "email confirmacion: " . $email_confirmacion . " / " . "telefono: " . $telefono . " / " . "direccion: " . $direccion . " / " . "password: " . $password;
            // die();TESTING**************************************************************
            
            // Validar que los campos no estén vací­os
            if (empty($id) || empty($email_nuevo) || empty($email_actual) || empty($email_confirmacion)|| empty($password)) {
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
        
        
            // Validar que los emails ingresados sean iguales
            if ($email_nuevo != $email_confirmacion) {
                $respuesta = [
                    "mensaje" => "Los emails son diferentes.",
                    "status" => "error"
                ];
                
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON

                // die();TESTING**************************************************************

                $respuesta = "";
                header("Location: ../../ver_info_cliente.php");
                exit();
            }

            // Verificamos las credenciales del cliente    
            $stmt = $conexion->prepare("CALL loginCliente(?)");
            $stmt->bind_param("s", $email_actual);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $cliente = $resultado->fetch_assoc();

            $stmt->close(); // Cerrar el statement

            // echo json_encode($cliente, JSON_UNESCAPED_UNICODE); die();TESTING**************************************************************

            // Validamos la contraseña del cliente
            if (password_verify($password, $cliente["password_cliente"])) {

                // echo "Son igusles"; die(); TESTING**************************************************************

                // Validamos si el email actual (que viene como campo oculto) y el nuevo, son diferentes, para consultar si el nuevo se encuentra o no registrado
                if ($email_actual != $email_nuevo) {

                    // echo 'son diferentes'; die();TESTING**************************************************************

                    // Verificar si el email ya está registrado
                    $stmt = $conexion->prepare("CALL validarEmail(?)");
                    $stmt->bind_param("s", $email_nuevo);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if ($stmt->num_rows > 0) {

                        // die("ya existe");TESTING**************************************************************

                        $respuesta = [
                            "mensaje" => "El correo ya se encuentra registrado.",
                            "status" => "error"
                        ];
                        
                        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON

                        // die();TESTING**************************************************************

                        $respuesta = "";
                        // header("Location: ../../ver_info_cliente.php");
                        exit();
                    }
            
                    $stmt->close();
                }
            
            
                // Si todo salé bien a este punto...
            
                $stmt = $conexion->prepare("CALL actualizarCliente(?, ?, ?, ?)");
                $stmt->bind_param("ssss", $id, $email_nuevo, $telefono, $direccion);
                    
                // Actualizar el cliente en la base de datos
                if ($stmt->execute()) {
                    $respuesta = [
                        "mensaje" => "Usuario actualizado con éxito.",
                        "status" => "ok"
                    ];
                        
                    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 

                    die();

                    $respuesta = "";
                        
                } else {
                    $respuesta = [
                        "mensaje" => "Se produjo un error.",
                        "status" => "error"
                    ];
                        
                    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON
                    $respuesta = "";
                }
                    
                $stmt->close();
                $conexion->close();
            
                header("Location: ../../ver_info_cliente.php");
            
            } else {
                $respuesta = [
                    "mensaje" => "Contraseña inválida.",
                    "status" => "error"
                ];
                    
                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);  // Convertir array PHP a JSON 
                $respuesta = "";
                exit();
            }
        
        } else {
            $respuesta = [
            "mensaje" => "No existe un asesión iniciada, o el id enviado no corresponde al id de la sesión.",
            "status" => "error",
            ];

            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            $respuesta = "";
        }

} else {
    echo 'No se recibieron datos';
}
?>
