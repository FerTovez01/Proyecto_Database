<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variables
    $primerNombre = $conexion->real_escape_string($_POST['PrimerNombre']);
    $segundoNombre = $conexion->real_escape_string($_POST['SegundoNombre']);
    $primerApellido = $conexion->real_escape_string($_POST['PrimerApellido']);
    $segundoApellido = $conexion->real_escape_string($_POST['SegundoApellido']);
    $telefono = $conexion->real_escape_string($_POST['Telefono']);
    $correo = $conexion->real_escape_string($_POST['email']);
    $departamento = $conexion->real_escape_string($_POST['Departamento']);
    $municipio = $conexion->real_escape_string($_POST['Municipio']);
    $colonia = $conexion->real_escape_string($_POST['colonia']);
    $calle = $conexion->real_escape_string($_POST['calle']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Insertar persona
        $sqlPersona = "INSERT INTO Persona (primer_Nombre, segundo_Nombre, primer_Apellido, segundo_Apellido, Correo) 
                       VALUES ('$primerNombre', '$segundoNombre', '$primerApellido', '$segundoApellido', '$correo')";
        if ($conexion->query($sqlPersona) === TRUE) {
            $idPersona = $conexion->insert_id;

            // Insertar teléfono relacionado con la persona
            $sqlTelefono = "INSERT INTO Telefono (Numero, Persona_idPersona) VALUES ('$telefono', '$idPersona')";
            if ($conexion->query($sqlTelefono) === TRUE) {
                // Insertar dirección relacionada con la persona
                $sqlDireccion = "INSERT INTO direccion_persona (Calle, Colonia_barrio, Municipio, Departamento, Persona_idPersona) 
                                 VALUES ('$calle', '$colonia', '$municipio', '$departamento', '$idPersona')";
                if ($conexion->query($sqlDireccion) === TRUE) {
                    // Insertar cliente
                    $fechaRegistro = date("Y-m-d H:i:s");
                    $sqlCliente = "INSERT INTO Cliente (Persona_idPersona, Fecha_Registro) 
                                   VALUES ('$idPersona', '$fechaRegistro')";
                    if ($conexion->query($sqlCliente) === TRUE) {
                        // Confirmar transacción
                        $conexion->commit();
                        $successMessage = "Cliente registrado exitosamente.";
                    } else {
                        throw new Exception("Error al registrar cliente: " . $conexion->error);
                    }
                } else {
                    throw new Exception("Error al registrar dirección: " . $conexion->error);
                }
            } else {
                throw new Exception("Error al registrar teléfono: " . $conexion->error);
            }
        } else {
            throw new Exception("Error al registrar persona: " . $conexion->error);
        }
    } catch (Exception $e) {
        // Revertir todas las operaciones si ocurre un error
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
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
        /* Estilos para el mensaje emergente */
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
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Gestión de Clientes</h1>
        <form method="POST" action="GestionCliente.php">
            <div class="column">
                <label for="PrimerNombre">Primer Nombre:</label>
                <input type="text" id="PrimerNombre" name="PrimerNombre" pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" required>

                <label for="PrimerApellido">Primer Apellido:</label>
                <input type="text" id="PrimerApellido" name="PrimerApellido" pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" required>

                <label for="Departamento">Departamento:</label>
                <input type="text" id="Departamento" name="Departamento" required>

                <label for="Municipio">Municipio:</label>
                <input type="text" id="Municipio" name="Municipio" required>

                <label for="Telefono">Teléfono:</label>
                <input type="tel" id="Telefono" name="Telefono" pattern="[0-9]{8,10}" required>
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
                <button type="button" onclick="location.href='MenuBotones.php'">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>

        <!-- Mensaje de éxito o error -->
        <div id="messageBox" class="message-box">
            <?php
            if (isset($successMessage)) {
                echo $successMessage;
            } elseif (isset($errorMessage)) {
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
