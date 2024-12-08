<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Configuración de paginación
$limit = 5; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Búsqueda
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta para obtener los datos requeridos con paginación y búsqueda
$sql = "
SELECT 
    f.idFactura,
    f.Fecha_Emision,
    v.Cliente_idCliente AS idCliente,
    v.Empleado_idEmpleado AS idEmpleado,  -- Se muestra el idEmpleado desde la tabla Venta
    mp.Nombre_Pago,
    dv.Producto_idProducto AS idProducto,
    dv.Cantidad,
    dv.Precio_Unitario AS Precio
FROM 
    Factura f
INNER JOIN 
    Venta v ON f.Venta_idVenta = v.idVenta
INNER JOIN 
    Metodo_Pago mp ON v.Metodo_Pago_idMetodo_Pago = mp.idMetodo_Pago
INNER JOIN 
    Detalle_Venta dv ON v.idVenta = dv.Venta_idVenta
INNER JOIN 
    Producto p ON dv.Producto_idProducto = p.idProducto
WHERE 
    f.idFactura LIKE '%$search_query%' OR
    v.Cliente_idCliente LIKE '%$search_query%' OR
    v.Empleado_idEmpleado LIKE '%$search_query%' 
LIMIT $limit OFFSET $offset";

// Ejecutar la consulta y almacenar el resultado
$resultado = $conexion->query($sql);

// Consulta para contar el total de registros (para la paginación)
$total_result_sql = "
SELECT COUNT(DISTINCT f.idFactura) as total
FROM 
    Factura f
INNER JOIN 
    Venta v ON f.Venta_idVenta = v.idVenta
INNER JOIN 
    Metodo_Pago mp ON v.Metodo_Pago_idMetodo_Pago = mp.idMetodo_Pago
INNER JOIN 
    Detalle_Venta dv ON v.idVenta = dv.Venta_idVenta
INNER JOIN 
    Producto p ON dv.Producto_idProducto = p.idProducto
WHERE 
    f.idFactura LIKE '%$search_query%' OR
    v.Cliente_idCliente LIKE '%$search_query%' OR
    v.Empleado_idEmpleado LIKE '%$search_query%'";

$total_resultado = $conexion->query($total_result_sql);
$total_row = $total_resultado->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Facturas</title>
    <link rel="stylesheet" href="styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #833576;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            background-color: white;
        }

        table th {
            background-color: #f2f2f2;
            color: #833576;
        }

        .acciones a {
            color: purple;
            margin-right: 10px;
            text-decoration: none;
        }

        .acciones a:hover {
            color: darkviolet;
        }

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            background-color: white;
        }

        .search-button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #833576;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #722c60;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            background-color: #833576;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #722c60;
        }

        .buttons button {
            padding: 10px 20px;
            background-color: #833576;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button:hover {
            background-color: #722c60;
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
        <h1>Lista de Facturas</h1>

        <!-- Formulario de búsqueda -->
        <form method="get" action="" class="search-form">
            <input type="text" name="search" placeholder="Buscar por ID Factura, Cliente, Empleado..." value="<?= htmlspecialchars($search_query) ?>" class="search-input">
            <button type="submit" class="search-button">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Factura</th>
                    <th>Fecha Emisión</th>
                    <th>ID Cliente</th>
                    <th>ID Empleado</th> <!-- Nueva columna -->
                    <th>Método de Pago</th>
                    <th>ID Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay resultados
                if ($resultado && $resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $fila['idFactura'] . "</td>";
                        echo "<td>" . $fila['Fecha_Emision'] . "</td>";
                        echo "<td>" . $fila['idCliente'] . "</td>";
                        echo "<td>" . $fila['idEmpleado'] . "</td>";
                        echo "<td>" . $fila['Nombre_Pago'] . "</td>";
                        echo "<td>" . $fila['idProducto'] . "</td>";
                        echo "<td>" . $fila['Cantidad'] . "</td>";
                        echo "<td>" . $fila['Precio'] . "</td>";
                        echo "<td>
                                <a href='DeleteFactura.php?id=" . $fila['idFactura'] . "' class='accion'><i class='fas fa-trash-alt'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay datos disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "&search=" . htmlspecialchars($search_query) . "'>&laquo; Anterior</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i&search=" . htmlspecialchars($search_query) . "'>" . $i . "</a>";
            }

            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "&search=" . htmlspecialchars($search_query) . "'>Siguiente &raquo;</a>";
            }
            ?>
        </div>

        <div class="buttons">
            <button type="button" onclick="location.href='GenerarFactura.php'">Registrar Factura</button>
        </div>
    </div>
</body>
</html>
