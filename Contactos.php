<?php

require_once('Conexion.php');



function getContactos()
{

    global $conn;
    $result = $conn->query("SELECT * FROM contactos");

    $contactos = [];

    while ($row = $result->fetch_assoc()) {
        $contactos[] = $row;
    }

    $conn->close();

    return $contactos;
}

function insertarContacto($nombre, $apellido, $dni)
{
    global $conn;

    try {

        $conn->begin_transaction();

        if (validarObligatorio($nombre, $apellido, $dni)) {
            throw new Exception("Error: Todos los campos son obligatorios.");
        }


        if (!validarNombre($nombre)) {
            throw new Exception("Error: El nombre  solo pueden contener letras.");
        }
        if (!validarApellido($apellido)) {
            throw new Exception("Error: El  apellido solo pueden contener letras.");
        }


        if (!validarDNI($dni)) {
            throw new Exception("Error: Formato del DNI no  válido.");
        }



        $insert = $conn->prepare("INSERT INTO contactos (nombre,apellido,dni) VALUES (?, ?, ?)");

        $insert->bind_param("sss", $nombre, $apellido, $dni);

        $insert->execute();



        if ($insert->affected_rows > 0) {
            $conn->commit();
            $resultado = ["Se ha insertado el contacto correctamente"];
        } else {
            $conn->rollback();
            $resultado = ["Error al insertar el contacto: No se realizaron cambios"];
        }

        $insert->close();
    } catch (Exception $e) {
        $resultado = ["Error al insertar el contacto: " . $e->getMessage()];
    }

    return $resultado;
}





function actualizarContacto($nombre, $apellido, $dni, $id)
{

    global $conn;

    $resultado = ["Error: No se ha actualizado ningún contacto"];

    try {


        $updateQuery = "UPDATE contactos SET ";
        $updateParams = [];

        if (!empty($nombre)) {

            if (!validarNombre($nombre)) {
                throw new Exception("Error: El nombre solo pueden contener letras.");
            }

            $updateQuery .= "nombre = ?, ";

            array_push($updateParams, $nombre);
        }

        if (!empty($apellido)) {

            if (!validarApellido($apellido)) {
                throw new Exception("Error: El  apellido solo pueden contener letras.");
            }

            $updateQuery .= "apellido = ?, ";

            array_push($updateParams, $apellido);
        }

        if (!empty($dni)) {

            if (!validarDNI($dni)) {
                throw new Exception("Error: El formato del DNI no es válido.");
            }

            $updateQuery .= "dni = ?, ";

            array_push($updateParams, $dni);
        }

        // Eliminar la última coma y espacio si hay campos para actualizar
        if (!empty($updateParams)) {
            $updateQuery = rtrim($updateQuery, ', ');

            // Agregar el ID a los parámetros

            array_push($updateParams, $id);

            $updateQuery .= " WHERE id = ?";

            // Preparar la consulta
            $update = $conn->prepare($updateQuery);



            // Vincular los parámetros
            $types = str_repeat('s', count($updateParams) - 1) . 'i';
            $update->bind_param($types, ...$updateParams);



            $update->execute();

            if ($update->affected_rows > 0) {
                $resultado = ["Se ha actualizado el contacto correctamente"];
            }
        } else {
            $resultado = ["No hay campos para actualizar"];
        }
    } catch (Exception $e) {

        $resultado = ["Error al actualizar el contacto: " . $e->getMessage()];
    }

    return $resultado;
}

function borrarContacto($id)
{

    global $conn;

    try {
        $delete = $conn->prepare("DELETE FROM contactos WHERE id= ?");

        $delete->bind_param("i", $id);

        $delete->execute();



        if ($delete->affected_rows > 0) {
            return ["Se ha eliminado el contacto correctamente"];
        } else {
            return ["Error: No se ha eliminado ningún contacto"];
        }
    } catch (Exception $e) {
        $resultado = ["Error al borrar el contacto: " . $e->getMessage()];
    }



    $delete->close();
}

function validarNombre($nombre)
{

    return preg_match('/^[A-Za-z]+$/', $nombre);
}
function validarApellido($apellido)
{

    return preg_match('/^[A-Za-z]+$/', $apellido);
}


function validarDNI($dni)
{

    return preg_match('/^\d{8}[A-Za-z]$/', $dni);
}

function validarObligatorio($nombre, $apellido, $dni)
{
    return empty($nombre) || empty($apellido) || empty($dni);
}
