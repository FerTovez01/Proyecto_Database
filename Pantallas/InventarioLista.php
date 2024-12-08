<?php
// Incluir la conexión a la base de datos
include '../php/conexion.php';

// Configuración de paginación
$limit = 5; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Búsqueda
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta SQL para obtener el inventario con paginación y búsqueda
$query = "SELECT i.idInventario, p.Nombre_Producto, 
                 i.Cantidad_Entrada,
                 i.Cantidad_Salida,
                 MAX(mi.Fecha_Moviento) AS Fecha_Registro
          FROM inventario i
          JOIN producto p ON i.Producto_idProducto = p.idProducto
          LEFT JOIN movimiento_inventario mi ON i.idInventario = mi.Inventario_idInventario
          WHERE p.Nombre_Producto LIKE '%$search_query%' 
          GROUP BY i.idInventario, p.Nombre_Producto, i.Cantidad_Entrada, i.Cantidad_Salida
          LIMIT $limit OFFSET $offset";

$result = $conexion->query($query);

// Consulta para contar el total de registros (para la paginación)
$total_result_sql = "SELECT COUNT(DISTINCT i.idInventario) as total
                     FROM inventario i
                     JOIN producto p ON i.Producto_idProducto = p.idProducto
                     WHERE p.Nombre_Producto LIKE '%$search_query%'";
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
    <title>Lista de Inventario</title>
    <link rel="stylesheet" href="styles3.css">
    <!-- Agregar Font Awesome -->
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
        <h1>Lista de Inventario</h1>

        <!-- Formulario de búsqueda -->
        <form method="get" action="" class="search-form">
            <input type="text" name="search" placeholder="Buscar producto..." value="<?= htmlspecialchars($search_query) ?>" class="search-input">
            <button type="submit" class="search-button">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Inventario</th>
                    <th>Producto</th>
                    <th>Cantidad Entrada</th>
                    <th>Cantidad Salida</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si se obtuvieron resultados
                if ($result->num_rows > 0) {
                    // Mostrar cada registro en una fila
                    while ($inventario = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $inventario['idInventario'] . "</td>";
                        echo "<td>" . $inventario['Nombre_Producto'] . "</td>";
                        echo "<td>" . $inventario['Cantidad_Entrada'] . "</td>";
                        echo "<td>" . $inventario['Cantidad_Salida'] . "</td>";
                        echo "<td>" . $inventario['Fecha_Registro'] . "</td>";
                        echo "<td class='acciones'>
                                <a href='UpdateInventario.php?idInventario=" . $inventario['idInventario'] . "'><i class='fas fa-edit'></i></a>
                                <a href='DeleteInventario.php?idInventario=" . $inventario['idInventario'] . "'><i class='fas fa-trash-alt'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron registros de inventario.</td></tr>";
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
            <button type="button" onclick="location.href='GestionInventario.php'">Registrar Inventario</button>
        </div>
    </div>
</body>
</html>
