<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Parámetros de búsqueda y paginación
$buscar = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Consulta con búsqueda
$sql = "SELECT Producto.idProducto, Producto.Nombre_Producto, 
               Categoria.Nombre_Categoria, Marca.Nombre_Marca, 
               COALESCE(Proveedor.Nombre_Provedor, 'Sin proveedor') AS Nombre_Provedor, 
               Producto.Stock, Producto.Fecha_Ingreso, Producto.Descripcion, 
               Producto.Precio, Producto.Estado
        FROM Producto
        INNER JOIN Categoria ON Producto.Categoria_idCategoria = Categoria.idCategoria
        INNER JOIN Marca ON Producto.Marca_idMarca = Marca.idMarca
        LEFT JOIN producto_has_proveedor ON Producto.idProducto = producto_has_proveedor.Producto_idProducto
        LEFT JOIN Proveedor ON producto_has_proveedor.Proveedor_idProveedor = Proveedor.idProveedor
        WHERE Producto.Nombre_Producto LIKE '%$buscar%'
        LIMIT $inicio, $registros_por_pagina";

// Total de registros para la paginación
$sql_total = "SELECT COUNT(*) as total
              FROM Producto
              WHERE Nombre_Producto LIKE '%$buscar%'";

$result_total = $conexion->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Ejecutar la consulta principal
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error al ejecutar la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .buscar-form {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    gap: 10px;
}

.buscar-form input {
    padding: 8px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.buscar-form button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    background-color: #833576;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

.buscar-form button:hover {
    background-color: #5a2448;
}

.paginacion {
    display: flex;
    justify-content: center;
    gap: 5px;
}

.paginacion a {
    padding: 8px 12px;
    border: 1px solid #833576;
    color: #833576;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.paginacion a:hover, .paginacion a.activo {
    background-color: #833576;
    color: white;
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
        <h1>Lista de Productos</h1>
        
        <!-- Formulario de búsqueda -->
        <form method="get" class="buscar-form">
            <input type="text" name="buscar" placeholder="Buscar producto..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
        </form>
        
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
                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['idProducto']}</td>
                                <td>{$row['Nombre_Producto']}</td>
                                <td>{$row['Nombre_Categoria']}</td>
                                <td>{$row['Nombre_Marca']}</td>
                                <td>{$row['Nombre_Provedor']}</td>
                                <td>{$row['Stock']}</td>
                                <td>{$row['Fecha_Ingreso']}</td>
                                <td>{$row['Descripcion']}</td>
                                <td>{$row['Precio']}</td>
                                <td>{$row['Estado']}</td>
                                <td>
                                    <a href='UpdateProducto.php?id={$row['idProducto']}' class='accion'>
                                        <i class='fas fa-edit'></i>
                                    </a>  
                                    | 
                                    <a href='DeleteProducto.php?id={$row['idProducto']}' 
                                       onclick='return confirm(\"¿Estás seguro de eliminar este producto?\")' class='accion'>
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
        
        <!-- Paginación -->
        <div class="paginacion">
            <?php if ($pagina_actual > 1): ?>
                <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $pagina_actual - 1; ?>">&laquo; Anterior</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $i; ?>" 
                   class="<?php echo ($i == $pagina_actual) ? 'activo' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($pagina_actual < $total_paginas): ?>
                <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
        
        <div class="buttons">
            <button type="button" onclick="location.href='GestionProducto.php'">Registrar Producto</button>
        </div>
    </div>
</body>
</html>

<?php
$conexion->close();
?>
