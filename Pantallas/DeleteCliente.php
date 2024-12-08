<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

if (isset($_GET['id'])) {
    $idCliente = $_GET['id'];

    // Primero, obtener Persona_idPersona desde la tabla Cliente
    $sqlCliente = "SELECT Persona_idPersona FROM Cliente WHERE idCliente = $idCliente";
    $resultadoCliente = $conexion->query($sqlCliente);

    if ($resultadoCliente && $resultadoCliente->num_rows > 0) {
        $cliente = $resultadoCliente->fetch_assoc();
        $personaId = $cliente['Persona_idPersona'];

        // Eliminar el registro de Cliente
        $sqlEliminarCliente = "DELETE FROM Cliente WHERE idCliente = $idCliente";
        if ($conexion->query($sqlEliminarCliente) === TRUE) {
            echo "Cliente eliminado exitosamente.<br>";
        } else {
            echo "Error al eliminar cliente: " . $conexion->error . "<br>";
        }

        // Ahora obtenemos el Telefono_idTelefono y Direccion_Persona_idDireccion_Persona de la tabla Telefono y Direccion_Persona
        // Obtener el teléfono de la persona
        $sqlTelefono = "SELECT idTelefono FROM Telefono WHERE Persona_idPersona = $personaId";
        $resultadoTelefono = $conexion->query($sqlTelefono);
        if ($resultadoTelefono && $resultadoTelefono->num_rows > 0) {
            $telefono = $resultadoTelefono->fetch_assoc();
            $telefonoId = $telefono['idTelefono'];

            // Eliminar el teléfono directamente
            $sqlEliminarTelefono = "DELETE FROM Telefono WHERE idTelefono = $telefonoId";
            if ($conexion->query($sqlEliminarTelefono) === TRUE) {
                echo "Teléfono eliminado exitosamente.<br>";
            } else {
                echo "Error al eliminar teléfono: " . $conexion->error . "<br>";
            }
        }

        // Obtener la dirección de la persona
        $sqlDireccion = "SELECT idDireccion_Persona FROM Direccion_Persona WHERE Persona_idPersona = $personaId";
        $resultadoDireccion = $conexion->query($sqlDireccion);
        if ($resultadoDireccion && $resultadoDireccion->num_rows > 0) {
            $direccion = $resultadoDireccion->fetch_assoc();
            $direccionId = $direccion['idDireccion_Persona'];

            // Eliminar la dirección directamente
            $sqlEliminarDireccion = "DELETE FROM Direccion_Persona WHERE idDireccion_Persona = $direccionId";
            if ($conexion->query($sqlEliminarDireccion) === TRUE) {
                echo "Dirección eliminada exitosamente.<br>";
            } else {
                echo "Error al eliminar dirección: " . $conexion->error . "<br>";
            }
        }

        // Ahora eliminamos el registro de Persona
        // Verificar si el registro de Persona está relacionado con el cliente
        $sqlEliminarPersona = "DELETE FROM Persona WHERE idPersona = $personaId";
        if ($conexion->query($sqlEliminarPersona) === TRUE) {
            echo "Persona eliminada exitosamente.<br>";
        } else {
            echo "Error al eliminar persona: " . $conexion->error . "<br>";
        }
    } else {
        echo "Cliente no encontrado.<br>";
    }
} else {
    echo "ID de cliente no proporcionado.<br>";
}

header("Location: ClienteLista.php");
?>
