<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include 'conexion.php'; // Incluye la conexión a la base de datos

$message = "";
$messageType = ""; // Para diferenciar entre éxito y error

if (isset($_GET['id'])) {
    $idProveedor = $_GET['id'];

    // Consulta para obtener los datos del proveedor
    $sql = "
        SELECT 
            p.idProveedor,
            pr.idProducto,
            p.Nombre_Provedor,
            p.Telefono,
            p.Correo,
            d.Colonia_Barrio,
            d.Municipio,
            d.Departamento,
            d.Pais
        FROM 
            Proveedor p
        INNER JOIN 
            Direccion d ON p.Direccion_idDireccion = d.idDireccion
        INNER JOIN 
            producto_has_proveedor php ON p.idProveedor = php.Proveedor_idProveedor
        INNER JOIN 
            Producto pr ON php.Producto_idProducto = pr.idProducto
        WHERE p.idProveedor = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $idProveedor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Proveedor no encontrado.</p>";
        exit;
    }

    $stmt->close();
} else {
    echo "<p>ID de proveedor no especificado.</p>";
    exit;
}

// Actualizar datos del proveedor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $colonia = $_POST['colonia'];
    $municipio = $_POST['municipio'];
    $departamento = $_POST['departamento'];
    $pais = $_POST['pais'];
    $idProducto = $_POST['idProducto'];

    // Actualizar la dirección
    $sqlDireccion = "UPDATE Direccion SET Colonia_Barrio = ?, Municipio = ?, Departamento = ?, Pais = ? WHERE idDireccion = (SELECT Direccion_idDireccion FROM Proveedor WHERE idProveedor = ?)";
    $stmtDireccion = $conexion->prepare($sqlDireccion);
    $stmtDireccion->bind_param('ssssi', $colonia, $municipio, $departamento, $pais, $idProveedor);


    // Actualizar el proveedor
    $sqlProveedor = "UPDATE Proveedor SET Nombre_Provedor = ?, Telefono = ?, Correo = ? WHERE idProveedor = ?";
    $stmtProveedor = $conexion->prepare($sqlProveedor);
    $stmtProveedor->bind_param('sssi', $nombre, $telefono, $correo, $idProveedor);

    // Actualizar la relación producto-proveedor
    $sqlRelacion = "UPDATE producto_has_proveedor SET Producto_idProducto = ? WHERE Proveedor_idProveedor = ?";
    $stmtRelacion = $conexion->prepare($sqlRelacion);
    $stmtRelacion->bind_param('ii', $idProducto, $idProveedor);

    if ($stmtDireccion->execute() && $stmtProveedor->execute() && $stmtRelacion->execute()) {
        $message = "Proveedor actualizado con éxito.";
        $messageType = "success";
    } else {
        $message = "Error al actualizar el proveedor.";
        $messageType = "error";
    }

    $stmtDireccion->close();
    $stmtProveedor->close();
    $stmtRelacion->close();
}

// Obtener los productos para el dropdown
$sqlProductos = "SELECT idProducto, Nombre_Producto FROM Producto";
$resultProductos = $conexion->query($sqlProductos);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Proveedor</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
        select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        button[type="submit"] {
            background-color: #800080;
            color: white;
        }

        button[type="button"] {
            background-color: #f0f0f0;
            color: #333;
        }
        
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

    .message-box.success {
        background-color: #28a745;
    }

    .message-box.show {
        display: block;
        opacity: 1;
    }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='ProveedoresLista.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Actualizar Proveedor</h1>
        <form action="" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre_Provedor']; ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $row['Telefono']; ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?php echo $row['Correo']; ?>" required>

            <label for="colonia">Colonia/Barrio:</label>
            <input type="text" id="colonia" name="colonia" value="<?php echo $row['Colonia_Barrio']; ?>" required>

            <label for="municipio">Municipio:</label>
            <input type="text" id="municipio" name="municipio" value="<?php echo $row['Municipio']; ?>" required>

            <label for="departamento">Departamento:</label>
            <input type="text" id="departamento" name="departamento" value="<?php echo $row['Departamento']; ?>" required>

            <label for="pais">País:</label>
            <input type="text" id="pais" name="pais" value="<?php echo $row['Pais']; ?>" required>

            <label for="idProducto">Producto:</label>
            <select id="idProducto" name="idProducto" required>
                <?php
                if ($resultProductos->num_rows > 0) {
                    while ($producto = $resultProductos->fetch_assoc()) {
                        $selected = $producto['idProducto'] == $row['idProducto'] ? 'selected' : '';
                        echo "<option value='{$producto['idProducto']}' $selected>{$producto['Nombre_Producto']}</option>";
                    }
                } else {
                    echo "<option value=''>No hay productos registrados</option>";
                }
                ?>
            </select>

            <div class="buttons">
                <button type="submit">Actualizar</button>
                <button type="button" onclick="location.href='ProveedoresLista.php'">Cancelar</button>
            </div>
        </form>

        <div id="messageBox" class="message-box <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>

<script>
    window.onload = function() {
        var messageBox = document.getElementById('messageBox');
        if (messageBox.innerText.trim() !== "") {
            messageBox.classList.add('show');
            setTimeout(function() {
                messageBox.classList.remove('show');
            }, 3000); // Desaparece después de 3 segundos
        }
    };
</script>

</div>
