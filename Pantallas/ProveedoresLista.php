<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include 'conexion.php'; // Incluye la conexión a la base de datos

// Configuración para la paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Manejo de búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

// Consulta para contar el total de registros según búsqueda
$sql_total = "
    SELECT COUNT(*) AS total
    FROM Proveedor p
    INNER JOIN Direccion d ON p.Direccion_idDireccion = d.idDireccion
    INNER JOIN producto_has_proveedor php ON p.idProveedor = php.Proveedor_idProveedor
    INNER JOIN Producto pr ON php.Producto_idProducto = pr.idProducto
    WHERE p.Nombre_Provedor LIKE '%$buscar%'
";
$resultado_total = $conexion->query($sql_total);
$total_registros = $resultado_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Consulta principal con búsqueda, LIMIT y OFFSET
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
        producto_has_proveedor php ON p.idProveedor = php.Proveedor_idProveedor
    INNER JOIN 
        Producto pr ON php.Producto_idProducto = pr.idProducto
    WHERE p.Nombre_Provedor LIKE '%$buscar%'
    LIMIT $registros_por_pagina OFFSET $offset
";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<sty>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Proveedores</title>
    <link rel="stylesheet" href="styles3.css">
    <style>
/* Diseño básico para la página */
/* Formulario de búsqueda reducido */
/* Formulario de búsqueda aún más reducido */
form.form-busqueda {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
}

form.form-busqueda input[type="text"] {
    padding: 4px;
    width: 150px; /* Reducido aún más el tamaño del campo */
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 12px; /* Reducido el tamaño de la fuente */
}

form.form-busqueda button {
    background-color: #833576;
    color: white;
    padding: 4px 8px; /* Reducido el tamaño del botón */
    border: none;
    cursor: pointer;
    font-size: 12px; /* Reducido el tamaño de la fuente */
    border-radius: 4px;
    margin-left: 5px; /* Reducido el espacio entre el campo y el botón */
}

form.form-busqueda button:hover {
    background-color: #5e264a;
}
.paginacion {
    text-align: center;
    margin-top: 20px;
}

.paginacion a {
    color: #833576;
    margin: 0 5px;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #833576;
    border-radius: 4px;
}

.paginacion a:hover {
    background-color: #833576;
    color: white;
}

.paginacion a.activo {
    background-color: #833576;
    color: white;
    pointer-events: none;
}

.paginacion a:disabled {
    background-color: #ddd;
    color: #aaa;
    pointer-events: none;
}

.buttons {
    text-align: center;
    margin-top: 20px;
}

.buttons button {
    background-color: #833576;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 4px;
}

.buttons button:hover {
    background-color: #5e264a;
}

/* Estilo para los íconos de acciones */
.accion-editar, .accion-eliminar {
    color: #833576;
    font-size: 20px;
    text-decoration: none;
}

.accion-editar:hover, .accion-eliminar:hover {
    color: #5e264a;
}



    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </style>
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

        <!-- Formulario de búsqueda -->
        <form method="GET" action="" class="form-busqueda">
            <input type="text" name="buscar" placeholder="Buscar por nombre" value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Proveedor</th>
                    <th>Id Producto</th>
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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['idProveedor']}</td>
                                <td>{$row['idProducto']}</td>
                                <td>{$row['Nombre_Provedor']}</td>
                                <td>{$row['Telefono']}</td>
                                <td>{$row['Correo']}</td>
                                <td>{$row['Colonia_Barrio']}</td>
                                <td>{$row['Municipio']}</td>
                                <td>{$row['Departamento']}</td>
                                <td>{$row['Pais']}</td>
                                <td>
                                    <a href='UpdateProveedor.php?id={$row['idProveedor']}' class='accion-editar' style='color: #833576;'>
                                        <i class='fas fa-edit' title='Editar'></i>
                                    </a>
                                    <a href='DeleteProveedor.php?id={$row['idProveedor']}' class='accion-eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este proveedor?\")' style='color: #833576;'>
                                        <i class='fas fa-trash' title='Eliminar'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay proveedores registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Enlaces de paginación -->
        <div class="paginacion">
    <?php if ($pagina_actual > 1): ?>
        <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $pagina_actual - 1; ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
        <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $i; ?>" 
           class="<?php echo ($i == $pagina_actual) ? 'activo' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($pagina_actual < $total_paginas): ?>
        <a href="?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente &raquo;</a>
    <?php endif; ?>
</div>

        <div class="buttons">
            <button type="button" onclick="location.href='GestionProveedores.php'">Registrar Proveedor</button>
        </div>
    </div>
</body>
</html>

<?php
$conexion->close();
?>
