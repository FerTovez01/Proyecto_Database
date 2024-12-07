<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Lista de Facturas </title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='index.php'">Inicio</button>
    </header>
    <div class="container">
        <h1>Menu</h1>
        <form>
            <div class="column">
                <button type="button" onclick="location.href='ClienteLista.php'"> Clientes </button>
                <button type="button" onclick="location.href='EmpleadoLista.php'">Empleados</button>
                <button type="button" onclick="location.href='ProveedoresLista.php'">Proveedor</button>
            </div>
            <div class="column">
                <button type="button" onclick="location.href='ProductoLista.php'">Productos</button>
                <button type="button" onclick="location.href='MarcaLista.php'">Marcas</button>
                <button type="button" onclick="location.href='CategoriaLista.php'">Gestion Categorias</button>
            </div>
            <div class="column">
                <button type="button" onclick="location.href='GestionInventario.php'">Gestion Inventario</button>
                <button type="button" onclick="location.href='FacturaLista.php'">Generar Factura</button>
                
            </div>
        </form>
    </div>
</body>
</html>