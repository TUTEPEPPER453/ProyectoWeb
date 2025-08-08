<?php
session_start();
include("conexion.php");

$message = '';

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit();
}

// Obtener ID del usuario logueado
$usuario_nombre = $_SESSION['usuario'];
$sql = "SELECT id FROM users WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario_nombre);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$usuario_id = $usuario['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validación básica
  $nombre = $_POST['nombre'] ?? '';
  $direccion = $_POST['direccion'] ?? '';
  $metodo = $_POST['pago'] ?? '';

  if ($nombre && $direccion && $metodo) {
    $sql = "INSERT INTO compras (usuario_id, nombre, direccion, metodo_pago) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $usuario_id, $nombre, $direccion, $metodo);

    if ($stmt->execute()) {
      $message = "✅ ¡Compra registrada exitosamente!";
    } else {
      $message = "❌ Error al registrar la compra.";
    }

    $stmt->close();
  } else {
    $message = "⚠️ Por favor completa todos los campos.";
  }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprar</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; text-align: center; }
    form { background: #fff; padding: 20px; display: inline-block; border-radius: 10px; }
    input, select { width: 100%; margin-bottom: 10px; padding: 10px; }
    button { background: #c000c0; color: white; padding: 10px 20px; border: none; cursor: pointer; }
    .message { margin-top: 20px; font-weight: bold; }
  </style>
</head>
<body>
 <a href="tienda.php" class="btn ">Comprar</a>
  <h1>Preparado todo para tu compra</h1>

  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <input type="text" name="nombre" placeholder="Tu nombre completo" required>
    <input type="text" name="direccion" placeholder="Dirección de entrega" required>
    <select name="pago" required>
      <option value="Tarjeta">Tarjeta</option>
      <option value="PayPal">PayPal</option>
      <option value="OXXO">OXXO</option>
    </select>
    <button type="submit">Confirmar compra</button>
  </form>


</body>
</html>
