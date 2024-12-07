<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");


if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];

    // Asegúrate de que $idProducto sea un valor válido y seguro
    $idProducto = mysqli_real_escape_string($conexion, $idProducto);

    $query = "SELECT Nombre_Producto, Precio FROM Producto WHERE idProducto = '$idProducto'";
    $result = mysqli_query($conexion, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(['nombre' => 'Producto no encontrado', 'precio' => 0]);
    }
}?>
