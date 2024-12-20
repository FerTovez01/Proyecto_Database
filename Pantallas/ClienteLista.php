<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Consulta SQL para obtener los datos de los clientes
$sql = "SELECT Cliente.idCliente, Cliente.Fecha_Registro, 
               Persona.primer_Nombre, Persona.segundo_Nombre, 
               Persona.primer_Apellido, Persona.segundo_Apellido, 
               Persona.Correo, 
               Telefono.Numero AS Telefono,
               direccion_persona.Departamento, direccion_persona.Municipio, 
               direccion_persona.Colonia_barrio AS Colonia, direccion_persona.Calle
        FROM Cliente
        INNER JOIN Persona ON Cliente.Persona_idPersona = Persona.idPersona
        LEFT JOIN Telefono ON Telefono.Persona_idPersona = Persona.idPersona
        LEFT JOIN direccion_persona ON direccion_persona.Persona_idPersona = Persona.idPersona";

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
    <title>Lista de Clientes</title>
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
        <h1>Lista de Clientes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Colonia</th>
                    <th>Calle</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["idCliente"] . "</td>
                                <td>" . $row["primer_Nombre"] . " " . $row["segundo_Nombre"] . "</td>
                                <td>" . $row["primer_Apellido"] . " " . $row["segundo_Apellido"] . "</td>
                                <td>" . ($row["Departamento"] ?? "N/A") . "</td>
                                <td>" . ($row["Municipio"] ?? "N/A") . "</td>
                                <td>" . ($row["Colonia"] ?? "N/A") . "</td>
                                <td>" . ($row["Calle"] ?? "N/A") . "</td>
                                <td>" . ($row["Telefono"] ?? "N/A") . "</td>
                                <td>" . $row["Correo"] . "</td>
                                <td>
                                    <a href='UpdateCliente.php?id=" . $row['idCliente'] . "' class='accion' style='color: #833576;'>
                                        <i class='fas fa-edit'></i>
                                    </a>  
                                    | 
                                    <a href='DeleteCliente.php?id=" . $row['idCliente'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")' style='color: #833576;'>
                                        <i class='fas fa-trash-alt'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay clientes registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" onclick="location.href='GestionCliente.php'">Registrar Cliente</button>
        </div>
    </div>
</body>
</html>

<?php
if ($conexion) {
    $conexion->close();
}
?>
