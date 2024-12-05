
<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

?>
<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\\xampp\\htdocs\\ProyectoWeb\\php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Factura</title>

    <!-- styles -->
    <link rel="stylesheet" href="styles7.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.html'">Volver</button>
    </header>
    
    <div class="form-container">
        <h2 class="form-title">Factura</h2>
        <form id="fform-container" action="procesar_factura.php" method="POST">
            <div class="form-row">
                <div class="form-group inline-group">
                    <label for="id">ID Factura</label>
                    <input type="text" id="id" name="id">
                </div>
                <div class="form-group inline-group">
                    <label for="fecha">Fecha </label>
                    <input type="date" id="fecha" name="fecha">
                </div>
            </div>
            <div class="form-group double">
                <label for="idEmpleado">ID Empleado</label>
                <input type="text" id="idEmpleado" name="idEmpleado" oninput="obtenerNombre('empleado', this.value)">
                <span id="nombre_empleado"></span>
            </div>  
            <div class="form-group double">
                <label for="idCliente">ID Cliente</label>
                <input type="text" id="idCliente" name="idCliente" oninput="obtenerNombre('cliente', this.value)">
                <span id="nombre_cliente"></span>
            </div>
            <div class="form-group wide">
                <label for="idMetodo_Pago">Metodo pago</label>
                <div id="idMetodo_Pago">
                    <label>
                        Efectivo
                        <input type="radio" name="idMetodo_Pago" value="1">
                    </label>
                    <label>
                        Tarjeta
                        <input type="radio" name="idMetodo_Pago" value="2">
                    </label>
                </div>
            </div>           
            <div id="producto-container">
                <h3>Producto</h3>
                <div class="form-group producto">
                    <label for="id_producto_0">ID Producto</label>
                    <input type="text" id="id_producto_0" class="id short" name="producto_id" oninput="obtenerNombre('producto', this.value, 0)">
                    <span id="nombre_producto_0"></span>
                    <label for="cantidad_0">Cantidad</label>
                    <input type="text" id="cantidad_0" class="cantidad short" name="cantidad_prod">
                    <label for="precio_unitario_0">Precio Unitario</label>
                    <input type="text" id="precio_unitario_0" class="precio_unitario short" name="precio_unitario" readonly>
                    <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
                </div>
            </div>      
            <div class="flex-container">
                <button type="button" onclick="agregarProducto()">Agregar Producto</button>
            </div>  
            <div class="form-buttons">
                <div class="total">
                    Total: $<span id="total">0.00</span>
                </div>
                <button type="button" onclick="registrarFactura()">REGISTRAR</button>
            </div>
        </form>
    </div>

    <!-- JavaScript -->
    <script>// Arreglo para almacenar los productos agregados
let productos = [];

function agregarProducto() {
    const container = document.getElementById("producto-container");
    const index = productos.length; // Nuevo índice basado en la cantidad de productos

    // Crear un nuevo bloque de producto
    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("form-group", "producto");

    nuevoProducto.innerHTML = `
        <label for="id_producto_${index}">ID Producto</label>
        <input type="text" id="id_producto_${index}" class="id short" name="producto_id" oninput="obtenerNombre('producto', this.value, ${index})">
        <span id="nombre_producto_${index}"></span>
        <label for="cantidad_${index}">Cantidad</label>
        <input type="text" id="cantidad_${index}" class="cantidad short" name="cantidad_prod" oninput="calcularTotal()">
        <label for="precio_unitario_${index}">Precio Unitario</label>
        <input type="text" id="precio_unitario_${index}" class="precio_unitario short" name="precio_unitario" readonly>
        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
    `;

    container.appendChild(nuevoProducto);
    productos.push({ id: "", cantidad: 0, precio: 0 }); // Agregar un producto vacío
}

function eliminarProducto(button) {
    const productoDiv = button.parentElement;
    const container = document.getElementById("producto-container");
    container.removeChild(productoDiv);

    // Recalcular el total después de eliminar
    calcularTotal();
}

function obtenerNombre(tipo, id, index = null) {
    let nombre = ""; // Valor predeterminado en caso de que no se encuentre

    // Simulación de nombres para efectos demostrativos
    if (tipo === 'empleado') {
        nombre = id === "1" ? "Empleado Ejemplo" : "Desconocido";
        document.getElementById("nombre_empleado").textContent = nombre;
    } else if (tipo === 'cliente') {
        nombre = id === "1" ? "Cliente Ejemplo" : "Desconocido";
        document.getElementById("nombre_cliente").textContent = nombre;
    } else if (tipo === 'producto' && index !== null) {
        nombre = id === "101" ? "Producto Ejemplo" : "Desconocido";
        const precio = id === "101" ? 10.0 : 0.0; // Precio simulado
        document.getElementById(`nombre_producto_${index}`).textContent = nombre;
        document.getElementById(`precio_unitario_${index}`).value = precio.toFixed(2);

        // Actualizar el arreglo de productos
        productos[index] = { id, cantidad: 0, precio };
        calcularTotal();
    }
}

function calcularTotal() {
    let total = 0;
    productos.forEach((producto, index) => {
        const cantidadInput = document.getElementById(`cantidad_${index}`);
        const cantidad = parseInt(cantidadInput?.value || 0);
        const precio = producto.precio;

        productos[index].cantidad = cantidad;
        total += cantidad * precio;
    });

    document.getElementById("total").textContent = total.toFixed(2);
}

function registrarFactura() {
    const idFactura = document.getElementById("id").value;
    const fecha = document.getElementById("fecha").value;
    const idEmpleado = document.getElementById("idEmpleado").value;
    const idCliente = document.getElementById("idCliente").value;
    const metodoPago = document.querySelector("input[name='idMetodo_Pago']:checked")?.value;

    if (!idFactura || !fecha || !idEmpleado || !idCliente || !metodoPago) {
        alert("Por favor, complete todos los campos obligatorios.");
        return;
    }

    // Crear un formulario y enviar los datos
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "procesar_factura.php";

    form.innerHTML = `
        <input type="hidden" name="id" value="${idFactura}">
        <input type="hidden" name="fecha" value="${fecha}">
        <input type="hidden" name="idEmpleado" value="${idEmpleado}">
        <input type="hidden" name="idCliente" value="${idCliente}">
        <input type="hidden" name="idMetodo_Pago" value="${metodoPago}">
        <input type="hidden" name="productos" value='${JSON.stringify(productos.filter(p => p.cantidad > 0))}'>
    `;

    document.body.appendChild(form);
    form.submit();
}

    </script>
</body>
</html>
