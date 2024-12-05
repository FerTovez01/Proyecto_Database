<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variables
    $nombreMarca = $conexion->real_escape_string($_POST['Nombre_Marca']);
    $descripcion = $conexion->real_escape_string($_POST['Descripcion']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Insertar marca
        $sqlMarca = "INSERT INTO Marca (Nombre_Marca, Descripcion) VALUES ('$nombreMarca', '$descripcion')";
        if ($conexion->query($sqlMarca) === TRUE) {
            // Confirmar transacción
            $conexion->commit();
            $successMessage = "Marca registrada exitosamente.";
        } else {
            throw new Exception("Error al registrar marca: " . $conexion->error);
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
    <title>Gestión de Marcas</title>
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
        <h1>Gestión de Marcas</h1>
        <form method="POST" action="GestionMarca.php">
            <div class="column">
                <label for="Nombre_Marca">Nombre de la Marca:</label>
                <input type="text" id="Nombre_Marca" name="Nombre_Marca" required>

                <label for="Descripcion">Descripción:</label>
                <input type="text" id="Descripcion" name="Descripcion" required>
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
