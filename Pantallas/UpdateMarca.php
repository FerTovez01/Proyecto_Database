<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se recibió el ID de la marca
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idMarca'])) {
    // Variables
    $idMarca = $conexion->real_escape_string($_POST['idMarca']);
    $nombreMarca = $conexion->real_escape_string($_POST['Nombre_Marca']);
    $descripcion = $conexion->real_escape_string($_POST['Descripcion']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Actualizar marca
        $sqlMarca = "UPDATE Marca SET Nombre_Marca = '$nombreMarca', Descripcion = '$descripcion' WHERE idMarca = $idMarca";
        if ($conexion->query($sqlMarca) === TRUE) {
            // Confirmar transacción
            $conexion->commit();
            $successMessage = "Marca actualizada exitosamente.";
        } else {
            throw new Exception("Error al actualizar marca: " . $conexion->error);
        }
    } catch (Exception $e) {
        // Revertir todas las operaciones si ocurre un error
        $conexion->rollback();
        $errorMessage = "Error: " . $e->getMessage();
    }
} elseif (isset($_GET['idMarca'])) {
    // Obtener el idMarca de la URL
    $idMarca = $conexion->real_escape_string($_GET['idMarca']);

    // Verificar si el idMarca está presente y obtener los datos de la marca
    $sql = "SELECT * FROM Marca WHERE idMarca = $idMarca";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $marca = $resultado->fetch_assoc();
    } else {
        // Si no se encuentra la marca, mostrar mensaje de error
        $errorMessage = "Marca no encontrada.";
    }
} else {
    // Si no se pasa el idMarca, mostrar mensaje de error
    $errorMessage = "ID de marca no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Marca</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
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
        <h1>Editar Marca</h1>
        
        <!-- Mostrar mensaje de error si no se encuentra la marca o el idMarca no está presente -->
        <?php if (isset($errorMessage)): ?>
            <div class="message-box error">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($marca)): ?>
            <!-- Si se encuentra la marca, mostrar el formulario -->
            <form method="POST" action="UpdateMarca.php">
                <input type="hidden" name="idMarca" value="<?php echo $marca['idMarca']; ?>">

                <div class="column">
                    <label for="Nombre_Marca">Nombre de la Marca:</label>
                    <input type="text" id="Nombre_Marca" name="Nombre_Marca" value="<?php echo $marca['Nombre_Marca']; ?>" required>

                    <label for="Descripcion">Descripción:</label>
                    <input type="text" id="Descripcion" name="Descripcion" value="<?php echo $marca['Descripcion']; ?>" required>
                </div>
                <div class="buttons">
                    <button type="button" onclick="location.href='MenuBotones.php'">Cancelar</button>
                    <button type="submit">Actualizar</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Mensaje de éxito o error después de la actualización -->
        <div id="messageBox" class="message-box">
            <?php
            if (isset($successMessage)) {
                echo $successMessage;
            }
            ?>
        </div>
    </div>

    <script>
        window.onload = function() {
            var messageBox = document.getElementById('messageBox');
            if (messageBox.innerText.trim() !== "") {
                messageBox.classList.add('show');
                setTimeout(function() {
                    messageBox.classList.remove('show');
                }, 3000); // Desaparece después de 3 segundos
            }
        };
    </script>
</body>
</html>
