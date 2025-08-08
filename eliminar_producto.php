<?php
session_start();
include('conexion.php');

// Verifica que el usuario sea administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica que se haya enviado un id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepara la consulta para eliminar el producto
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirige a la página de administración con mensaje de éxito
        header("Location: administrar_productos.php?mensaje=producto_eliminado");
        exit();
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
} else {
    // Si no se pasó el id, redirige al panel
    header("Location: administrar_productos.php");
    exit();
}
?>
