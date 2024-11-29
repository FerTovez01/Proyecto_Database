<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="styles3.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Lista de Proveedores</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombres</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Proveedor</th>
                    <th>Stock</th>
                    <th>Fecha Ingreso</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estado</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>05</td>
                    <td>Gloss</td>
                    <td>Labial</td>
                    <td>Huda beauty</td>
                    <td>Cosmeticos MEX</td>
                    <td>3</td>
                    <td>2024-10-11</td>
                    <td>Brillo labial</td>
                    <td>50</td>
                    <td>A</td>
                </tr>
            </tbody>
            <div class="buttons" >
                <button type="button" onclick="location.href='GestionProducto.php'"> Registrar Producto </button>
            </div>
        </table>
    </div>
</body>
</html>