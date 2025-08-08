<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Verifica que los campos existan
if (isset($_POST['nombre']) && isset($_POST['precio'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    $sql = "INSERT INTO carrito (user_id, nombre, precio) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $user_id, $nombre, $precio); // isd = int, string, double

    if ($stmt->execute()) {
        header("Location: perfil.php");
        exit();
    } else {
        echo "❌ Error al agregar al carrito: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "❌ Error: nombre o precio no definidos en POST.";
}

$conn->close();
?>

