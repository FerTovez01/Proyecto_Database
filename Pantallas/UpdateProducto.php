<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

$mensajeExito = ''; // Variable para manejar el mensaje de éxito
$mensajeError = ''; // Variable para manejar el mensaje de error

if (isset($_GET['id'])) {
    $idProducto = intval($_GET['id']); // Convertir a entero para mayor seguridad

    // Consulta SQL para obtener los datos del producto
    $sql = "SELECT Producto.idProducto, Producto.Nombre_Producto, 
                   Producto.Categoria_idCategoria AS idCategoria, 
                   Producto.Marca_idMarca AS idMarca, 
                   Producto.Stock, Producto.Fecha_Ingreso, Producto.Descripcion, 
                   Producto.Precio, Producto.Estado, 
                   producto_has_proveedor.Proveedor_idProveedor AS idProveedor
            FROM Producto
            LEFT JOIN producto_has_proveedor 
                ON Producto.idProducto = producto_has_proveedor.Producto_idProducto
            WHERE Producto.idProducto = $idProducto";

    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        die("Producto no encontrado.");
    }
} else {
    die("ID no proporcionado.");
}

// Obtener listas de categorías, marcas y proveedores
$categorias = $conexion->query("SELECT idCategoria, Nombre_Categoria FROM Categoria");
$marcas = $conexion->query("SELECT idMarca, Nombre_Marca FROM Marca");
$proveedores = $conexion->query("SELECT idProveedor, Nombre_Provedor FROM Proveedor");

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar datos del formulario
    $nombreProducto = $conexion->real_escape_string($_POST['nombre_producto']);
    $categoria = intval($_POST['categoria']);
    $marca = intval($_POST['marca']);
    $proveedor = intval($_POST['proveedor']); // 0 si no hay proveedor
    $stock = intval($_POST['stock']);
    $fechaIngreso = $conexion->real_escape_string($_POST['fecha_ingreso']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $estado = $conexion->real_escape_string($_POST['estado']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Actualizar datos del producto
        $sqlProducto = "UPDATE Producto SET 
                        Nombre_Producto = '$nombreProducto', 
                        Categoria_idCategoria = $categoria, 
                        Marca_idMarca = $marca, 
                        Stock = $stock, 
                        Fecha_Ingreso = '$fechaIngreso', 
                        Descripcion = '$descripcion', 
                        Precio = $precio, 
                        Estado = '$estado'
                      WHERE idProducto = $idProducto";
        $conexion->query($sqlProducto);

        // Gestionar proveedor
        if ($proveedor > 0) {
            // Actualizar o insertar proveedor asociado
            $sqlProveedor = "REPLACE INTO producto_has_proveedor 
                             (Producto_idProducto, Proveedor_idProveedor)
                             VALUES ($idProducto, $proveedor)";
            $conexion->query($sqlProveedor);
        } else {
            // Eliminar relación con proveedor si se seleccionó "Sin proveedor"
            $sqlEliminarProveedor = "DELETE FROM producto_has_proveedor 
                                      WHERE Producto_idProducto = $idProducto";
            $conexion->query($sqlEliminarProveedor);
        }

        // Confirmar transacción
        $conexion->commit();
        $mensajeExito = "Producto actualizado exitosamente.";
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        $mensajeError = "Error al actualizar el producto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="styles5.css">
    <style>
        /* Estilo similar al ejemplo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        select {
            appearance: none;
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24' fill='%23777'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px 15px;
        }
        select:hover, input:hover, textarea:hover {
            border-color: #4CAF50;
        }
        select:focus, input:focus, textarea:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message-box {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            border-radius: 5px;
            color: white;
            background: green;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>
<body>
    <h1>Editar Producto</h1>

    <!-- Mensajes de éxito o error -->
    <?php if ($mensajeExito || $mensajeError): ?>
        <div class="message-box" style="background: <?= $mensajeExito ? 'purple' : 'purple'; ?>">
            <?= $mensajeExito ?: $mensajeError; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre del Producto</label>
        <input type="text" name="nombre_producto" value="<?= $producto['Nombre_Producto']; ?>" required>

        <label>Categoría</label>
        <select name="categoria" required>
            <?php while ($cat = $categorias->fetch_assoc()): ?>
                <option value="<?= $cat['idCategoria']; ?>" <?= $producto['idCategoria'] == $cat['idCategoria'] ? 'selected' : ''; ?>>
                    <?= $cat['Nombre_Categoria']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Marca</label>
        <select name="marca" required>
            <?php while ($mar = $marcas->fetch_assoc()): ?>
                <option value="<?= $mar['idMarca']; ?>" <?= $producto['idMarca'] == $mar['idMarca'] ? 'selected' : ''; ?>>
                    <?= $mar['Nombre_Marca']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Proveedor</label>
        <select name="proveedor">
            <option value="0" <?= !$producto['idProveedor'] ? 'selected' : ''; ?>>Sin proveedor</option>
            <?php while ($prov = $proveedores->fetch_assoc()): ?>
                <option value="<?= $prov['idProveedor']; ?>" <?= $producto['idProveedor'] == $prov['idProveedor'] ? 'selected' : ''; ?>>
                    <?= $prov['Nombre_Provedor']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Stock</label>
        <input type="number" name="stock" value="<?= $producto['Stock']; ?>" required>

        <label>Fecha de Ingreso</label>
        <input type="date" name="fecha_ingreso" value="<?= $producto['Fecha_Ingreso']; ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion" required><?= $producto['Descripcion']; ?></textarea>

        <label>Precio</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto['Precio']; ?>" required>

        <label>Estado</label>
        <select name="estado" required>
            <option value="A" <?= $producto['Estado'] == 'A' ? 'selected' : ''; ?>>Activo</option>
            <option value="I" <?= $producto['Estado'] == 'I' ? 'selected' : ''; ?>>Inactivo</option>
        </select>

        <button type="submit">Actualizar Producto</button>
        <div>
            <button type="button" onclick="location.href='ProductoLista.php'">Volver</button>
        </div>
    </form>
</body>
</html>
