<?php 
// Incluir la conexión a la base de datos
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $primerNombre = $_POST['PrimerNombre'];
    $segundoNombre = $_POST['SegundoNombre'];
    $primerApellido = $_POST['PrimerApellido'];
    $segundoApellido = $_POST['SegundoApellido'];
    $telefono = $_POST['Telefono'];
    $correo = $_POST['email'];
    $departamento = $_POST['Departamento'];
    $municipio = $_POST['Municipio'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];
    
    // Registrar teléfono en la tabla `Telefono`
    $sqlTelefono = "INSERT INTO Telefono (Numero) VALUES ('$telefono')";
    if ($conexion->query($sqlTelefono) === TRUE) {
        // Obtener el id del teléfono insertado
        $telefonoId = $conexion->insert_id;

        // Registrar dirección en la tabla `direccion_persona`
        $sqlDireccion = "INSERT INTO direccion_persona (Calle, Colonia_barrio, Municipio, Departamento) 
                         VALUES ('$calle', '$colonia', '$municipio', '$departamento')";
        if ($conexion->query($sqlDireccion) === TRUE) {
            // Obtener el id de la dirección insertada
            $direccionId = $conexion->insert_id;

            // Registrar persona en la tabla `Persona`
            $sqlPersona = "INSERT INTO Persona (primer_Nombre, segundo_Nombre, primer_Apellido, segundo_Apellido, Telefono_idTelefono, Correo, Direccion_Persona_idDireccion_Persona) 
                           VALUES ('$primerNombre', '$segundoNombre', '$primerApellido', '$segundoApellido', '$telefonoId', '$correo', '$direccionId')";
            if ($conexion->query($sqlPersona) === TRUE) {
                // Obtener el id de la persona insertada
                $personaId = $conexion->insert_id;

                // Registrar cliente en la tabla `Cliente`
                $fechaRegistro = date("Y-m-d H:i:s"); // Fecha y hora actuales
                $sqlCliente = "INSERT INTO Cliente (Persona_idPersona, Fecha_Registro) 
                               VALUES ('$personaId', '$fechaRegistro')";
                if ($conexion->query($sqlCliente) === TRUE) {
                    echo "";
                } else {
                    echo "Error al registrar cliente: " . $conexion->error;
                }
            } else {
                echo "Error al registrar persona: " . $conexion->error;
            }
        } else {
            echo "Error al registrar dirección: " . $conexion->error;
        }
    } else {
        echo "Error al registrar teléfono: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Gestión de Clientes</h1>
        <form method="POST" action="GestionCliente.php">
            <div class="column">
                <label for="PrimerNombre">Primer Nombre:</label>
                <input type="text" id="PrimerNombre" name="PrimerNombre" required>

                <label for="PrimerApellido">Primer Apellido:</label>
                <input type="text" id="PrimerApellido" name="PrimerApellido" required>

                <label for="Departamento">Departamento:</label>
                <input type="text" id="Departamento" name="Departamento" required>

                <label for="Municipio">Municipio:</label>
                <input type="text" id="Municipio" name="Municipio" required>

                <label for="Telefono">Teléfono:</label>
                <input type="tel" id="Telefono" name="Telefono" required>
            </div>
            <div class="column">
                <label for="SegundoNombre">Segundo Nombre:</label>
                <input type="text" id="SegundoNombre" name="SegundoNombre" required>

                <label for="SegundoApellido">Segundo Apellido:</label>
                <input type="text" id="SegundoApellido" name="SegundoApellido" required>

                <label for="colonia">Colonia:</label>
                <input type="text" id="colonia" name="colonia" required>

                <label for="calle">Calle:</label>
                <input type="text" id="calle" name="calle" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="buttons">
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</body>
</html>
