<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Incluir archivo de conexión

// Consulta SQL para obtener las marcas registradas
$sql = "SELECT idMarca, Nombre_Marca, Descripcion FROM Marca"; 
$resultado = $conexion->query($sql); // Ejecutar la consulta

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Marcas</title>
    <link rel="stylesheet" href="styles3.css">
    <!-- Agregar Font Awesome -->
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
        <table>
            <thead>
                <tr>
                    <th>ID Marca</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th> <!-- Nueva columna para las acciones -->
                </tr>
            </thead>
            <tbody>
                <?php 
                // Verificar si se obtuvieron resultados de la consulta
                if ($resultado->num_rows > 0) {
                    // Mostrar cada marca en una fila
                    while ($marca = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $marca['idMarca'] . "</td>";
                        echo "<td>" . $marca['Nombre_Marca'] . "</td>";
                        echo "<td>" . $marca['Descripcion'] . "</td>";
                        // Columna de acciones con enlaces para editar y eliminar utilizando Font Awesome
                        echo "<td>
                                <a href='UpdateMarca.php?idMarca=" . $marca['idMarca'] . "' title='' style='color: #833576;'>
                                    <i class='fas fa-edit' style='font-size: 20px;'></i>
                                </a>
                                <a href='DeleteMarca.php?idMarca=" . $marca['idMarca'] . "' title='Eliminar'style='color: #833576;' >
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
        <div class="buttons">
            <button type="button" onclick="location.href='GestionMarca.php'">Registrar Marca</button>
        </div>
    </div>
</body>
</html>
