<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Verificar si existe el parámetro 'id'
if (isset($_GET['id'])) {
    $idCliente = $_GET['id'];
    
    // Consulta SQL para obtener los datos del cliente
    $sql = "SELECT Cliente.idCliente, Cliente.Fecha_Registro, 
                   Persona.primer_Nombre, Persona.segundo_Nombre, 
                   Persona.primer_Apellido, Persona.segundo_Apellido, Persona.Correo, 
                   Telefono.Numero AS Telefono,
                   Direccion_Persona.Departamento, Direccion_Persona.Municipio, 
                   Direccion_Persona.Colonia_barrio AS Colonia, Direccion_Persona.Calle
            FROM Cliente
            INNER JOIN Persona ON Cliente.Persona_idPersona = Persona.idPersona
            INNER JOIN Telefono ON Persona.Telefono_idTelefono = Telefono.idTelefono
            INNER JOIN Direccion_Persona ON Persona.Direccion_Persona_idDireccion_Persona = direccion_persona.idDireccion_Persona
            WHERE Cliente.idCliente = $idCliente";

    $resultado = $conexion->query($sql);
    
    if ($resultado && $resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
    } else {
        echo "Cliente no encontrado.";
        exit;
    }
} else {
    echo "ID no proporcionado.";
    exit;
}

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $primerNombre = $_POST['primerNombre'];
    $segundoNombre = $_POST['segundoNombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = $_POST['segundoApellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $departamento = $_POST['departamento'];
    $municipio = $_POST['municipio'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];

    // Actualizar los datos en la base de datos
    $updatePersona = "UPDATE Persona SET 
                        primer_Nombre = '$primerNombre', 
                        segundo_Nombre = '$segundoNombre', 
                        primer_Apellido = '$primerApellido', 
                        segundo_Apellido = '$segundoApellido', 
                        Correo = '$correo'
                    WHERE idPersona = (SELECT Persona_idPersona FROM Cliente WHERE idCliente = $idCliente)";

    $updateTelefono = "UPDATE Telefono SET 
                        Numero = '$telefono' 
                      WHERE idTelefono = (SELECT Telefono_idTelefono FROM Persona WHERE idPersona = (SELECT Persona_idPersona FROM Cliente WHERE idCliente = $idCliente))";

    $updateDireccion = "UPDATE direccion_persona SET 
                        Departamento = '$departamento', 
                        Municipio = '$municipio', 
                        Colonia_barrio = '$colonia', 
                        Calle = '$calle'
                    WHERE idDireccion_Persona = (SELECT Direccion_Persona_idDireccion_Persona FROM Persona WHERE idPersona = (SELECT Persona_idPersona FROM Cliente WHERE idCliente = $idCliente))";

    // Ejecutar las consultas de actualización
    if ($conexion->query($updatePersona) && $conexion->query($updateTelefono) && $conexion->query($updateDireccion)) {
        echo "Cliente actualizado exitosamente.";
    } else {
        echo "Error al actualizar el cliente: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles5.css">
    
</head>
<body>
    <h1>Editar Cliente</h1>

    
    <!-- Formulario de edición de cliente -->
    <form action="" method="POST">
        <label for="primerNombre">Primer Nombre:</label>
        <input type="text" id="primerNombre" name="primerNombre" value="<?php echo isset($cliente['primer_Nombre']) ? $cliente['primer_Nombre'] : ''; ?>" required><br>

        <label for="segundoNombre">Segundo Nombre:</label>
        <input type="text" id="segundoNombre" name="segundoNombre" value="<?php echo isset($cliente['segundo_Nombre']) ? $cliente['segundo_Nombre'] : ''; ?>"><br>

        <label for="primerApellido">Primer Apellido:</label>
        <input type="text" id="primerApellido" name="primerApellido" value="<?php echo isset($cliente['primer_Apellido']) ? $cliente['primer_Apellido'] : ''; ?>" required><br>

        <label for="segundoApellido">Segundo Apellido:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" value="<?php echo isset($cliente['segundo_Apellido']) ? $cliente['segundo_Apellido'] : ''; ?>"><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo isset($cliente['Correo']) ? $cliente['Correo'] : ''; ?>" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo isset($cliente['Telefono']) ? $cliente['Telefono'] : ''; ?>" required><br>

        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento" value="<?php echo isset($cliente['Departamento']) ? $cliente['Departamento'] : ''; ?>" required><br>

        <label for="municipio">Municipio:</label>
        <input type="text" id="municipio" name="municipio" value="<?php echo isset($cliente['Municipio']) ? $cliente['Municipio'] : ''; ?>" required><br>

        <label for="colonia">Colonia:</label>
        <input type="text" id="colonia" name="colonia" value="<?php echo isset($cliente['Colonia']) ? $cliente['Colonia'] : ''; ?>" required><br>

        <label for="calle">Calle:</label>
        <input type="text" id="calle" name="calle" value="<?php echo isset($cliente['Calle']) ? $cliente['Calle'] : ''; ?>" required><br>

        <input type="submit" value="Guardar Cambios">
        
       <div>
        <button type="button" onclick="location.href='ClienteLista.php'">Volver</button>
</div>
    </form>
    
</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>

