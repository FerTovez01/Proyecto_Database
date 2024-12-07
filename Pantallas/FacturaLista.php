<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Consulta para obtener los datos requeridos
$sql = "
SELECT 
    f.idFactura,
    f.Fecha_Emision,
    v.Cliente_idCliente AS idCliente,
    v.Empleado_idEmpleado AS idEmpleado,  -- Se muestra el idEmpleado desde la tabla Venta
    mp.Nombre_Pago,
    dv.Producto_idProducto AS idProducto,
    dv.Cantidad,
    dv.Precio_Unitario AS Precio
FROM 
    Factura f
INNER JOIN 
    Venta v ON f.Venta_idVenta = v.idVenta
INNER JOIN 
    Metodo_Pago mp ON v.Metodo_Pago_idMetodo_Pago = mp.idMetodo_Pago
INNER JOIN 
    Detalle_Venta dv ON v.idVenta = dv.Venta_idVenta
INNER JOIN 
    Producto p ON dv.Producto_idProducto = p.idProducto";

// Ejecutar la consulta y almacenar el resultado
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Facturas</title>
    <link rel="stylesheet" href="styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Lista de Facturas</h1>
        
        <table>
        <thead>
    <tr>
        <th>ID Factura</th>
        <th>Fecha Emisión</th>
        <th>ID Cliente</th>
        <th>ID Empleado</th> <!-- Nueva columna -->
        <th>Método de Pago</th>
        <th>ID Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    <?php
    // Verificar si hay resultados
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['idFactura'] . "</td>";
            echo "<td>" . $fila['Fecha_Emision'] . "</td>";
            echo "<td>" . $fila['idCliente'] . "</td>";
            echo "<td>" . $fila['idEmpleado'] . "</td>"; // Mostrar ID Empleado
            echo "<td>" . $fila['Nombre_Pago'] . "</td>";
            echo "<td>" . $fila['idProducto'] . "</td>";
            echo "<td>" . $fila['Cantidad'] . "</td>";
            echo "<td>" . $fila['Precio'] . "</td>";
            echo "<td>
                   
                    <a href='DeleteFactura.php?id=" . $fila['idFactura'] . "' class='accion'><i class='fas fa-trash-alt' style='color: purple;'></i></a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No hay datos disponibles</td></tr>";
    }
    ?>
</tbody>
</table>
<div class="buttons">
            <button type="button" onclick="location.href='GenerarFactura.php'">Registrar Factura</button>
        </div>
</div>
</body>
</html>
