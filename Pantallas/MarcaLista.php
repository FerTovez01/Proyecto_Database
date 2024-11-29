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
            <img src="../img/bf.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Lista de Marcas</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Marca</th>
                    <th>Nombres</th>
                    <th>Descripción</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Huda beauty</td>
                    <td>Marca de maquillaje vegana</td>
                </tr>
            </tbody>
            <div class="buttons" >
                <button type="button" onclick="location.href='GestionMarca.php'"> Registrar Marca </button>
            </div>
        </table>
    </div>
</body>
</html>