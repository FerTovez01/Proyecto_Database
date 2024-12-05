<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Consulta SQL para obtener los datos de los productos
$sql = "SELECT Producto.idProducto, Producto.Nombre_Producto, 
               Categoria.Nombre_Categoria, Marca.Nombre_Marca, 
               COALESCE(Proveedor.Nombre_Provedor, 'Sin proveedor') AS Nombre_Provedor, 
               Producto.Stock, Producto.Fecha_Ingreso, Producto.Descripcion, 
               Producto.Precio, Producto.Estado
        FROM Producto
        INNER JOIN Categoria ON Producto.Categoria_idCategoria = Categoria.idCategoria
        INNER JOIN Marca ON Producto.Marca_idMarca = Marca.idMarca
        LEFT JOIN producto_has_proveedor ON Producto.idProducto = producto_has_proveedor.Producto_idProducto
        LEFT JOIN Proveedor ON producto_has_proveedor.Proveedor_idProveedor = Proveedor.idProveedor";


if ($conexion) {
    $resultado = $conexion->query($sql);

    if (!$resultado) {
        die("Error al ejecutar la consulta: " . $conexion->error);
    }
} else {
    die("Error de conexión: " . $conexion->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="styles3.css">
    <!-- Incluir la CDN de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Lista de Productos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Proveedor</th>
                    <th>Stock</th>
                    <th>Fecha Ingreso</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
               if ($resultado && $resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["idProducto"] . "</td>
                            <td>" . $row["Nombre_Producto"] . "</td>
                            <td>" . $row["Nombre_Categoria"] . "</td>
                            <td>" . $row["Nombre_Marca"] . "</td>
                            <td>" . $row["Nombre_Provedor"] . "</td>
                            <td>" . $row["Stock"] . "</td>
                            <td>" . $row["Fecha_Ingreso"] . "</td>
                            <td>" . $row["Descripcion"] . "</td>
                            <td>" . $row["Precio"] . "</td>
                            <td>" . $row["Estado"] . "</td>
                            <td>
                                <a href='UpdateProducto.php?id=" . $row['idProducto'] . "' class='accion' style='color: #833576;'>
                                    <i class='fas fa-edit'></i>
                                </a>  
                                | 
                                <a href='DeleteProducto.php?id=" . $row['idProducto'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este producto?\")' style='color: #833576;'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No hay productos registrados.</td></tr>";
            }
             ?>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" onclick="location.href='GestionProducto.php'">Registrar Producto</button>
        </div>
    </div>
</body>
</html>

<?php
if ($conexion) {
    $conexion->close();
}
?>
