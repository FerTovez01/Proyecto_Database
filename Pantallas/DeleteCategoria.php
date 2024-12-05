<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Asegúrate de que el archivo de conexión esté en la ubicación correcta

// Verificar si se ha recibido un ID de categoría
if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Eliminar la categoría de la base de datos
    $sql = "DELETE FROM Categoria WHERE idCategoria = $idCategoria";
    if ($conexion->query($sql) === TRUE) {
        // Si la categoría se eliminó correctamente
        $mensaje = "Categoría eliminada exitosamente";
    } else {
        // Si hubo un error al eliminar la categoría
        $mensaje = "Error al eliminar la categoría: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Categoría</title>
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
        <h1>Eliminar Categoría</h1>

        <!-- Mostrar mensaje si la categoría fue eliminada correctamente -->
        <?php if (isset($mensaje)): ?>
            <div id="mensaje-exito" class="mensaje-exito">
                <?php echo $mensaje; ?>
            </div>
            <script>mostrarMensaje();</script>
        <?php endif; ?>

        <!-- Redirigir al usuario después de la eliminación -->
        <div class="buttons">
            <button type="button" onclick="location.href='CategoriaLista.php'">Volver a la lista</button>
        </div>
    </div>
</body>
</html>
