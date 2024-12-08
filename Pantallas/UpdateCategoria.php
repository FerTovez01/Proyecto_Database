<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Asegúrate de que el archivo de conexión esté en la ubicación correcta

// Verifica si se ha recibido un ID de categoría a través de GET
if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Realizar la consulta para obtener los datos de la categoría
    $sql = "SELECT * FROM Categoria WHERE idCategoria = $idCategoria";
    $resultado = $conexion->query($sql);

    // Verificar si se encontró la categoría
    if ($resultado->num_rows > 0) {
        $categoria = $resultado->fetch_assoc();
    } else {
        echo "Categoría no encontrada.";
        exit;
    }
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombreCategoria = $_POST['Nombre_Categoria'];
    $descripcion = $_POST['Descripcion'];

    // Actualizar los datos de la categoría
    $sqlUpdate = "UPDATE Categoria SET Nombre_Categoria = '$nombreCategoria', Descripcion = '$descripcion' WHERE idCategoria = $idCategoria";

    if ($conexion->query($sqlUpdate) === TRUE) {
        // Si la categoría se actualiza correctamente, mostrar el mensaje
        $mensaje = "Categoría Actualizada";
    } else {
        $mensaje = "Error al actualizar la categoría: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Categoría</title>
    <link rel="stylesheet" href="styles1.css">

    <style>
        /* Estilo para el mensaje de éxito */
        .mensaje-exito {
            display: none;
            color: white;
            background-color: #800080; /* Color morado */
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }
    </style>

    <script>
        // Función para mostrar el mensaje de éxito y ocultarlo después de 3 segundos
        function mostrarMensaje() {
            var mensaje = document.getElementById('mensaje-exito');
            mensaje.style.display = 'block';
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 3000); // Desaparece después de 3 segundos
        }
    </script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>

    <div class="container">
        <h1>Actualizar Categoría</h1>

        <!-- Mostrar mensaje si la categoría fue actualizada correctamente -->
        <?php if (isset($mensaje)): ?>
            <div id="mensaje-exito" class="mensaje-exito">
                <?php echo $mensaje; ?>
            </div>
            <script>mostrarMensaje();</script>
        <?php endif; ?>

        <form method="POST">
            <div class="column">
                <label for="idCategoria">ID Categoria:</label>
                <input type="text" id="idCategoria" name="idCategoria" value="<?php echo $categoria['idCategoria']; ?>" disabled>
            </div>
            <div class="column">
                <label for="Nombre_Categoria">Nombre:</label>
                <input type="text" id="Nombre_Categoria" name="Nombre_Categoria" value="<?php echo $categoria['Nombre_Categoria']; ?>" required>
            </div>
            <div class="column">
                <label for="Descripcion">Descripción:</label>
                <input type="text" id="Descripcion" name="Descripcion" value="<?php echo $categoria['Descripcion']; ?>" required>
            </div>
            <div class="buttons">
                <button type="reset">Cancelar</button>
                <button type="submit">Actualizar</button>
            </div>
        </form>
    </div>
</body>
</html>
