<?php
// Conexión a la base de datos (MySQLi)
$server = "localhost";
$user = "root";
$pass = "";
$DB = "bella_fusion_db";

// Establecer la conexión
$conexion = new mysqli($server, $user, $pass, $DB);

// Verificar si la conexión es exitosa
if ($conexion->connect_errno) {
    die("Conexión Fallida: " . $conexion->connect_errno);
}

// Función para retornar la conexión
function retornarConexion() {
    global $conexion;
    return $conexion;
}

// Procesamiento cuando se recibe el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegurarnos de que producto_id y otras variables estén definidas
    if (isset($_POST['producto_id']) && !empty($_POST['producto_id'])) {
        $producto_ids = $_POST['producto_id'];
        $cantidad_prod = $_POST['cantidad_prod'];
        $cantidad_prod1 = $_POST['cantidad_prod1'];
        $fecha_registro = $_POST['fecha_registro'];

        // Iterar sobre los productos y guardar cada uno en la base de datos
        for ($i = 0; $i < count($producto_ids); $i++) {
            $producto_id = $producto_ids[$i];
            $cantidad_entrada = $cantidad_prod[$i];
            $cantidad_salida = $cantidad_prod1[$i];
            $fecha = $fecha_registro[$i];

            // Verificar que producto_id no sea nulo o vacío
            if ($producto_id == NULL || empty($producto_id)) {
                echo "El ID del producto no puede ser nulo o vacío.";
                exit;
            }

            // Primero, obtenemos el idInventario relacionado con el producto
            $sql_inventario = "SELECT idInventario FROM inventario WHERE Producto_idProducto = ?";
            $stmt_inventario = retornarConexion()->prepare($sql_inventario);
            $stmt_inventario->bind_param('i', $producto_id);
            $stmt_inventario->execute();
            $stmt_inventario->store_result();

            // Verificamos si hay resultados
            if ($stmt_inventario->num_rows > 0) {
                $stmt_inventario->bind_result($inventario_id);
                $stmt_inventario->fetch();  // Obtenemos el idInventario

                // Inserción de movimiento de inventario (Entrada)
                $tipo_movimiento = 'Entrada';  // Para la entrada
                $sql = "INSERT INTO movimiento_inventario (Inventario_idInventario, Tipo_Movimiento, Cantidad, Fecha_Moviento)
                        VALUES (?, ?, ?, ?)";
                $stmt = retornarConexion()->prepare($sql);
                $stmt->bind_param('isis', $inventario_id, $tipo_movimiento, $cantidad_entrada, $fecha);
                if (!$stmt->execute()) {
                    echo "Error al insertar entrada: " . $stmt->error;
                    exit;
                }

                // Inserción de movimiento de inventario (Salida)
                $tipo_movimiento = 'Salida';  // Para la salida
                $sql = "INSERT INTO movimiento_inventario (Inventario_idInventario, Tipo_Movimiento, Cantidad, Fecha_Moviento)
                        VALUES (?, ?, ?, ?)";
                $stmt = retornarConexion()->prepare($sql);
                $stmt->bind_param('isis', $inventario_id, $tipo_movimiento, $cantidad_salida, $fecha);
                if (!$stmt->execute()) {
                    echo "Error al insertar salida: " . $stmt->error;
                    exit;
                }

            } else {
                // Si no encontramos el inventario, insertar uno nuevo
                $sql_insertar_inventario = "INSERT INTO inventario (Producto_idProducto, Cantidad_Entrada, Cantidad_Salida) 
                                            VALUES (?, ?, ?)";
                $stmt_insertar = retornarConexion()->prepare($sql_insertar_inventario);
                $stmt_insertar->bind_param('iii', $producto_id, $cantidad_entrada, $cantidad_salida);
                if (!$stmt_insertar->execute()) {
                    echo "Error al insertar nuevo inventario: " . $stmt_insertar->error;
                    exit;
                }

                // Obtener el id del nuevo inventario insertado
                $inventario_id = retornarConexion()->insert_id;

                // Ahora, insertar el movimiento de inventario (Entrada)
                $tipo_movimiento = 'Entrada';
                $sql = "INSERT INTO movimiento_inventario (Inventario_idInventario, Tipo_Movimiento, Cantidad, Fecha_Moviento)
                        VALUES (?, ?, ?, ?)";
                $stmt = retornarConexion()->prepare($sql);
                $stmt->bind_param('isis', $inventario_id, $tipo_movimiento, $cantidad_entrada, $fecha);
                if (!$stmt->execute()) {
                    echo "Error al insertar entrada: " . $stmt->error;
                    exit;
                }

                // Inserción de movimiento de inventario (Salida)
                $tipo_movimiento = 'Salida';
                $sql = "INSERT INTO movimiento_inventario (Inventario_idInventario, Tipo_Movimiento, Cantidad, Fecha_Moviento)
                        VALUES (?, ?, ?, ?)";
                $stmt = retornarConexion()->prepare($sql);
                $stmt->bind_param('isis', $inventario_id, $tipo_movimiento, $cantidad_salida, $fecha);
                if (!$stmt->execute()) {
                    echo "Error al insertar salida: " . $stmt->error;
                    exit;
                }

                echo "Nuevo inventario insertado y movimientos registrados correctamente";
            }
        }
    } else {
        echo "No se ha proporcionado un ID de producto.";
        exit;
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="styles7.css">
    <style>
         h3 {
            text-align: center;
            margin-top: 20px;
        }
        .flex-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
         /* Estilos para los mensajes */
         .message-box {
            display: none;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            transition: opacity 0.3s ease-in-out;
        }

        .message-box.show {
            display: block;
            opacity: 1;
        }

        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
</style>

    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<header>
    <div class="logo">
        <img src="../img/bf2.png" alt="Logo">
    </div>
    <button type="button" onclick="location.href='InventarioLista.php'">Volver</button>
</header>

<div class="content">
    <h3>Gestión de inventario</h3>

    <!-- Formulario para enviar los productos al backend -->
    <form id="registroInventarioForm" method="POST">
        <div id="producto-container">
            <div class="form-group producto" id="producto_0">
                <label for="id_producto_0">ID Producto</label>
                <select id="id_producto_0" class="id short" name="producto_id[]">
                    <!-- Aquí cargamos los productos desde la base de datos -->
                    <?php
                    // Consulta para obtener los productos desde la base de datos
                    $query = "SELECT idProducto, Nombre_Producto FROM Producto";
                    $result = retornarConexion()->query($query);

                    // Generar las opciones para el select con los productos desde la base de datos
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idProducto'] . "'>" . $row['Nombre_Producto'] . "</option>";
                    }
                    ?>
                </select>

                <label for="cantidad_0">Cantidad entrada</label>
                <input type="text" id="cantidad_0" class="cantidad short" name="cantidad_prod[]">
                
                <label for="cantidads_0">Cantidad salida</label>
                <input type="text" id="cantidad_1" class="cantidads short" name="cantidad_prod1[]">
                
                <label for="fecha_registro_0">Fecha registro</label>
                <input type="date" id="fecha_registro_0" class="fecha_registro short" name="fecha_registro[]">
                
                <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
            </div>
        </div>      

        <div class="flex-container">
            <button type="button" onclick="agregarProducto()">Agregar Producto</button>
        </div>

        <div class="flex-container">
            <button type="submit">REGISTRAR</button>
        </div>
    </form>
      <!-- Mensaje de éxito o error -->
      <div id="messageBox" class="message-box">
            <?php
            if (isset($successMessage)) {
                echo $successMessage;
            } elseif (isset($errorMessage)) {
                echo $errorMessage;
            }
            ?>
        </div>
</div>

<script>
// Arreglo para almacenar los productos agregados
let productos = [];

function agregarProducto() {
    const container = document.getElementById("producto-container");
    const index = productos.length; // Nuevo índice basado en la cantidad de productos

    // Crear un nuevo bloque de producto
    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("form-group", "producto");
    nuevoProducto.id = `producto_${index}`;

    nuevoProducto.innerHTML = `
        <label for="id_producto_${index}">ID Producto</label>
        <select id="id_producto_${index}" class="id short" name="producto_id[]">
            <?php
            // Generar las opciones para el select con productos
            $query = "SELECT idProducto, Nombre_Producto FROM Producto";
            $result = retornarConexion()->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['idProducto'] . "'>" . $row['Nombre_Producto'] . "</option>";
            }
            ?>
        </select>
        
        <label for="cantidad_${index}">Cantidad entrada</label>
        <input type="text" id="cantidad_${index}" class="cantidad short" name="cantidad_prod[]">
        
        <label for="cantidads_${index}">Cantidad salida</label>
        <input type="text" id="cantidad_${index}" class="cantidads short" name="cantidad_prod1[]">
        
        <label for="fecha_registro_${index}">Fecha registro</label>
        <input type="date" id="fecha_registro_${index}" class="fecha_registro short" name="fecha_registro[]">
        
        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    `;

    container.appendChild(nuevoProducto);
    productos.push({ id: "", cantidad: 0, precio: 0 });
}

function eliminarProducto(button) {
    const productoDiv = button.parentElement;
    const container = document.getElementById("producto-container");
    container.removeChild(productoDiv);
}

 // Mostrar el mensaje emergente
        window.onload = function() {
            var messageBox = document.getElementById('messageBox');
            if (messageBox.innerText.trim() !== "") {
                messageBox.classList.add('show');
                setTimeout(function() {
                    messageBox.classList.remove('show');
                }, 3000); // El mensaje se desvanece después de 3 segundos
            }
        };
</script>
</body>
</html>
