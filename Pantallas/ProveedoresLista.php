<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include 'conexion.php'; // Incluye la conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="styles3.css">
    <!-- Enlace a Font Awesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <th>ID Proveedor</th>
                    <th>Id Producto</th> <!-- Nueva columna IdProducto -->
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Colonia/Barrio</th>
                    <th>Municipio</th>
                    <th>Departamento</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener datos de proveedor y productos
                $sql = "
    SELECT 
        p.idProveedor,
        pr.idProducto,  
        p.Nombre_Provedor,
        p.Telefono,
        p.Correo,
        d.Colonia_Barrio,
        d.Municipio,
        d.Departamento,
        d.Pais
    FROM 
        Proveedor p
    INNER JOIN 
        Direccion d ON p.Direccion_idDireccion = d.idDireccion
    INNER JOIN
        producto_has_proveedor php ON p.idProveedor = php.Proveedor_idProveedor  -- Usar 'Proveedor_idProveedor' en lugar de 'idProveedor'
    INNER JOIN 
        Producto pr ON php.Producto_idProducto = pr.idProducto  -- Usar 'Producto_idProducto' en lugar de 'idProducto'
";

                $result = $conexion->query($sql);

                if ($result->num_rows > 0) {
                    // Genera dinámicamente las filas
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['idProveedor']}</td>
                                <td>{$row['idProducto']}</td> <!-- Muestra el IdProducto -->
                                <td>{$row['Nombre_Provedor']}</td>
                                <td>{$row['Telefono']}</td>
                                <td>{$row['Correo']}</td>
                                <td>{$row['Colonia_Barrio']}</td>
                                <td>{$row['Municipio']}</td>
                                <td>{$row['Departamento']}</td>
                                <td>{$row['Pais']}</td>
                                <td>
                                    <a href='UpdateProveedor.php?id={$row['idProveedor']}' class='accion-editar'   style='color: #833576;'>
                                        <i class='fas fa-edit' title='Editar'></i>
                                    </a>
                                    <a href='DeleteProveedor.php?id={$row['idProveedor']}' class='accion-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este proveedor?\")'  style='color: #833576;'>
                                        <i class='fas fa-trash' title='Eliminar'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay proveedores registrados</td></tr>";
                }

                // Cierra la conexión
                $conexion->close();
                ?>
            </tbody>
        </table>
        <div class="buttons">
            <button type="button" onclick="location.href='GestionProveedores.php'">Registrar Proveedor</button>
        </div>
    </div>
</body>
</html>
