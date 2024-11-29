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
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Gestión de Categoria</h1>
        <form>
            <div class="column">
                <label for="idCategoria">ID Categoria:</label>
                <input type="text" id="idCategoria" name="idCategoria" required>

            </div>
            <div class="column">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" required>

            </div>
            <div class="column">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required>

            </div>
            <div class="buttons" >
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</body>
</html>