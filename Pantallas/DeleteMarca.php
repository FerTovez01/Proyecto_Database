<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se ha recibido el idMarca a través de la URL
if (isset($_GET['idMarca'])) {
    // Escapar el idMarca para evitar inyecciones SQL
    $idMarca = $conexion->real_escape_string($_GET['idMarca']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Consulta para eliminar la marca
        $sql = "DELETE FROM Marca WHERE idMarca = $idMarca";
        if ($conexion->query($sql) === TRUE) {
            // Confirmar transacción
            $conexion->commit();
            $successMessage = "Marca eliminada exitosamente.";
        } else {
            throw new Exception("Error al eliminar la marca: " . $conexion->error);
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        $errorMessage = "Error: " . $e->getMessage();
    }
} else {
    // Si no se pasó el idMarca, mostrar un mensaje de error
    $errorMessage = "ID de marca no proporcionado.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Marca</title>
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
        <button type="button" onclick="location.href='MarcaLista.php'">Volver</button>
    </header>

    <div class="container">
        <h1>Eliminar Marca</h1>
        
        <!-- Mostrar mensaje de error si no se encuentra la marca o el idMarca no está presente -->
        <?php if (isset($errorMessage)): ?>
            <div class="message-box error">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="message-box">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
