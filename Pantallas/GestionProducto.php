<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

// Incluir el archivo de conexión
include '../php/conexion.php';

// Consultas para obtener las marcas, categorías y proveedores
$sqlMarcas = "SELECT idMarca, Nombre_Marca FROM Marca";
$resultadoMarcas = $conexion->query($sqlMarcas);

$sqlCategorias = "SELECT idCategoria, Nombre_Categoria FROM Categoria";
$resultadoCategorias = $conexion->query($sqlCategorias);

$sqlProveedores = "SELECT idProveedor, Nombre_Provedor FROM Proveedor";
$resultadoProveedores = $conexion->query($sqlProveedores);

// Verificar si la consulta fue exitosa
if (!$resultadoMarcas || !$resultadoCategorias || !$resultadoProveedores) {
    die("Error al obtener los datos: " . $conexion->error);
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['Nombre'];
    $marca = $_POST['Marca'];
    $categoria = $_POST['Categoria'];
    $idProveedor = $_POST['IdProveedor'];
    $stock = $_POST['stock'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['Precio'];
    $fechaIngreso = $_POST['FechaIngreso'];
    $estado = $_POST['Estado'];

    // Comenzamos la transacción para asegurar que los datos se insertan correctamente en todas las tablas
    $conexion->begin_transaction();

    try {
        // Insertar en la tabla Producto
        $sqlProducto = "INSERT INTO Producto (Nombre_Producto, Marca_idMarca, Categoria_idCategoria, Descripcion, Precio, Stock, Fecha_Ingreso, Estado) 
                        VALUES ('$nombre', '$marca', '$categoria', '$descripcion', '$precio', '$stock', '$fechaIngreso', '$estado')";
        if (!$conexion->query($sqlProducto)) {
            throw new Exception("Error al insertar en la tabla Producto: " . $conexion->error);
        }

        // Confirmar la transacción
        $conexion->commit();
        $successMessage = "Producto registrado exitosamente!";
    } catch (Exception $e) {
        // Si hubo algún error, deshacer la transacción
        $conexion->rollback();
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Producto</title>
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
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>

    <div class="container">
        <h1>Gestión de Producto</h1>
        <form method="POST">
            <div class="column">
                <label for="Nombre">Nombre del Producto:</label>
                <input type="text" id="Nombre" name="Nombre" required>
                
                <label for="Marca">Marca:</label>
                <select id="Marca" name="Marca" required>
                    <option value="">Seleccione una marca</option>
                    <?php
                    while ($row = $resultadoMarcas->fetch_assoc()) {
                        echo "<option value='" . $row['idMarca'] . "'>" . $row['Nombre_Marca'] . "</option>";
                    }
                    ?>
                </select>

                <label for="Categoria">Categoría:</label>
                <select id="Categoria" name="Categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php
                    while ($row = $resultadoCategorias->fetch_assoc()) {
                        echo "<option value='" . $row['idCategoria'] . "'>" . $row['Nombre_Categoria'] . "</option>";
                    }
                    ?>
                </select>

                <label for="IdProveedor">Proveedor:</label>
                <select id="IdProveedor" name="IdProveedor" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php
                    while ($row = $resultadoProveedores->fetch_assoc()) {
                        echo "<option value='" . $row['idProveedor'] . "'>" . $row['Nombre_Provedor'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="column">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>

                <label for="Precio">Precio:</label>
                <input type="text" id="Precio" name="Precio" required>

                <label for="FechaIngreso">Fecha de Ingreso:</label>
                <input type="date" id="FechaIngreso" name="FechaIngreso" required>

                <label for="Estado">Estado:</label>
                <select id="Estado" name="Estado" required>
                    <option value="A">Activo</option>
                    <option value="I">Inactivo</option>
                </select>
            </div>

            <div class="buttons">
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
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
