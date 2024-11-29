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
        <h1>Lista de Categoria</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Categoria</th>
                    <th>Nombres</th>
                    <th>Descripción</th>

                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
            <div class="buttons" >
                <button type="button" onclick="location.href='GestionCategoria.php'"> Registrar Categoria </button>
            </div>
        </table>
    </div>
</body>
</html>