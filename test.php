<?php
require_once 'config/database.php';

$database = new Database();
$conn = $database->getConnection();

if($conn){
    echo "Conexión exitosa al servidor remoto ";
} else {
    echo "No se pudo conectar";
}
?>
