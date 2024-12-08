<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Variables de paginación
$registros_por_pagina = 10; // Cambia este número según cuántos registros quieras por página
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Capturar el término de búsqueda si existe
$buscar = isset($_GET['buscar']) ? $conexion->real_escape_string($_GET['buscar']) : '';

// Consulta SQL principal con paginación
$sql_base = "SELECT Cliente.idCliente, Cliente.Fecha_Registro, 
               Persona.primer_Nombre, Persona.segundo_Nombre, 
               Persona.primer_Apellido, Persona.segundo_Apellido, 
               Persona.Correo, 
               Telefono.Numero AS Telefono,
               direccion_persona.Departamento, direccion_persona.Municipio, 
               direccion_persona.Colonia_barrio AS Colonia, direccion_persona.Calle
        FROM Cliente
        INNER JOIN Persona ON Cliente.Persona_idPersona = Persona.idPersona
        LEFT JOIN Telefono ON Telefono.Persona_idPersona = Persona.idPersona
        LEFT JOIN direccion_persona ON direccion_persona.Persona_idPersona = Persona.idPersona";

// Agregar la condición de búsqueda si aplica
if ($buscar) {
    $sql_base .= " WHERE Persona.primer_Nombre LIKE '%$buscar%' 
                   OR Persona.segundo_Nombre LIKE '%$buscar%' 
                   OR Persona.primer_Apellido LIKE '%$buscar%' 
                   OR Persona.segundo_Apellido LIKE '%$buscar%' 
                   OR Persona.Correo LIKE '%$buscar%' 
                   OR Telefono.Numero LIKE '%$buscar%' 
                   OR direccion_persona.Departamento LIKE '%$buscar%' 
                   OR direccion_persona.Municipio LIKE '%$buscar%' 
                   OR direccion_persona.Colonia_barrio LIKE '%$buscar%' 
                   OR direccion_persona.Calle LIKE '%$buscar%'";
}

// Consulta para obtener el número total de registros
$sql_count = "SELECT COUNT(*) as total FROM ($sql_base) as subquery";
$resultado_count = $conexion->query($sql_count);
$total_registros = $resultado_count->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Agregar límite para la paginación
$sql_base .= " LIMIT $offset, $registros_por_pagina";

if ($conexion) {
    $resultado = $conexion->query($sql_base);

    if (!$resultado) {
        die("Error al ejecutar la consulta: " . $conexion->error);
    }
} else {
    die("Error de conexión: " . $conexion->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>/* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

/* Estilo del formulario de búsqueda */
form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin: 20px 0;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

form input[type="text"] {
    padding: 12px;
    width: 300px; /* Ancho fijo para la barra de búsqueda */
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease-in-out;
}

form input[type="text"]:focus {
    border-color: #833576;
}

form button {
    padding: 12px 20px;
    background-color: #833576;
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #5e2a50;
}

form input[type="text"], form button {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Estilos de la tabla */
table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

table th {
    background-color: #f7f7f7;
    font-weight: bold;
    color: #833576;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table td a {
    text-decoration: none;
    color: #833576;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

table td a:hover {
    background-color: #f7f7f7;
}

/* Estilo de la paginación */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a {
    display: inline-block;
    padding: 10px 15px;
    margin: 0 5px;
    border: 1px solid #833576;
    border-radius: 5px;
    text-decoration: none;
    color: #833576;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.pagination a:hover {
    background-color: #833576;
    color: #fff;
}

.pagination a.active {
    background-color: #833576;
    color: #fff;
    pointer-events: none;
}

/* Botones */
.buttons {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.buttons button {
    padding: 12px 30px;
    background-color: #833576;
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.buttons button:hover {
    background-color: #5e2a50;
}

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
        <h1>Lista de Clientes</h1>
        
        <!-- Formulario de búsqueda -->
        <form method="GET" action="ClienteLista.php">
            <input type="text" name="buscar" placeholder="Buscar cliente" value="<?php echo htmlspecialchars($buscar); ?>" />
            <button type="submit">Buscar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Colonia</th>
                    <th>Calle</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["idCliente"] . "</td>
                                <td>" . $row["primer_Nombre"] . " " . $row["segundo_Nombre"] . "</td>
                                <td>" . $row["primer_Apellido"] . " " . $row["segundo_Apellido"] . "</td>
                                <td>" . ($row["Departamento"] ?? "N/A") . "</td>
                                <td>" . ($row["Municipio"] ?? "N/A") . "</td>
                                <td>" . ($row["Colonia"] ?? "N/A") . "</td>
                                <td>" . ($row["Calle"] ?? "N/A") . "</td>
                                <td>" . ($row["Telefono"] ?? "N/A") . "</td>
                                <td>" . $row["Correo"] . "</td>
                                <td>
                                    <a href='UpdateCliente.php?id=" . $row['idCliente'] . "' class='accion' style='color: #833576;'>
                                        <i class='fas fa-edit'></i>
                                    </a>  
                                     
                                    <a href='DeleteCliente.php?id=" . $row['idCliente'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")' style='color: #833576;'>
                                        <i class='fas fa-trash-alt'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hay clientes registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Paginación -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="ClienteLista.php?buscar=<?php echo urlencode($buscar); ?>&pagina=<?php echo $i; ?>" 
                   class="<?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>

        <div class="buttons">
            <button type="button" onclick="location.href='GestionCliente.php'">Registrar Cliente</button>
        </div>
    </div>
</body>
</html>

<?php
if ($conexion) {
    $conexion->close();
}
?>
