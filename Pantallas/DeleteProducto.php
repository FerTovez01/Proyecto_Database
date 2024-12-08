<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Primero, obtener el Producto_idProducto desde la tabla producto_has_proveedor
        $sqlProductoProveedor = "SELECT Proveedor_idProveedor FROM producto_has_proveedor WHERE Producto_idProducto = $idProducto";
        $resultadoProductoProveedor = $conexion->query($sqlProductoProveedor);

        if ($resultadoProductoProveedor && $resultadoProductoProveedor->num_rows > 0) {
            // Obtener el proveedor relacionado con el producto
            $productoProveedor = $resultadoProductoProveedor->fetch_assoc();
            $proveedorId = $productoProveedor['Proveedor_idProveedor'];

            // Eliminar la asociación del producto con el proveedor
            $sqlEliminarProductoProveedor = "DELETE FROM producto_has_proveedor WHERE Producto_idProducto = $idProducto";
            $conexion->query($sqlEliminarProductoProveedor);

            // Verificar si el proveedor está asociado con otros productos
            $sqlVerificarProveedor = "SELECT COUNT(*) AS count FROM producto_has_proveedor WHERE Proveedor_idProveedor = $proveedorId";
            $resultadoVerificarProveedor = $conexion->query($sqlVerificarProveedor);
            $proveedor = $resultadoVerificarProveedor->fetch_assoc();

            // Si el proveedor no está relacionado con otros productos, eliminarlo
            if ($proveedor['count'] == 0) {
                $sqlEliminarProveedor = "DELETE FROM Proveedor WHERE idProveedor = $proveedorId";
                $conexion->query($sqlEliminarProveedor);
            }
        }

        // Eliminar el producto de la tabla producto
        $sqlEliminarProducto = "DELETE FROM producto WHERE idProducto = $idProducto";
        if ($conexion->query($sqlEliminarProducto) === TRUE) {
            echo "Producto eliminado exitosamente.<br>";
        } else {
            echo "Error al eliminar producto: " . $conexion->error . "<br>";
        }

        // Confirmar transacción
        $conexion->commit();
    } catch (Exception $e) {
        // Si algo falla, hacer rollback
        $conexion->rollback();
        echo "Error al eliminar el producto: " . $e->getMessage() . "<br>";
    }
} else {
    echo "ID de producto no proporcionado.<br>";
}

header("Location: ProductoLista.php");
?>
