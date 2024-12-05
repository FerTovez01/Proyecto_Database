<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include 'conexion.php'; // Incluye la conexión a la base de datos

// Variable para mostrar mensajes de éxito o error
$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibe los datos del formulario
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $colonia = $_POST['colonia'];
    $municipio = $_POST['municipio'];
    $departamento = $_POST['departamento'];
    $pais = $_POST['pais'];
    $idProducto = $_POST['idProducto'];

    // Inserta la dirección primero
    $sqlDireccion = "INSERT INTO Direccion (Colonia_Barrio, Municipio, Departamento, Pais) VALUES (?, ?, ?, ?)";
    $stmtDireccion = $conexion->prepare($sqlDireccion);
    $stmtDireccion->bind_param("ssss", $colonia, $municipio, $departamento, $pais);

    if ($stmtDireccion->execute()) {
        // Obtiene el ID de la dirección insertada
        $idDireccion = $conexion->insert_id;

        // Inserta el proveedor sin la relación con el producto
        $sqlProveedor = "INSERT INTO Proveedor (Nombre_Provedor, Telefono, Correo, Direccion_idDireccion) VALUES (?, ?, ?, ?)";
        $stmtProveedor = $conexion->prepare($sqlProveedor);
        $stmtProveedor->bind_param("sssi", $nombre, $telefono, $correo, $idDireccion);

        if ($stmtProveedor->execute()) {
            // Obtiene el ID del proveedor insertado
            $idProveedor = $conexion->insert_id;

            // Inserta la relación en la tabla intermedia producto_has_proveedor
            $sqlRelacion = "INSERT INTO producto_has_proveedor (Producto_idProducto, Proveedor_idProveedor) VALUES (?, ?)";
            $stmtRelacion = $conexion->prepare($sqlRelacion);
            $stmtRelacion->bind_param("ii", $idProducto, $idProveedor);

            if ($stmtRelacion->execute()) {
                $successMessage = 'Proveedor registrado exitosamente.';
                header("Location: ProveedoresLista.php");
                exit;
            } else {
                $errorMessage = 'Error al registrar la relación producto-proveedor: ' . $conexion->error;
            }
        } else {
            $errorMessage = 'Error al registrar el proveedor: ' . $conexion->error;
        }
    } else {
        $errorMessage = 'Error al registrar la dirección: ' . $conexion->error;
    }

    // Cierra las declaraciones
    $stmtDireccion->close();
    $stmtProveedor->close();
    $stmtRelacion->close();
    $conexion->close();
}

// Consulta para obtener los productos registrados
$sqlProductos = "SELECT idProducto, Nombre_Producto FROM Producto";
$resultProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
        /* Estilos para los selects */
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
            background-color: #e9f5ff;
            outline: none;
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
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
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

        /* Estilo para el formulario en columna */
        .column {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre los elementos */
            max-width: 400px;
            margin: 0 auto;
        }

        .column label {
            font-weight: bold;
        }

        .column input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .buttons button {
            width: 48%;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .buttons button[type="submit"] {
            background-color: #800080; /* Morado */
            color: white;
        }

        .buttons button[type="button"] {
            background-color: #800080; /* Morado */
            color: white;
            border: 1px solid #800080; /* Borde morado */
        }

        .buttons button:hover {
            background-color: #6a006a; /* Morado más oscuro al pasar el ratón */
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>

    <div class="container">
        <h1>Registrar Proveedor</h1>
        <form method="POST">
            <div class="column">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="colonia">Colonia/Barrio:</label>
                <input type="text" id="colonia" name="colonia" required>

                <label for="municipio">Municipio:</label>
                <input type="text" id="municipio" name="municipio" required>

                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" required>

                <label for="pais">País:</label>
                <input type="text" id="pais" name="pais" required>

                <label for="idProducto">ID Producto:</label>
                <select id="idProducto" name="idProducto" required>
                    <option value="">Seleccione un producto</option>
                    <?php
                    // Verifica si hay productos
                    if ($resultProductos->num_rows > 0) {
                        while ($producto = $resultProductos->fetch_assoc()) {
                            echo "<option value='" . $producto['idProducto'] . "'>" . $producto['Nombre_Producto'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay productos registrados</option>";
                    }
                    ?>
                </select>

                <div class="buttons">
                    <button type="submit">Registrar</button>
                    <button type="button" onclick="window.location.href='ProveedoresLista.php'">Cancelar</button>
                </div>
            </div>
        </form>

        <!-- Mensaje de éxito o error -->
        <div id="messageBox" class="message-box <?php echo $successMessage ? 'success' : ($errorMessage ? 'error' : ''); ?>">
            <?php
            if ($successMessage) {
                echo $successMessage;
            } elseif ($errorMessage) {
                echo $errorMessage;
            }
            ?>
        </div>
    </div>

    <script>
        window.onload = function() {
            var messageBox = document.getElementById('messageBox');
            if (messageBox.innerText.trim() !== "") {
                messageBox.classList.add('show');
                setTimeout(function() {
                    messageBox.classList.remove('show');
                }, 3000);
            }
        };
    </script>
</body>
</html>
