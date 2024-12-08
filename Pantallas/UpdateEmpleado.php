<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

$mensajeExito = ''; // Variable para manejar el mensaje de éxito
$mensajeError = '';  // Variable para manejar el mensaje de error

if (isset($_GET['id'])) {
    $idEmpleado = intval($_GET['id']); // Convertir a entero para mayor seguridad

    // Consulta SQL para obtener los datos del empleado
    $sql = "SELECT Empleado.idEmpleado, Persona.idPersona, Persona.primer_Nombre, Persona.segundo_Nombre, 
                   Persona.primer_Apellido, Persona.segundo_Apellido, Persona.Correo, 
                   Telefono.idTelefono, Telefono.Numero AS Telefono,
                   Direccion_Persona.idDireccion_Persona, Direccion_Persona.Departamento, 
                   Direccion_Persona.Municipio, Direccion_Persona.Colonia_barrio AS Colonia, 
                   Direccion_Persona.Calle, Cargo.Nombre AS Cargo
            FROM Empleado
            INNER JOIN Persona ON Empleado.Persona_idPersona = Persona.idPersona
            INNER JOIN Telefono ON Persona.idPersona = Telefono.Persona_idPersona
            INNER JOIN Direccion_Persona ON Persona.idPersona = Direccion_Persona.Persona_idPersona
            INNER JOIN Cargo ON Empleado.Cargo_idCargo = Cargo.idCargo
            WHERE Empleado.idEmpleado = $idEmpleado";

    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $empleado = $resultado->fetch_assoc();
    } else {
        die("Empleado no encontrado.");
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
    $cargoId = $conexion->real_escape_string($_POST['cargoId']); // ID del cargo

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
                      WHERE idPersona = {$empleado['idPersona']}";
        $conexion->query($sqlPersona);

        // Actualizar Teléfono
        $sqlTelefono = "UPDATE Telefono SET 
                        Numero = '$telefono' 
                      WHERE Persona_idPersona = {$empleado['idPersona']}";
        $conexion->query($sqlTelefono);

        // Actualizar Dirección
        $sqlDireccion = "UPDATE Direccion_Persona SET 
                        Departamento = '$departamento', 
                        Municipio = '$municipio', 
                        Colonia_barrio = '$colonia', 
                        Calle = '$calle'
                      WHERE Persona_idPersona = {$empleado['idPersona']}";
        $conexion->query($sqlDireccion);

        // Actualizar Cargo
        $sqlCargo = "UPDATE Empleado SET 
                        Cargo_idCargo = '$cargoId'
                      WHERE idEmpleado = $idEmpleado";
        $conexion->query($sqlCargo);

        // Confirmar transacción
        $conexion->commit();
        $mensajeExito = "Empleado actualizado exitosamente."; // Mensaje de éxito
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        $mensajeError = "Error al actualizar el empleado: " . $e->getMessage(); // Mensaje de error
    }
}

// Obtener todos los cargos para mostrarlos en el formulario
$sqlCargos = "SELECT idCargo, Nombre FROM Cargo";
$resultadoCargos = $conexion->query($sqlCargos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="styles5.css">

    <style>
    /* Estilo general del formulario */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

    input[type="submit"], button {
        background-color: #833576;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        width: auto;
    }

    input[type="submit"]:hover, button:hover {
        background-color: #721F57;
    }

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

    /* Estilo para el select del cargo */
    select {
        background-color: #f9f9f9;
        border-color: #ddd;
    }

    select:focus {
        border-color: #833576;
        box-shadow: 0 0 5px rgba(131, 53, 118, 0.5);
    }

    /* Estilo para los botones */
input[type="submit"], button {
    background-color: #833576;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    width: 100%; /* Aseguramos que ambos botones tengan el mismo tamaño */
    box-sizing: border-box; /* Incluye el padding en el tamaño del botón */
}

input[type="submit"]:hover, button:hover {
    background-color: #721F57;
}

button {
    background-color: #28a745;
    margin-top: 10px;
    text-align: center;
    font-weight: bold;
}

button:hover {
    background-color: #218838;
}

</style>
</head>
<body>
    <h1>Editar Empleado</h1>

    <!-- Formulario de edición de empleado -->
    <form action="" method="POST">
        <label for="primerNombre">Primer Nombre:</label>
        <input type="text" id="primerNombre" name="primerNombre" value="<?php echo isset($empleado['primer_Nombre']) ? $empleado['primer_Nombre'] : ''; ?>" required><br>

        <label for="segundoNombre">Segundo Nombre:</label>
        <input type="text" id="segundoNombre" name="segundoNombre" value="<?php echo isset($empleado['segundo_Nombre']) ? $empleado['segundo_Nombre'] : ''; ?>"><br>

        <label for="primerApellido">Primer Apellido:</label>
        <input type="text" id="primerApellido" name="primerApellido" value="<?php echo isset($empleado['primer_Apellido']) ? $empleado['primer_Apellido'] : ''; ?>" required><br>

        <label for="segundoApellido">Segundo Apellido:</label>
        <input type="text" id="segundoApellido" name="segundoApellido" value="<?php echo isset($empleado['segundo_Apellido']) ? $empleado['segundo_Apellido'] : ''; ?>"><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo isset($empleado['Correo']) ? $empleado['Correo'] : ''; ?>" required><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo isset($empleado['Telefono']) ? $empleado['Telefono'] : ''; ?>" required><br>

        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" name="departamento" value="<?php echo isset($empleado['Departamento']) ? $empleado['Departamento'] : ''; ?>" required><br>

        <label for="municipio">Municipio:</label>
        <input type="text" id="municipio" name="municipio" value="<?php echo isset($empleado['Municipio']) ? $empleado['Municipio'] : ''; ?>" required><br>

        <label for="colonia">Colonia:</label>
        <input type="text" id="colonia" name="colonia" value="<?php echo isset($empleado['Colonia']) ? $empleado['Colonia'] : ''; ?>" required><br>

        <label for="calle">Calle:</label>
        <input type="text" id="calle" name="calle" value="<?php echo isset($empleado['Calle']) ? $empleado['Calle'] : ''; ?>" required><br>

        <label for="cargoId">Cargo:</label>
        <select id="cargoId" name="cargoId" required>
            <?php
            while ($row = $resultadoCargos->fetch_assoc()) {
                $selected = ($row['idCargo'] == $empleado['Cargo']) ? 'selected' : '';
                echo "<option value='" . $row['idCargo'] . "' $selected>" . $row['Nombre'] . "</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Guardar Cambios">
        
        <div>
            <button type="button" onclick="location.href='EmpleadoLista.php'">Volver</button>
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