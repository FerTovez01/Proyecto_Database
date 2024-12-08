<?php
// Incluir la conexión a la base de datos
include '../php/conexion.php';

// Verificar si se ha pasado un ID de inventario por la URL
if (isset($_GET['idInventario'])) {
    $idInventario = $_GET['idInventario'];

    // Obtener el registro de inventario desde la base de datos
    $query = "SELECT mi.Inventario_idInventario, p.Nombre_Producto, 
                     SUM(CASE WHEN mi.Tipo_Movimiento = 'entrada' THEN mi.Cantidad ELSE 0 END) AS Cantidad_Entrada,
                     SUM(CASE WHEN mi.Tipo_Movimiento = 'salida' THEN mi.Cantidad ELSE 0 END) AS Cantidad_Salida,
                     MAX(mi.Fecha_Moviento) AS Fecha_Registro
              FROM movimiento_inventario mi
              JOIN inventario i ON mi.Inventario_idInventario = i.idInventario
              JOIN producto p ON i.Producto_idProducto = p.idProducto
              WHERE mi.Inventario_idInventario = ?
              GROUP BY mi.Inventario_idInventario, p.Nombre_Producto";

    // Preparar la consulta y vincular parámetros
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idInventario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el registro existe, llenar el formulario
    if ($result->num_rows > 0) {
        $inventario = $result->fetch_assoc();
    } else {
        echo "No se encontró el inventario.";
        exit;
    }
} else {
    echo "ID de inventario no proporcionado.";
    exit;
}
// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger las cantidades de entrada y salida desde el formulario
    $cantidadEntrada = $_POST['cantidad_entrada'];
    $cantidadSalida = $_POST['cantidad_salida'];
    $idInventario = $_GET['idInventario'];

    // Depuración: Verificar que los datos sean recibidos correctamente
    echo "Entrada: " . $cantidadEntrada . "<br>"; // Verificar valor de entrada
    echo "Salida: " . $cantidadSalida . "<br>";   // Verificar valor de salida
    echo "ID Inventario: " . $idInventario . "<br>"; // Verificar ID del inventario

    // Actualizar la base de datos con los nuevos valores
    $updateQuery = "UPDATE inventario 
                    SET Cantidad_Entrada = ?, Cantidad_Salida = ? 
                    WHERE idInventario = ?";

    // Preparar y ejecutar la consulta
    $updateStmt = $conexion->prepare($updateQuery);
    $updateStmt->bind_param("iii", $cantidadEntrada, $cantidadSalida, $idInventario);

    if ($updateStmt->execute()) {
        echo "Registro actualizado correctamente.";
    } else {
        echo "Error al actualizar el registro: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Inventario</title>
    <link rel="stylesheet" href="styles7.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: grid;
            gap: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .update-button {
            width: 100%;
            padding: 10px;
            background-color: #833576;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .update-button:hover {
            background-color: #6e2a53;
        }

       
        /* Estilo del mensaje flotante */
        .message-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #833576;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            z-index: 9999;
        }

        .message-box.error {
            background-color: #dc3545;
        }

        .message-box.show {
            display: block;
            opacity: 1;
        }
    </style>
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button class="back-button" type="button" onclick="location.href='InventarioLista.php'">Volver</button>
    </header>

    <div class="container">
        <h1>Actualizar Inventario</h1>
        <form action="UpdateInventario.php?idInventario=<?php echo $inventario['Inventario_idInventario']; ?>" method="POST">
            <div>
                <label for="nombre_producto">Producto:</label>
                <input type="text" id="nombre_producto" name="nombre_producto" value="<?php echo $inventario['Nombre_Producto']; ?>" disabled>
            </div>
            <div>
                <label for="cantidad_entrada">Cantidad Entrada:</label>
                <input type="number" id="cantidad_entrada" name="cantidad_entrada" value="<?php echo $inventario['Cantidad_Entrada']; ?>" required>
            </div>
            <div>
                <label for="cantidad_salida">Cantidad Salida:</label>
                <input type="number" id="cantidad_salida" name="cantidad_salida" value="<?php echo $inventario['Cantidad_Salida']; ?>" required>
            </div>
            <div>
                <label for="fecha_registro">Fecha Registro:</label>
                <input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo $inventario['Fecha_Registro']; ?>" disabled>
            </div>
            <div class="buttons">
                <button class="update-button" type="submit">Actualizar</button>
            </div>
        </form>

    <!-- Mensaje de éxito o error -->
    <div id="messageBox" class="message-box <?php echo $mensajeError ? 'error' : ''; ?>">
        <?php
        if ($mensajeExito) {
            echo $mensajeExito;
        } elseif ($mensajeError) {
            echo $mensajeError;
        }
        ?>
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