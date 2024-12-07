<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Variable para el mensaje de estado
$message = '';
$message_type = '';

if (isset($_GET['id'])) {
    $idFactura = $_GET['id'];

    // Iniciar la transacción
    $conexion->begin_transaction();

    try {
        // Eliminar los productos asociados a la factura (Detalle_Venta)
        $sqlDetalle = "DELETE FROM Detalle_Venta WHERE Venta_idVenta = (SELECT Venta_idVenta FROM Factura WHERE idFactura = ?)";
        $stmtDetalle = $conexion->prepare($sqlDetalle);
        $stmtDetalle->bind_param("i", $idFactura);
        $stmtDetalle->execute();

        // Eliminar la factura (Factura)
        $sqlFactura = "DELETE FROM Factura WHERE idFactura = ?";
        $stmtFactura = $conexion->prepare($sqlFactura);
        $stmtFactura->bind_param("i", $idFactura);
        $stmtFactura->execute();

        // Eliminar la venta relacionada (Venta)
        $sqlVenta = "DELETE FROM Venta WHERE idVenta = (SELECT Venta_idVenta FROM Factura WHERE idFactura = ?)";
        $stmtVenta = $conexion->prepare($sqlVenta);
        $stmtVenta->bind_param("i", $idFactura);
        $stmtVenta->execute();

        // Confirmar los cambios en la base de datos
        $conexion->commit();

        // Mensaje de éxito
        $message = 'Factura eliminada correctamente.';
        $message_type = 'success';
    } catch (Exception $e) {
        // Si hay algún error, revertir los cambios
        $conexion->rollback();
        // Mensaje de error
        $message = 'Error al eliminar la factura: ' . $e->getMessage();
        $message_type = 'error';
    }
} else {
    $message = 'No se ha proporcionado un idFactura.';
    $message_type = 'error';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Factura</title>
    <style>
        .message-box {
            width: 300px;
            padding: 15px;
            color: white;
            background-color: #6a1b9a; /* Morado */
            text-align: center;
            margin: 20px auto;
            border-radius: 5px;
            display: none;
        }

        .message-box.success {
            background-color: #388e3c; /* Verde */
        }

        .message-box.error {
            background-color: #d32f2f; /* Rojo */
        }

        /* Animación para desaparecer el mensaje */
        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }

        .fade-out {
            animation: fadeOut 3s forwards;
        }
    </style>
</head>
<body>
    <!-- Mostrar el mensaje si existe -->
    <?php if ($message): ?>
        <div class="message-box <?php echo $message_type; ?>" id="message-box">
            <?php echo $message; ?>
        </div>

        <script>
            // Mostrar el mensaje y luego ocultarlo después de 3 segundos
            window.onload = function() {
                var messageBox = document.getElementById('message-box');
                messageBox.style.display = 'block';
                messageBox.classList.add('fade-out');

                // Redirigir después de 3 segundos
                setTimeout(function() {
                    window.location.href = 'FacturaLista.php'; // Redirigir a la lista de facturas
                }, 3000);
            }
        </script>
    <?php endif; ?>
</body>
</html>
