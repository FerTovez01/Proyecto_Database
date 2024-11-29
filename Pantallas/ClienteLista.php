<?php
// Incluir el archivo de conexión
include '../php/conexion.php'; // Ajusta la ruta según la ubicación de 'conexion.php'

// Consulta SQL para obtener los datos de los clientes
$sql = "SELECT Cliente.idCliente, Cliente.Fecha_Registro, 
               Persona.primer_Nombre, Persona.segundo_Nombre, Persona.primer_Apellido, 
               Persona.segundo_Apellido, Persona.Telefono_idTelefono, Persona.Correo,
               Persona.Direccion_Persona_idDireccion_Persona
        FROM Cliente
        INNER JOIN Persona ON Cliente.Persona_idPersona = Persona.idPersona";

// Verificar si la conexión fue exitosa
if ($conexion) {
    // Ejecutar la consulta
    $resultado = $conexion->query($sql);

    // Verificar si la consulta devolvió resultados
    if (!$resultado) {
        die("Error al ejecutar la consulta: " . $conexion->error); // Si hay un error en la consulta, lo mostramos
    }
} else {
    die("Error de conexión: " . $conexion->connect_error); // Mostrar error si no hay conexión
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="styles3.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf.png" alt="Logo">
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
                    <th>Teléfono</th>
                    <th>Correo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si la consulta devuelve resultados
                if ($resultado && $resultado->num_rows > 0) {
                    // Recorrer los resultados y mostrarlos en la tabla
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["idCliente"] . "</td>
                                <td>" . $row["primer_Nombre"] . " " . $row["segundo_Nombre"] . "</td>
                                <td>" . $row["primer_Apellido"] . " " . $row["segundo_Apellido"] . "</td>
                                <td>" . $row["Telefono_idTelefono"] . "</td>
                                <td>" . $row["Correo"] . "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay clientes registrados.</td></tr>";
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
// Cerrar la conexión solo si está establecida
if ($conexion) {
    $conexion->close();
}
?>
