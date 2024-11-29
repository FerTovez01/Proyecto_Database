
<?php 
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');

?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Factura</title>
    <link rel="stylesheet" href="styles4.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../img/bf2.png" alt="Logo">
        </div>
        <button type="button" onclick="location.href='MenuBotones.php'">Volver</button>
    </header>
    <div class="container">
        <h1>Generar Factura</h1>
        <form>
            <div class="column"> 
                <label for="idFactura">Id Factura:</label>
                <input type="text" id="idFactura" name="idFactura" required>

            </div> 
            <div class="column"> 
                <label for="IdCliente">Id Cliente:</label>
                <input type="text" id="IdCliente" name="IdCliente" required>


                <label for="fechaFactura">Fecha de Factura:</label>
                <input type="date" id="fechaFactura" name="fechaFactura" required>


                <label for="PrimerNombre">Nombre del Cliente:</label>
                <input type="text" id="PrimerNombre" name="PrimerNombre" required>

            </div>
            <div class="column">

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>


                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>    

        </div>        
    <div class="container">        

        <div class="items">
            <h2 class="centered">Detalle de Factura</h2>
            </div>
            <table id="detalleFactura">
                <thead>
                    <tr>
                        <th>Producto/Servicio</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="producto1"></td>
                        <td><input type="number" name="cantidad1" oninput="calcularTotal()"></td>
                        <td><input type="number" name="precio1" oninput="calcularTotal()"></td>
                        <td><input type="text" name="subtotal1" readonly></td>
                    </tr>
                </tbody>
            </table>

            <div class="button">
                <button type="button" onclick="agregarFila()">Agregar Producto/Servicio</button>
            </div>

            <div class="total">
                <label for="total">Total:</label>
                <input type="text" id="total" name="total" readonly>
            </div>

            <div class="buttons">
                <button type="reset">Cancelar</button>
                <button type="submit">Generar Factura</button>
            </div>
        </form>
    </div>

    <script>
        function agregarFila() {
            const tabla = document.getElementById("detalleFactura");
            const tbody = tabla.getElementsByTagName("tbody")[0];

            const nuevaFila = tbody.insertRow();
            const celdaProducto = nuevaFila.insertCell(0);
            const celdaCantidad = nuevaFila.insertCell(1);
            const celdaPrecio = nuevaFila.insertCell(2);
            const celdaSubtotal = nuevaFila.insertCell(3);

            celdaProducto.innerHTML = `<input type="text" name="producto${tbody.rows.length}">`;
            celdaCantidad.innerHTML = `<input type="number" name="cantidad${tbody.rows.length}" oninput="calcularTotal()">`;
            celdaPrecio.innerHTML = `<input type="number" name="precio${tbody.rows.length}" oninput="calcularTotal()">`;
            celdaSubtotal.innerHTML = `<input type="text" name="subtotal${tbody.rows.length}" readonly>`;

            calcularTotal();
        }

        function calcularTotal() {
            const tabla = document.getElementById("detalleFactura");
            const tbody = tabla.getElementsByTagName("tbody")[0];
            let total = 0;

            for (let i = 0; i < tbody.rows.length; i++) {
                const cantidad = tbody.rows[i].cells[1].children[0].value;
                const precio = tbody.rows[i].cells[2].children[0].value;
                const subtotal = cantidad * precio;
                tbody.rows[i].cells[3].children[0].value = subtotal;
                total += subtotal;
            }

            document.getElementById("total").value = total;
        }

        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', calcularTotal);
        });
    </script>
</body>
</html>