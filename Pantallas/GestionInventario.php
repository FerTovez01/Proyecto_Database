<?php
// Incluir el archivo de conexión
include '../php/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
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
        <h1>Gestión de Inventario</h1>
        <form method="POST" action="GestionMarca.php">
            <div class="column">
            <label for="Producto_idProducto">Producto:</label>
        <select name="Producto_idProducto" id="Producto_idProducto" required>
            <option value="">Seleccionar Producto</option>

            </select><br><br>

<label for="Cantidad_Entrada">Cantidad Entrada:</label>
<input type="number" name="Cantidad_Entrada" id="Cantidad_Entrada" required><br><br>

<label for="Cantidad_Salida">Cantidad Salida:</label>
<input type="number" name="Cantidad_Salida" id="Cantidad_Salida" required><br><br>

<label for="Fecha_Registro">Fecha Registro:</label>
<input type="date" name="Fecha_Registro" id="Fecha_Registro" required><br><br>

<button type="submit">Registrar Inventario</button>

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
