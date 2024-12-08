<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Asegúrate de que el archivo de conexión esté en la ubicación correcta

// Configuración de paginación
$limit = 5; // Número de categorías por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Búsqueda
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Consulta SQL para obtener las categorías con paginación y búsqueda
$sql = "SELECT * FROM Categoria WHERE Nombre_Categoria LIKE '%$search_query%' LIMIT $limit OFFSET $offset";
$resultado = $conexion->query($sql);

// Consulta para contar el total de categorías (para la paginación)
$total_result_sql = "SELECT COUNT(*) as total FROM Categoria WHERE Nombre_Categoria LIKE '%$search_query%'";
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
    <title>Lista de Categorías</title>
    <link rel="stylesheet" href="styles3.css">
    <!-- Incluir Font Awesome para los iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #833576;
        }

        /* Estilos de la tabla */
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

        /* Estilos de los iconos de acción */
        .acciones a {
            color: purple;
            margin-right: 10px;
            text-decoration: none;
        }

        .acciones a:hover {
            color: darkviolet;
        }

        /* Estilo del formulario de búsqueda */
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

        /* Estilos de la paginación */
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

        /* Estilos de los botones */
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
        <h1>Lista de Categorías</h1>

        <!-- Formulario de búsqueda -->
        <form method="get" action="" class="search-form">
            <input type="text" name="search" placeholder="Buscar categoría..." value="<?= htmlspecialchars($search_query) ?>" class="search-input">
            <button type="submit" class="search-button">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Categoria</th>
                    <th>Nombres</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica si la consulta tuvo resultados
                if ($resultado->num_rows > 0) {
                    // Recorre cada fila de resultados y muéstralos en la tabla
                    while ($categoria = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $categoria['idCategoria'] . "</td>";
                        echo "<td>" . $categoria['Nombre_Categoria'] . "</td>";
                        echo "<td>" . $categoria['Descripcion'] . "</td>";
                        echo "<td class='acciones'>";
                        echo "<a href='UpdateCategoria.php?id=" . $categoria['idCategoria'] . "'><i class='fas fa-edit'></i></a>";
                        echo "<a href='DeleteCategoria.php?id=" . $categoria['idCategoria'] . "'><i class='fas fa-trash-alt'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay categorías disponibles.</td></tr>";
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
            <button type="button" onclick="location.href='GestionCategoria.php'">Registrar Categoria</button>
        </div>
    </div>
</body>
</html>
