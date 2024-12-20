<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php"); // Incluir archivo de conexión

// Consultas para obtener los datos de las tablas (como antes)
$queryClientes = "SELECT idCliente, CONCAT(Persona_idPersona) AS Cliente FROM Cliente";
$resultClientes = mysqli_query($conexion, $queryClientes);

$queryEmpleados = "SELECT idEmpleado, CONCAT(Persona_idPersona) AS Empleado FROM Empleado";
$resultEmpleados = mysqli_query($conexion, $queryEmpleados);

$queryProductos = "SELECT idProducto, Nombre_Producto, Precio FROM Producto";
$resultProductos = mysqli_query($conexion, $queryProductos);

// Verificar si se recibió una solicitud AJAX para obtener el precio y nombre de un producto
if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    $query = "SELECT Nombre_Producto, Precio FROM Producto WHERE idProducto = '$idProducto'";
    $result = mysqli_query($conexion, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Retornar nombre y precio del producto en formato JSON
        echo json_encode([
            'nombre' => $row['Nombre_Producto'],
            'precio' => $row['Precio']
        ]);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }
    exit; // Detener el procesamiento después de enviar la respuesta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Factura</title>
    <link rel="stylesheet" href="styles7.css">
    <style>
        /* Estilos para los selects */
        select, input[type="text"], input[type="date"], input[type="number"] {
            font-size: 14px;
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Estilos para los botones */
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
        }

        .flex-container {
            display: flex;
            justify-content: center;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
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
    
    <div class="form-container">
        <h2 class="form-title">Factura</h2>
        <form id="fform-container" action="procesar_factura.php" method="POST">
            <div class="form-row">
                <div class="form-group inline-group">
                    <label for="fecha">Fecha </label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
            </div>
            <div class="form-group double">
                <label for="idEmpleado">ID Empleado</label>
                <select id="idEmpleado" name="idEmpleado" required>
                    <option value="">Selecciona un Empleado</option>
                    <?php while ($row = mysqli_fetch_assoc($resultEmpleados)): ?>
                        <option value="<?php echo $row['idEmpleado']; ?>"><?php echo $row['Empleado']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>  
            <div class="form-group double">
                <label for="idCliente">ID Cliente</label>
                <select id="idCliente" name="idCliente" required>
                    <option value="">Selecciona un Cliente</option>
                    <?php while ($row = mysqli_fetch_assoc($resultClientes)): ?>
                        <option value="<?php echo $row['idCliente']; ?>"><?php echo $row['Cliente']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group wide">
                <label for="idMetodo_Pago">Método de Pago</label>
                <div id="idMetodo_Pago">
                    <label>
                        Efectivo
                        <input type="radio" name="idMetodo_Pago" value="1" required>
                    </label>
                    <label>
                        Tarjeta
                        <input type="radio" name="idMetodo_Pago" value="2" required>
                    </label>
                </div>
            </div>

            <div id="producto-container">
                <h3>Producto</h3>
                <div class="form-group producto" id="producto_0">
                    <label for="id_producto_0">ID Producto</label>
                    <select id="id_producto_0" class="id short" name="producto_id[]" onchange="obtenerNombre(0)">
                        <option value="">Selecciona un Producto</option>
                        <?php while ($row = mysqli_fetch_assoc($resultProductos)): ?>
                            <option value="<?php echo $row['idProducto']; ?>"><?php echo $row['Nombre_Producto']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <span id="nombre_producto_0"></span>
                    <label for="cantidad_0">Cantidad</label>
                    <input type="number" id="cantidad_0" class="cantidad short" name="cantidad_prod[]" value="1" min="1" oninput="calcularTotal()">
                    <label for="precio_unitario_0">Precio Unitario</label>
                    <input type="text" id="precio_unitario_0" class="precio_unitario short" name="precio_unitario[]" readonly>
                    <button type="button" onclick="eliminarProducto(0)">Eliminar</button>
                </div>
            </div>
            <div class="flex-container">
                <button type="button" onclick="agregarProducto()">Agregar Producto</button>
            </div>

            <div class="form-buttons">
                <div class="total">
                    Total: $<span id="total">0.00</span>
                </div>
                <button type="submit">REGISTRAR</button>
            </div>
        </form>
    </div>

    <script>
let productos = []; // Para llevar la cuenta de los productos agregados
// Función para agregar un nuevo producto dinámico
function agregarProducto() {
    const index = productos.length; // El índice del nuevo producto
    clonarProducto(index); // Llama a la función para clonar un nuevo producto
}

// Función para clonar y configurar un nuevo producto
function clonarProducto(index) {
    // Generar HTML del nuevo producto
    const nuevoProductoHTML = `
        <div class="form-group producto" id="producto_${index}">
            <label for="id_producto_${index}">ID Producto</label>
            <select id="id_producto_${index}" class="id short" name="producto_id[]" onchange="obtenerNombre(${index})">
                <option value="">Selecciona un Producto</option>
                <?php while ($row = mysqli_fetch_assoc($resultProductos)): ?>
                    <option value="<?php echo $row['idProducto']; ?>"><?php echo $row['Nombre_Producto']; ?></option>
                <?php endwhile; ?>
            </select>
            <span id="nombre_producto_${index}"></span>
            <label for="cantidad_${index}">Cantidad</label>
            <input type="number" id="cantidad_${index}" class="cantidad short" name="cantidad_prod[]" value="1" min="1" oninput="calcularTotal()">
            <label for="precio_unitario_${index}">Precio Unitario</label>
            <input type="text" id="precio_unitario_${index}" class="precio_unitario short" name="precio_unitario[]" readonly>
            <button type="button" onclick="eliminarProducto(${index})">Eliminar</button>
        </div>
    `;

    // Insertar el nuevo producto en el contenedor
    document.getElementById('producto-container').insertAdjacentHTML('beforeend', nuevoProductoHTML);

    // Registrar el nuevo producto en el array
    productos.push(index);
}
async function obtenerNombre(index) {
    const idProducto = document.getElementById(`id_producto_${index}`).value;
    if (idProducto) {
        try {
            const response = await fetch(`?idProducto=${idProducto}`);
            const data = await response.json();
            document.getElementById(`nombre_producto_${index}`).textContent = data.nombre;
            document.getElementById(`precio_unitario_${index}`).value = data.precio;
            calcularTotal();
        } catch (error) {
            console.error('Error al obtener los datos del producto:', error);
        }
    }
}
// Suponiendo que tienes una función obtenerDatos() que devuelve una promesa con los datos
async function generarSelect(contenedor, datos) {
    const select = document.createElement('select');
    select.id = `select_${contenedor}`; // Genera un ID único

    let html = '<option value="">Selecciona una opción</option>';
    datos.forEach(dato => {
        html += `<option value="${dato.id}">${dato.nombre}</option>`;
    });
    select.innerHTML = html;

    document.getElementById(contenedor).appendChild(select);
}

// Ejemplo de uso
obtenerDatos()
    .then(datos => {
        generarSelect('contenedor_select', datos);
    })
    .catch(error => {
        console.error('Error al obtener los datos:', error);
    });// Función para calcular el total de la factura
function calcularTotal() {
    let total = 0;
    const productos = document.querySelectorAll(".producto");

    productos.forEach(producto => {
        const cantidad = parseFloat(producto.querySelector(".cantidad").value);
        const precioUnitario = parseFloat(producto.querySelector(".precio_unitario").value);
        if (!isNaN(cantidad) && !isNaN(precioUnitario)) {
            total += cantidad * precioUnitario;
        }
    });

    document.getElementById("total").textContent = total.toFixed(2);
}

// Función para eliminar un producto
function eliminarProducto(index) {
    document.getElementById(`producto_${index}`).remove();
    calcularTotal(); // Recalcular el total al eliminar un producto
}
</script>

</body>
</html>
