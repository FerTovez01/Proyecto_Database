<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

if (isset($_GET['id'])) {
    $idProveedor = $_GET['id'];

    // Primero, obtener el ID de la dirección asociada al proveedor
    $sqlProveedor = "SELECT Direccion_idDireccion FROM Proveedor WHERE idProveedor = ?";
    $stmtProveedor = $conexion->prepare($sqlProveedor);
    $stmtProveedor->bind_param('i', $idProveedor);

    if ($stmtProveedor->execute()) {
        $resultadoProveedor = $stmtProveedor->get_result();

        if ($resultadoProveedor && $resultadoProveedor->num_rows > 0) {
            $proveedor = $resultadoProveedor->fetch_assoc();
            $direccionId = $proveedor['Direccion_idDireccion'];

            // Eliminar la relación producto-proveedor
            $sqlEliminarRelacion = "DELETE FROM producto_has_proveedor WHERE Proveedor_idProveedor = ?";
            $stmtEliminarRelacion = $conexion->prepare($sqlEliminarRelacion);
            $stmtEliminarRelacion->bind_param('i', $idProveedor);
            if (!$stmtEliminarRelacion->execute()) {
                echo "Error al eliminar la relación producto-proveedor: " . $stmtEliminarRelacion->error . "<br>";
            } else {
                echo "Relación producto-proveedor eliminada exitosamente.<br>";
            }

            // Eliminar el proveedor
            $sqlEliminarProveedor = "DELETE FROM Proveedor WHERE idProveedor = ?";
            $stmtEliminarProveedor = $conexion->prepare($sqlEliminarProveedor);
            $stmtEliminarProveedor->bind_param('i', $idProveedor);
            if (!$stmtEliminarProveedor->execute()) {
                echo "Error al eliminar proveedor: " . $stmtEliminarProveedor->error . "<br>";
            } else {
                echo "Proveedor eliminado exitosamente.<br>";
            }

            // Eliminar la dirección asociada al proveedor
            $sqlEliminarDireccion = "DELETE FROM Direccion WHERE idDireccion = ?";
            $stmtEliminarDireccion = $conexion->prepare($sqlEliminarDireccion);
            $stmtEliminarDireccion->bind_param('i', $direccionId);
            if (!$stmtEliminarDireccion->execute()) {
                echo "Error al eliminar la dirección: " . $stmtEliminarDireccion->error . "<br>";
            } else {
                echo "Dirección eliminada exitosamente.<br>";
            }

        } else {
            echo "Proveedor no encontrado.<br>";
        }
    } else {
        echo "Error al ejecutar consulta para obtener el proveedor: " . $stmtProveedor->error . "<br>";
    }
} else {
    echo "ID de proveedor no proporcionado.<br>";
}

header("Location: ProveedoresLista.php");
?>
