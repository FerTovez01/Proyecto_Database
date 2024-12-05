<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idCategoria = $_POST['idCategoria'];
    $nombreCategoria = $_POST['Nombre_Categoria'];
    $descripcion = $_POST['Descripcion'];

    // Validación simple
    if (!empty($idCategoria) && !empty($nombreCategoria) && !empty($descripcion)) {
        // Verificar si el idCategoria ya existe en la base de datos
        $sqlCheck = "SELECT idCategoria FROM Categoria WHERE idCategoria = ?";
        if ($stmtCheck = $conexion->prepare($sqlCheck)) {
            $stmtCheck->bind_param("i", $idCategoria); // 'i' indica que el parámetro es un entero
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "El ID de categoría ya existe. Por favor, ingrese un ID diferente.";
            } else {
                // Si no existe, proceder con la inserción
                $sql = "INSERT INTO Categoria (idCategoria, Nombre_Categoria, Descripcion) VALUES (?, ?, ?)";
                if ($stmt = $conexion->prepare($sql)) {
                    $stmt->bind_param("iss", $idCategoria, $nombreCategoria, $descripcion); // 'iss' indica que el primer parámetro es entero y los otros dos son cadenas
                    if ($stmt->execute()) {
                        echo "<script>alert('Categoría registrada exitosamente.');</script>";
                        echo "<script>window.location.href='CategoriaLista.php';</script>";
                        exit();
                    } else {
                        echo "Error al registrar la categoría: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $stmtCheck->close();
        } else {
            echo "Error al verificar el ID de categoría: " . $conexion->error;
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categoría</title>
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
        <h1>Gestión de Categoría</h1>
        <form action="GestionCategoria.php" method="POST">
            <div class="column">
                <label for="idCategoria">ID Categoria:</label>
                <input type="text" id="idCategoria" name="idCategoria" required>
            </div>
            <div class="column">
                <label for="Nombre_Categoria">Nombre:</label>
                <input type="text" id="Nombre_Categoria" name="Nombre_Categoria" required>
            </div>
            <div class="column">
                <label for="Descripcion">Descripción:</label>
                <input type="text" id="Descripcion" name="Descripcion" required>
            </div>
            <div class="buttons">
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>
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

