<?php
ob_start();
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");
set_include_path(get_include_path() . PATH_SEPARATOR . realpath('../vendor/TCPDF-main'));
require_once('tcpdf.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario de forma segura
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $idEmpleado = intval($_POST['idEmpleado']);
    $idCliente = intval($_POST['idCliente']);
    $metodoPago = intval($_POST['idMetodo_Pago']);
    $productos = $_POST['producto_id'] ?? [];
    $cantidades = $_POST['cantidad_prod'] ?? [];
    $precios = $_POST['precio_unitario'] ?? [];

    // Validaciones básicas
    if (empty($productos) || empty($cantidades) || empty($precios)) {
        exit("Error: Debes seleccionar al menos un producto con cantidades y precios.");
    }

    if (count($productos) !== count($cantidades) || count($productos) !== count($precios)) {
        exit("Error: La cantidad de productos, cantidades y precios no coincide.");
    }

    // Validar que el método de pago existe
    $consultaMetodoPago = "SELECT idMetodo_Pago FROM Metodo_Pago WHERE idMetodo_Pago = ?";
    $stmt = mysqli_prepare($conexion, $consultaMetodoPago);
    mysqli_stmt_bind_param($stmt, 'i', $metodoPago);
    mysqli_stmt_execute($stmt);
    $resultadoMetodoPago = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultadoMetodoPago) === 0) {
        exit("Error: El método de pago seleccionado no existe.");
    }

    // Insertar la venta
    $insertVenta = "INSERT INTO Venta (Cliente_idCliente, Fecha_venta, Total, Metodo_Pago_idMetodo_Pago, Estado_Pedido, Empleado_idEmpleado)
                    VALUES (?, ?, 0, ?, 'Pendiente', ?)";
    $stmt = mysqli_prepare($conexion, $insertVenta);
    mysqli_stmt_bind_param($stmt, 'isii', $idCliente, $fecha, $metodoPago, $idEmpleado);

    if (!mysqli_stmt_execute($stmt)) {
        exit("Error al registrar la venta: " . mysqli_error($conexion));
    }

    $idVenta = mysqli_insert_id($conexion);
    $totalVenta = 0;

    // Insertar detalles de venta
    foreach ($productos as $index => $productoId) {
        $cantidad = floatval($cantidades[$index]);
        $precio = floatval($precios[$index]);

        // Validar que cantidad y precio sean válidos
        if ($cantidad <= 0 || $precio <= 0) {
            exit("Error: Cantidad o precio inválidos para el producto ID " . $productoId);
        }

        $subtotal = $cantidad * $precio;
        $totalVenta += $subtotal;

        $insertDetalleVenta = "INSERT INTO Detalle_Venta (Venta_idVenta, Producto_idProducto, Cantidad, Precio_Unitario, Subtotal)
                                VALUES (?, ?, ?, ?, ?)";
        $stmtDetalle = mysqli_prepare($conexion, $insertDetalleVenta);
        mysqli_stmt_bind_param($stmtDetalle, 'iiidd', $idVenta, $productoId, $cantidad, $precio, $subtotal);

        if (!mysqli_stmt_execute($stmtDetalle)) {
            exit("Error al insertar detalle de venta: " . mysqli_error($conexion));
        }
    }

    // Actualizar total de la venta
    $updateVenta = "UPDATE Venta SET Total = ? WHERE idVenta = ?";
    $stmtUpdate = mysqli_prepare($conexion, $updateVenta);
    mysqli_stmt_bind_param($stmtUpdate, 'di', $totalVenta, $idVenta);
    if (!mysqli_stmt_execute($stmtUpdate)) {
        exit("Error al actualizar el total de la venta: " . mysqli_error($conexion));
    }

   // Asegúrate de tener el valor de Empleado_idEmpleado
$empleado_id = 1;  // Este valor debe ser un ID de empleado existente

// Insertar factura
$insertFactura = "INSERT INTO Factura (Venta_idVenta, Fecha_Emision, Monto_total, Empleado_idEmpleado)
                  VALUES (?, ?, ?, ?)";
$stmtFactura = mysqli_prepare($conexion, $insertFactura);

// Vincula los parámetros a la sentencia preparada
mysqli_stmt_bind_param($stmtFactura, 'isdi', $idVenta, $fecha, $totalVenta, $empleado_id);

// Ejecuta la sentencia
if (!mysqli_stmt_execute($stmtFactura)) {
    exit("Error al generar la factura: " . mysqli_error($conexion));
}

    // Generar PDF
    generarPDF($idVenta, $fecha, $idEmpleado, $idCliente, $metodoPago, $productos, $cantidades, $precios);
}

function generarPDF($idVenta, $fecha, $idEmpleado, $idCliente, $metodoPago, $productos, $cantidades, $precios) {
    // Crear instancia de TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Cargar el CSS (asegúrate de que la ruta al archivo sea correcta)
    $css = file_get_contents('styles7.css');
    $pdf->SetFont('helvetica', '', 12);

    // Aquí creamos el HTML para el contenido del PDF
    $html = '
    <html>
        <head>
            <style>' . $css . '</style>
        </head>
        <body>
            <header>
                <div class="logo">
                    <img src="../img/bf2.png" alt="Logo" />
                </div>
                <button type="button" onclick="location.href=\'MenuBotones.php\'">Volver</button>
            </header>
            <div class="form-container">
                <h2 class="form-title">Factura</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha:</label>
                        <p>' . $fecha . '</p>
                    </div>
                    <div class="form-group">
                        <label>ID Empleado:</label>
                        <p>' . $idEmpleado . '</p>
                    </div>
                    <div class="form-group">
                        <label>ID Cliente:</label>
                        <p>' . $idCliente . '</p>
                    </div>
                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <p>' . ($metodoPago == '1' ? 'Efectivo' : 'Tarjeta') . '</p>
                    </div>
                </div>';

    // Productos y detalles
    $html .= '<h3>Productos</h3><div class="form-group">';

    $totalFactura = 0;
    foreach ($productos as $index => $productoId) {
        $cantidad = floatval($cantidades[$index]);
        $precio = floatval($precios[$index]);
        $subtotal = $cantidad * $precio;
        $totalFactura += $subtotal;

        $html .= '
            <div class="producto-item">
                <label>ID Producto: ' . $productoId . '</label>
                <p>Cantidad: ' . $cantidad . ' | Precio: $' . $precio . ' | Subtotal: $' . $subtotal . '</p>
            </div>';
    }

    $html .= '</div><div class="total">Total: $' . $totalFactura . '</div>';

    // Cerrar formulario
    $html .= '</div></body></html>';

    // Escribir el HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_clean();
    
    // Generar y mostrar el PDF
    $pdf->Output('Factura.pdf', 'I');
    exit;
}
?>
