<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

$mensajeExito = ''; // Variable para manejar el mensaje
$mensajeError = '';  // Variable para manejar el mensaje de error

if (isset($_GET['id'])) {
    $idCliente = intval($_GET['id']); // Convertir a entero para mayor seguridad

    // Consulta SQL para obtener los datos del cliente
    $sql = "SELECT Cliente.idCliente, Cliente.Fecha_Registro, 
                   Persona.idPersona, Persona.primer_Nombre, Persona.segundo_Nombre, 
                   Persona.primer_Apellido, Persona.segundo_Apellido, Persona.Correo, 
                   Telefono.idTelefono, Telefono.Numero AS Telefono,
                   Direccion_Persona.idDireccion_Persona, Direccion_Persona.Departamento, 
                   Direccion_Persona.Municipio, Direccion_Persona.Colonia_barrio AS Colonia, 
                   Direccion_Persona.Calle
            FROM Cliente
            INNER JOIN Persona ON Cliente.Persona_idPersona = Persona.idPersona
            INNER JOIN Telefono ON Persona.idPersona = Telefono.Persona_idPersona
            INNER JOIN Direccion_Persona ON Persona.idPersona = Direccion_Persona.Persona_idPersona
            WHERE Cliente.idCliente = $idCliente";

    $resultado = $conexion->query($sql);
    
    if ($resultado && $resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
    } else {
        die("Cliente no encontrado.");
    }
} else {
    die("ID no proporcionado.");
}

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar datos del formulario
    $primerNombre = $conexion->real_escape_string($_POST['primerNombre']);
    $segundoNombre = $conexion->real_escape_string($_POST['segundoNombre']);
    $primerApellido = $conexion->real_escape_string($_POST['primerApellido']);
    $segundoApellido = $conexion->real_escape_string($_POST['segundoApellido']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $telefono = $conexion->real_escape_string($_POST['telefono']);
    $departamento = $conexion->real_escape_string($_POST['departamento']);
    $municipio = $conexion->real_escape_string($_POST['municipio']);
    $colonia = $conexion->real_escape_string($_POST['colonia']);
    $calle = $conexion->real_escape_string($_POST['calle']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Actualizar Persona
        $sqlPersona = "UPDATE Persona SET 
                        primer_Nombre = '$primerNombre', 
                        segundo_Nombre = '$segundoNombre', 
                        primer_Apellido = '$primerApellido', 
                        segundo_Apellido = '$segundoApellido', 
                        Correo = '$correo'
                      WHERE idPersona = {$cliente['idPersona']}";
        $conexion->query($sqlPersona);

        // Actualizar Teléfono
        $sqlTelefono = "UPDATE Telefono SET 
                        Numero = '$telefono' 
                      WHERE Persona_idPersona = {$cliente['idPersona']}";
        $conexion->query($sqlTelefono);

        // Actualizar Dirección
        $sqlDireccion = "UPDATE Direccion_Persona SET 
                        Departamento = '$departamento', 
                        Municipio = '$municipio', 
                        Colonia_barrio = '$colonia', 
                        Calle = '$calle'
                      WHERE Persona_idPersona = {$cliente['idPersona']}";
        $conexion->query($sqlDireccion);

        // Confirmar transacción
        $conexion->commit();
        $mensajeExito = "Cliente actualizado exitosamente."; // Mensaje de éxito
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        $mensajeError = "Error al actualizar el cliente: " . $e->getMessage(); // Mensaje de error
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

    <style>
        /* Estilo del mensaje flotante */
        .message-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #833576;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            z-index: 9999;
        }

        .message-box.error {
            background-color: #dc3545;
        }

        .message-box.show {
            display: block;
            opacity: 1;
        }
    </style>
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

    <!-- Mensaje de éxito o error -->
    <div id="messageBox" class="message-box <?php echo $mensajeError ? 'error' : ''; ?>">
        <?php
        if ($mensajeExito) {
            echo $mensajeExito;
        } elseif ($mensajeError) {
            echo $mensajeError;
        }
        ?>
    </div>

    <script>
        // Mostrar el mensaje emergente
        window.onload = function() {
            var messageBox = document.getElementById('messageBox');
            if (messageBox.innerText.trim() !== "") {
                messageBox.classList.add('show');
                setTimeout(function() {
                    messageBox.classList.remove('show');
                }, 3000); // El mensaje se desvanece después de 3 segundos
            }
        };
    </script>
</body>
</html>
