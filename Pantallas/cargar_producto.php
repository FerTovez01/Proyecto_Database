<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia si usas otro servidor
$username = "root";       // Cambia según tu configuración
$password = "";           // Cambia según tu configuración
$dbname = "bella_fusion_db"; // Cambia al nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar que se haya recibido el parámetro idProducto
if (isset($_GET['idProducto'])) {
    $idProducto = intval($_GET['idProducto']);

    // Consultar los datos del producto
    $sql = "SELECT 
                Nombre_Producto,
                Precio
            FROM producto
            WHERE idProducto = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Devolver los datos como JSON
        echo json_encode([
            'nombreProducto' => $row['Nombre_Producto'],
            'precio' => $row['Precio'],
         
        ]);
    } else {
        echo json_encode(['error' => 'Producto no encontrado']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No se proporcionó idProducto']);
}

$conn->close();
?>
