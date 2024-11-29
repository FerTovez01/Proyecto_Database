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

        // Ahora obtenemos el Telefono_idTelefono y Direccion_Persona_idDireccion_Persona de la tabla Persona
        $sqlPersona = "SELECT Telefono_idTelefono, Direccion_Persona_idDireccion_Persona FROM Persona WHERE idPersona = $personaId";
        $resultadoPersona = $conexion->query($sqlPersona);
        if ($resultadoPersona && $resultadoPersona->num_rows > 0) {
            $persona = $resultadoPersona->fetch_assoc();
            $telefonoId = $persona['Telefono_idTelefono'];
            $direccionId = $persona['Direccion_Persona_idDireccion_Persona'];

            // Eliminar el teléfono si no hay otras personas asociadas a este teléfono
            $sqlTelefono = "SELECT COUNT(*) AS total FROM Persona WHERE Telefono_idTelefono = $telefonoId";
            $resultadoTelefono = $conexion->query($sqlTelefono);
            $dataTelefono = $resultadoTelefono->fetch_assoc();
            if ($dataTelefono['total'] == 0) {
                $sqlEliminarTelefono = "DELETE FROM Telefono WHERE idTelefono = $telefonoId";
                if ($conexion->query($sqlEliminarTelefono) === TRUE) {
                    echo "Teléfono eliminado exitosamente.<br>";
                } else {
                    echo "Error al eliminar teléfono: " . $conexion->error . "<br>";
                }
            } else {
                echo "El teléfono está siendo utilizado por otro usuario, no se elimina.<br>";
            }

            // Eliminar la dirección si no hay otras personas asociadas a esta dirección
            $sqlDireccion = "SELECT COUNT(*) AS total FROM Persona WHERE Direccion_Persona_idDireccion_Persona = $direccionId";
            $resultadoDireccion = $conexion->query($sqlDireccion);
            $dataDireccion = $resultadoDireccion->fetch_assoc();
            if ($dataDireccion['total'] == 0) {
                $sqlEliminarDireccion = "DELETE FROM Direccion_Persona WHERE idDireccion_Persona = $direccionId";
                if ($conexion->query($sqlEliminarDireccion) === TRUE) {
                    echo "Dirección eliminada exitosamente.<br>";
                } else {
                    echo "Error al eliminar dirección: " . $conexion->error . "<br>";
                }
            } else {
                echo "La dirección está siendo utilizada por otra persona, no se elimina.<br>";
            }

            // Eliminar el registro de Persona si no hay otros clientes asociados a esta persona
            $sqlEliminarPersona = "DELETE FROM Persona WHERE idPersona = $personaId";
            if ($conexion->query($sqlEliminarPersona) === TRUE) {
                echo "Persona eliminada exitosamente.<br>";
            } else {
                echo "Error al eliminar persona: " . $conexion->error . "<br>";
            }
        } else {
            echo "No se encontró la persona asociada al cliente.<br>";
        }
    } else {
        echo "Cliente no encontrado.<br>";
    }
}

header("Location: ClienteLista.php");


?>
