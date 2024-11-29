<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Gestión de Producto</h1>
        <form>
            <div class="column">
                <label for="idProducto">ID Producto:</label>
                <input type="text" id="IdProducto" name="IdProducto" required>

                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" required>

                <label for="Marca">Marca:</label>
                <input type="text" id="Marca" name="Marca" required>

                <label for="Categoria">Categoria:</label>
                <input type="text" id="Categoria" name="Categoria" required>

                <label for="stock">Stock:</label>
                <input type="text" id="stock" name="stock" required>
            </div>
            <div class="column">
                <label for="IdProveedor">ID Proveedor:</label>
                <input type="text" id="IdProveedor" name="IdProveedor" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>

                <label for="Precio">Precio:</label>
                <input type="text" id="Precio" name="Precio" required>

                <label for="FechaIngreso">Fecha Ingreso:</label>
                <input type="datetime" id="FechaIngreso" name="FechaIngreso" required>

                <label for="Estado">Estado:</label>
                <input type="text" id="Estado" name="Estado" required>
            </div>

            <div class="buttons" >
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</body>
</html>