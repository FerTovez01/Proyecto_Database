<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Incluir archivo de conexión

// Número de resultados por página
$results_per_page = 5; 

// Determinar la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Implementación de búsqueda
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = $search_query ? "WHERE Nombre_Marca LIKE '%$search_query%' OR Descripcion LIKE '%$search_query%'" : '';

// Consulta SQL para obtener las marcas registradas con paginación
$sql = "SELECT idMarca, Nombre_Marca, Descripcion FROM Marca $where_clause LIMIT $start_from, $results_per_page"; 
$resultado = $conexion->query($sql);

// Consulta para contar el total de resultados
$total_result_sql = "SELECT COUNT(*) FROM Marca $where_clause";
$total_result = $conexion->query($total_result_sql)->fetch_row()[0];
$total_pages = ceil($total_result / $results_per_page);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Marcas</title>
    <link rel="stylesheet" href="styles3.css">
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
        <h1>Lista de Marcas</h1>
        
        <!-- Formulario de búsqueda -->
        <form method="get" action="">
            <input type="text" name="search" placeholder="Buscar marca..." value="<?= htmlspecialchars($search_query) ?>" style="padding: 5px; font-size: 14px;">
            <button type="submit" style="padding: 5px 10px; font-size: 14px; background-color: #833576; color: white; border: none;">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Marca</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultado->num_rows > 0) {
                    while ($marca = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $marca['idMarca'] . "</td>";
                        echo "<td>" . $marca['Nombre_Marca'] . "</td>";
                        echo "<td>" . $marca['Descripcion'] . "</td>";
                        echo "<td>
                                <a href='UpdateMarca.php?idMarca=" . $marca['idMarca'] . "' title='Editar' style='color: #833576;'>
                                    <i class='fas fa-edit' style='font-size: 20px;'></i>
                                </a>
                                <a href='DeleteMarca.php?idMarca=" . $marca['idMarca'] . "' title='Eliminar' style='color: #833576;'>
                                    <i class='fas fa-trash-alt' style='font-size: 20px;'></i>
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron marcas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="pagination">
            <?php
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "&search=" . htmlspecialchars($search_query) . "' class='page-link'>&laquo; Anterior</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i&search=" . htmlspecialchars($search_query) . "' class='page-link'>" . $i . "</a>";
            }

            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "&search=" . htmlspecialchars($search_query) . "' class='page-link'>Siguiente &raquo;</a>";
            }
            ?>
        </div>

        <div class="buttons">
            <button type="button" onclick="location.href='GestionMarca.php'">Registrar Marca</button>
        </div>
    </div>

    <style>
        /* Estilos generales */
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
            background-color: white; /* Fondo blanco para la caja principal */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #833576;
            text-align: center;
        }

        /* Estilos de la tabla */
        .marca-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .marca-table th, .marca-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            background-color: white; /* Fondo blanco para las celdas */
        }

        .marca-table th {
            background-color: #f2f2f2;
            color: #833576;
        }

        /* Estilos de los botones de búsqueda */
        .search-input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            background-color: white; /* Fondo blanco para el campo de búsqueda */
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

        /* Estilos de la paginación */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            margin: 0 5px;
            padding: 8px 16px;
            background-color: #833576;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination .page-link:hover {
            background-color: #722c60;
        }

        /* Estilos de los botones de acción */
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
    </style></body>
</html>
