<?php
$servername = "sql103.infinityfree.com"; 
$username = "if0_39169363";               // Tu usuario completo
$password = "UNEZ121010";             // Tu contraseña real
$dbname = "if0_39169363_fernando";                 // Tu base de datos completa

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>



