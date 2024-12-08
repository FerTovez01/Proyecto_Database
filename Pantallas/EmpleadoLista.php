<?php
// Incluir el archivo de conexión
include '../php/conexion.php';

// Variables para búsqueda y paginación
$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 10;
$offset = ($pagina - 1) * $registros_por_pagina;

// Consulta SQL con búsqueda y paginación
$sql = "SELECT Empleado.idEmpleado, 
               Cargo.Nombre AS Cargo, 
               Persona.primer_Nombre, Persona.segundo_Nombre, 
               Persona.primer_Apellido, Persona.segundo_Apellido, 
               Persona.Correo, 
               Telefono.Numero AS Telefono,
               direccion_persona.Departamento, direccion_persona.Municipio, 
               direccion_persona.Colonia_barrio AS Colonia, direccion_persona.Calle
        FROM Empleado
        INNER JOIN Persona ON Empleado.Persona_idPersona = Persona.idPersona
        LEFT JOIN Cargo ON Empleado.Cargo_idCargo = Cargo.idCargo
        LEFT JOIN Telefono ON Telefono.Persona_idPersona = Persona.idPersona
        LEFT JOIN direccion_persona ON direccion_persona.Persona_idPersona = Persona.idPersona
        WHERE CONCAT(Persona.primer_Nombre, ' ', Persona.primer_Apellido, ' ', Cargo.Nombre) LIKE '%$busqueda%'
        LIMIT $offset, $registros_por_pagina";

$resultado = $conexion->query($sql);

// Contar el total de registros para la paginación
$sql_total = "SELECT COUNT(*) as total FROM Empleado
              INNER JOIN Persona ON Empleado.Persona_idPersona = Persona.idPersona
              LEFT JOIN Cargo ON Empleado.Cargo_idCargo = Cargo.idCargo
              WHERE CONCAT(Persona.primer_Nombre, ' ', Persona.primer_Apellido, ' ', Cargo.Nombre) LIKE '%$busqueda%'";
$total_resultado = $conexion->query($sql_total);
$total_registros = $total_resultado->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>/* Estilos del formulario de búsqueda */
form {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
}

form input[type="text"] {
    padding: 12px;
    width: 100%;
    max-width: 400px; /* Ajuste del tamaño máximo */
    border: 1px solid #ddd;
    border-radius: 25px;
    font-size: 16px;
    transition: border-color 0.3s ease-in-out;
}

form input[type="text"]:focus {
    border-color: #833576;
    outline: none;
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

/* Agregar sombra al formulario para destacarlo */
form input[type="text"], form button {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

form input[type="text"]:focus, form button:focus {
    box-shadow: 0 0 8px rgba(131, 53, 118, 0.6);
}

/* Mejoras en la paginación */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination a {
    padding: 10px 15px;
    border: 1px solid #833576;
    border-radius: 5px;
    text-decoration: none;
    color: #833576;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.pagination a:hover {
    background-color: #833576;
    color: white;
}

.pagination a.active {
    background-color: #833576;
    color: white;
    pointer-events: none;
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
        <h1>Lista de Empleados</h1>
        <!-- Formulario de búsqueda -->
        <form method="GET" action="EmpleadoLista.php">
            <input type="text" name="busqueda" placeholder="Buscar empleado..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit">Buscar</button>
        </form>
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado && $resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["idEmpleado"] . "</td>
                                <td>" . $row["Cargo"] . "</td>
                                <td>" . $row["primer_Nombre"] . " " . $row["segundo_Nombre"] . "</td>
                                <td>" . $row["primer_Apellido"] . " " . $row["segundo_Apellido"] . "</td>
                                <td>" . ($row["Departamento"] ?? "N/A") . "</td>
                                <td>" . ($row["Municipio"] ?? "N/A") . "</td>
                                <td>" . ($row["Colonia"] ?? "N/A") . "</td>
                                <td>" . ($row["Calle"] ?? "N/A") . "</td>
                                <td>" . ($row["Telefono"] ?? "N/A") . "</td>
                                <td>" . $row["Correo"] . "</td>
                                <td>
                                    <a href='UpdateEmpleado.php?id=" . $row['idEmpleado'] . "' class='accion' style='color: #833576;'>
                                        <i class='fas fa-edit'></i>
                                    </a>  
                                    | 
                                    <a href='DeleteEmpleado.php?id=" . $row['idEmpleado'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este empleado?\")' style='color: #833576;'>
                                        <i class='fas fa-trash-alt'></i>
                                    </a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay empleados registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="EmpleadoLista.php?busqueda=<?php echo urlencode($busqueda); ?>&pagina=<?php echo $i; ?>" 
                   class="<?php echo $i == $pagina ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <div class="buttons">
            <button type="button" onclick="location.href='GestionEmpleado.php'">Registrar Empleado</button>
        </div>
    </div>
</body>
</html>
