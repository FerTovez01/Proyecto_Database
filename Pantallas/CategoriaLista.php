<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Asegúrate de que el archivo de conexión esté en la ubicación correcta

// Realizar la consulta para obtener las categorías
$sql = "SELECT * FROM Categoria"; // Suponiendo que la tabla se llama Categoria
$resultado = $conexion->query($sql);
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
        .acciones a {
            color: purple; /* Color morado para los iconos */
            margin-right: 10px; /* Espacio entre los iconos */
            text-decoration: none;
        }

        .acciones a:hover {
            color: darkviolet; /* Efecto hover para los iconos */
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
        <table>
            <thead>
                <tr>
                    <th>ID Categoria</th>
                    <th>Nombres</th>
                    <th>Descripción</th>
                    <th>Acciones</th> <!-- Nueva columna de acciones -->
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
                        echo "<a href='UpdateCategoria.php?id=" . $categoria['idCategoria'] . "'><i class='fas fa-edit'></i></a>"; // Icono de editar
                        echo "<a href='DeleteCategoria.php?id=" . $categoria['idCategoria'] . "'><i class='fas fa-trash-alt'></i></a>"; // Icono de eliminar
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay categorías disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" onclick="location.href='GestionCategoria.php'">Registrar Categoria</button>
        </div>
    </div>
</body>
</html>
