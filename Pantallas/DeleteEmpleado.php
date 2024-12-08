<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\xampp\htdocs\ProyectoWeb\php');
include("conexion.php");

if (isset($_GET['id'])) {
    $idEmpleado = $_GET['id'];

    // Primero, obtener Persona_idPersona desde la tabla Empleado
    $sqlEmpleado = "SELECT Persona_idPersona, Cargo_idCargo FROM Empleado WHERE idEmpleado = $idEmpleado";
    $resultadoEmpleado = $conexion->query($sqlEmpleado);

    if ($resultadoEmpleado && $resultadoEmpleado->num_rows > 0) {
        $empleado = $resultadoEmpleado->fetch_assoc();
        $personaId = $empleado['Persona_idPersona'];
        $cargoId = $empleado['Cargo_idCargo'];

        // Eliminar el registro de Empleado
        $sqlEliminarEmpleado = "DELETE FROM Empleado WHERE idEmpleado = $idEmpleado";
        if ($conexion->query($sqlEliminarEmpleado) === TRUE) {
            echo "Empleado eliminado exitosamente.<br>";
        } else {
            echo "Error al eliminar empleado: " . $conexion->error . "<br>";
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
        // Verificar si el registro de Persona está relacionado con el empleado
        $sqlEliminarPersona = "DELETE FROM Persona WHERE idPersona = $personaId";
        if ($conexion->query($sqlEliminarPersona) === TRUE) {
            echo "Persona eliminada exitosamente.<br>";
        } else {
            echo "Error al eliminar persona: " . $conexion->error . "<br>";
        }

        // Eliminar el Cargo solo si no está relacionado con otros empleados
        $sqlEliminarCargo = "SELECT COUNT(*) AS count FROM Empleado WHERE Cargo_idCargo = $cargoId";
        $resultadoCargo = $conexion->query($sqlEliminarCargo);
        $cargo = $resultadoCargo->fetch_assoc();

        if ($cargo['count'] == 0) {
            // Si no hay otros empleados con el mismo cargo, eliminamos el cargo
            $sqlEliminarCargo = "DELETE FROM Cargo WHERE idCargo = $cargoId";
            if ($conexion->query($sqlEliminarCargo) === TRUE) {
                echo "Cargo eliminado exitosamente.<br>";
            } else {
                echo "Error al eliminar cargo: " . $conexion->error . "<br>";
            }
        }

    } else {
        echo "Empleado no encontrado.<br>";
    }
} else {
    echo "ID de empleado no proporcionado.<br>";
}

header("Location: EmpleadoLista.php");
?>
