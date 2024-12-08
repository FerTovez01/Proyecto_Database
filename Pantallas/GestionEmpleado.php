<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

// Incluir el archivo de conexión
include '../php/conexion.php';

// Obtener los cargos de la base de datos
$sqlCargos = "SELECT idCargo, Nombre FROM Cargo";
$resultadoCargos = $conexion->query($sqlCargos);

// Verificar si la consulta fue exitosa
if (!$resultadoCargos) {
    die("Error al obtener los cargos: " . $conexion->error);
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $idEmpleado = $_POST['IdEmpleado'];
    $cargoId = $_POST['Cargo']; // Ahora obtenemos el ID del cargo
    $primerNombre = $_POST['PrimerNombre'];
    $segundoNombre = $_POST['SegundoNombre'];
    $primerApellido = $_POST['PrimerApellido'];
    $segundoApellido = $_POST['SegundoApellido'];
    $departamento = $_POST['Departamento'];
    $municipio = $_POST['Municipio'];
    $telefono = $_POST['Telefono'];
    $colonia = $_POST['colonia'];
    $calle = $_POST['calle'];
    $correo = $_POST['email'];

    // Comenzamos la transacción para asegurar que los datos se insertan correctamente en todas las tablas
    $conexion->begin_transaction();

    try {
        // Insertar en la tabla Persona
        $sqlPersona = "INSERT INTO Persona (primer_Nombre, segundo_Nombre, primer_Apellido, segundo_Apellido, Correo) 
                       VALUES ('$primerNombre', '$segundoNombre', '$primerApellido', '$segundoApellido', '$correo')";
        if (!$conexion->query($sqlPersona)) {
            throw new Exception("Error al insertar en la tabla Persona: " . $conexion->error);
        }
        $personaId = $conexion->insert_id;  // Obtener el ID de la persona insertada

        // Insertar en la tabla Empleado con el ID de cargo
        $sqlEmpleado = "INSERT INTO Empleado (idEmpleado, Persona_idPersona, Cargo_idCargo) 
                        VALUES ('$idEmpleado', '$personaId', '$cargoId')";
        if (!$conexion->query($sqlEmpleado)) {
            throw new Exception("Error al insertar en la tabla Empleado: " . $conexion->error);
        }

        // Insertar en la tabla Telefono
        $sqlTelefono = "INSERT INTO Telefono (Numero, Persona_idPersona) 
                        VALUES ('$telefono', '$personaId')";
        if (!$conexion->query($sqlTelefono)) {
            throw new Exception("Error al insertar en la tabla Telefono: " . $conexion->error);
        }

        // Insertar en la tabla direccion_persona
        $sqlDireccion = "INSERT INTO direccion_persona (Calle, Colonia_barrio, Municipio, Departamento, Persona_idPersona) 
                         VALUES ('$calle', '$colonia', '$municipio', '$departamento', '$personaId')";
        if (!$conexion->query($sqlDireccion)) {
            throw new Exception("Error al insertar en la tabla direccion_persona: " . $conexion->error);
        }

        // Confirmar la transacción
        $conexion->commit();
        $successMessage = "Empleado registrado exitosamente!";
    } catch (Exception $e) {
        // Si hubo algún error, deshacer la transacción
        $conexion->rollback();
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleado</title>
    <link rel="stylesheet" href="styles1.css">

    <style>
        /* Estilo para el select */
        select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 16px;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        select:focus {
            border-color: #007bff;
            background-color: #e9f5ff;
            outline: none;
        }

        /* Estilo para las opciones */
        option {
            padding: 10px;
            font-size: 16px;
        }

        select {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Estilo para el mensaje emergente */
        .message-box {
            display: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            transition: opacity 0.3s ease-in-out;
        }

        .message-box.show {
            display: block;
            opacity: 1;
        }

        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

    </style>

</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='EmpleadoLista.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Gestión de Empleado</h1>
        <form method="POST">
            <div class="column">
                <label for="IdEmpleado">ID Empleado:</label>
                <input type="text" id="IdEmpleado" name="IdEmpleado" required>
                
                <label for="Cargo">Cargo:</label>
                <select id="Cargo" name="Cargo" required>
                    <option value="">Seleccione un cargo</option>
                    <?php
                    // Cargar las opciones de cargos desde la base de datos
                    while ($row = $resultadoCargos->fetch_assoc()) {
                        echo "<option value='" . $row['idCargo'] . "'>" . $row['Nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="column">
                <label for="PrimerNombre">Nombres:</label>
                <input type="text" id="PrimerNombre" name="PrimerNombre" required>

                <label for="PrimerApellido">Apellidos:</label>
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
                <input type="text" id="SegundoNombre" name="SegundoNombre">

                <label for="SegundoApellido">Segundo Apellido:</label>
                <input type="text" id="SegundoApellido" name="SegundoApellido">

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

        <!-- Mensaje de éxito o error -->
        <div id="messageBox" class="message-box <?php echo $successMessage ? 'success' : ($errorMessage ? 'error' : ''); ?>">
            <?php
            if ($successMessage) {
                echo $successMessage;
            } elseif ($errorMessage) {
                echo $errorMessage;
            }
            ?>
        </div>
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
