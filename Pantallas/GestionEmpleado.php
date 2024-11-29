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
        <h1>Gestión de Empleado</h1>
        <form>
            <div class="column">
                <label for="IdEmpleado">ID Empleado:</label>
                <input type="text" id="IdEmpleado" name="IdEmpleado" required>
                <label for="Cargo">Cargo:</label>
                <input type="text" id="Cargo" name="Cargo" required>
            </div>
            <div class="column">
                <label for="PrimerNombre">Nombres:</label>
                <input type="text" id="PrimerNombre" name="PrimerNombre" required>

                <label for="PrimerApellido">Apellidos:</label>
                <input type="text" id="PrimerApellido" name="PrimerApellido" required>

                <label for="Departamento">Departamento:</label>
                <input type="text" id="Departamento" name="Departamento" required>

                <label for="Municipio">Municipio:</label>
                <input type="text" id="Municipio" name="Municipio" required>

                <label for="Telefono">Teléfono:</label>
                <input type="tel" id="Telefono" name="Telefono" required>
            </div>
            <div class="column">
                <label for="SegundoNombre">ㅤㅤㅤ</label>
                <input type="text" id="SegundoNombre" name="SegundoNombre" required>

                <label for="SegundoApellido">ㅤㅤㅤ</label>
                <input type="text" id="SegundoApellido" name="SegundoApellido" required>

                <label for="colonia">Colonia:</label>
                <input type="text" id="colonia" name="colonia" required>

                <label for="calle">Calle:</label>
                <input type="text" id="calle" name="calle" required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="buttons" >
                <button type="reset">Cancelar</button>
                <button type="submit">Registrar</button>
            </div>
        </form>
    </div>
</body>
</html>