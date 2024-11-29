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
        <h1>Lista de Proveedores</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Proveedor</th>
                    <th>Nombres</th>
                    <th>Pais</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Colonia</th>
                    <th>Calle</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>IdProducto</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Cosmeticos MEX</td>
                    <td>Mexico</td>
                    <td>Cancun</td>
                    <td>Playa</td>
                    <td>Brisas</td>
                    <td>Calle Principal</td>
                    <td>12376548</td>
                    <td>gatofeliz.perez@example.com</td>
                    <td>05</td>
                </tr>
            </tbody>
            <div class="buttons" >
                <button type="button" onclick="location.href='GestionProveedores.html'"> Registrar Proveedor </button>
            </div>
        </table>
    </div>
</body>
</html>