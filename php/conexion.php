<?php
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
?>
