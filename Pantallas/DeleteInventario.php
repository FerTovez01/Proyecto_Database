<?php
// Conexión a la base de datos (MySQLi)
$server = "localhost";
$user = "root";
$pass = "";
$DB = "bella_fusion_db";

// Establecer la conexión
$conexion = new mysqli($server, $user, $pass, $DB);

// Verificar si la conexión es exitosa
if ($conexion->connect_errno) {
    die("Conexión Fallida: " . $conexion->connect_errno);
}

// Función para retornar la conexión
function retornarConexion() {
    global $conexion;
    return $conexion;
}

// Procesamiento cuando se recibe la solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['producto_id']) && !empty($_POST['producto_id'])) {
        $producto_id = $_POST['producto_id'];

        // Primero, verificamos si el producto existe en el inventario
        $sql_verificar = "SELECT idInventario FROM inventario WHERE Producto_idProducto = ?";
        $stmt_verificar = retornarConexion()->prepare($sql_verificar);
        $stmt_verificar->bind_param('i', $producto_id);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            // Producto existe, ahora eliminamos los movimientos y el inventario
            $sql_eliminar_movimientos = "DELETE FROM movimiento_inventario WHERE Inventario_idInventario IN (SELECT idInventario FROM inventario WHERE Producto_idProducto = ?)";
            $stmt_eliminar_movimientos = retornarConexion()->prepare($sql_eliminar_movimientos);
            $stmt_eliminar_movimientos->bind_param('i', $producto_id);

            if (!$stmt_eliminar_movimientos->execute()) {
                $errorMessage = "Error al eliminar los movimientos de inventario: " . $stmt_eliminar_movimientos->error;
            } else {
                // Ahora eliminamos el inventario
                $sql_eliminar_inventario = "DELETE FROM inventario WHERE Producto_idProducto = ?";
                $stmt_eliminar_inventario = retornarConexion()->prepare($sql_eliminar_inventario);
                $stmt_eliminar_inventario->bind_param('i', $producto_id);

                if (!$stmt_eliminar_inventario->execute()) {
                    $errorMessage = "Error al eliminar el inventario: " . $stmt_eliminar_inventario->error;
                } else {
                    $successMessage = "Inventario y movimientos eliminados correctamente.";
                }
            }
        } else {
            $errorMessage = "El producto no se encuentra en el inventario.";
        }
    } else {
        $errorMessage = "No se ha proporcionado un ID de producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Inventario</title>
    <link rel="stylesheet" href="styles4.css">
    <style>
         h3 {
            text-align: center;
            margin-top: 20px;
        }
        .flex-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
         /* Estilos para los mensajes */
         .message-box {
            display: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            transition: opacity 0.3s ease-in-out;
        }

        .message-box.show {
            display: block;
            opacity: 1;
        }

        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="../img/bf2.png" alt="Logo">
    </div>
    <button type="button" onclick="location.href='InventarioLista.php'">Volver</button>
</header>

<div class="content">
    <h3>Eliminar Inventario</h3>

    <!-- Formulario para eliminar inventario -->
    <form id="eliminarInventarioForm" method="POST">
        <label for="producto_id">ID Producto a eliminar</label>
        <input type="text" id="producto_id" name="producto_id" required>

        <div class="flex-container">
            <button type="submit">Eliminar Inventario</button>
        </div>
    </form>

    <!-- Mensaje de éxito o error -->
    <div id="messageBox" class="message-box">
        <?php
        if (isset($successMessage)) {
            echo $successMessage;
        } elseif (isset($errorMessage)) {
            echo $errorMessage;
        }
        ?>
    </div>
</div>

<script>
// Mostrar el mensaje emergente
window.onload = function() {
    var messageBox = document.getElementById('messageBox');
    if (messageBox.innerText.trim() !== "") {
        messageBox.classList.add('show');
        setTimeout(function() {
            messageBox.classList.remove('show');
        }, 3000); // El mensaje se desvanece después de 3 segundos
    }
};
</script>
</body>
</html>
