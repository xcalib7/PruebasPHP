<?php



$conn= new mysqli('localhost','root',"","contactos");


if($conn->connect_error){
    die("Error en la conexión de la Base de datos");
}



?>