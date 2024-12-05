<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambia si usas otro servidor
$username = "root";       // Cambia según tu configuración
$password = "";           // Cambia según tu configuración
$dbname = "bella_fusion_db"; // Cambia al nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);



if (isset($_GET['idCliente'])) {
    $idCliente = $_GET['idCliente'];
    $query = "SELECT p.primer_Nombre, p.primer_Apellido, d.Calle, d.Colonia_barrio, d.Municipio, d.Departamento, c.telefono, c.Correo
              FROM Cliente c
              JOIN Persona p ON c.Persona_idPersona = p.idPersona
              JOIN Direccion_Persona d ON p.idPersona = d.Persona_idPersona
              WHERE c.idCliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    
    echo json_encode($cliente);
}
?>
