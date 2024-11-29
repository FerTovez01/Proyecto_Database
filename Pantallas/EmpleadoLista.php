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
        <button type="button" onclick="location.href='MenuBotones.html'">Volver</button>
    </header>
    <div class="container">
        <h1>Lista de Empleados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Empleado</th>
                    <th>Cargo</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Colonia</th>
                    <th>Calle</th>
                    <th>Teléfono</th>
                    <th>Correo</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Gerente</td>
                    <td>Mariela</td>
                    <td>Paz</td>
                    <td>Comayagua</td>
                    <td>Comayagua</td>
                    <td>Brisas del rio</td>
                    <td>Calle Principal</td>
                    <td>12376548</td>
                    <td>gatofeliz.perez@example.com</td>
                </tr>
            </tbody>
            <div class="buttons" >
                <button type="button" onclick="location.href='GestionEmpleado.html'"> Registrar Empleado </button>
            </div>
        </table>
    </div>
</body>
</html>