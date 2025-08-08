<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "usuarios_db";

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Consultar las noticias
$sql = "SELECT titulo, contenido, fecha FROM noticias ORDER BY fecha DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Noticias</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
        .noticia { background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        .noticia h2 { margin-top: 0; }
        .noticia small { color: #555; }
    </style>
</head>
<body>

    <h1>📰 Últimas Noticias</h1>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <div class="noticia">
                <h2><?php echo htmlspecialchars($fila['titulo']); ?></h2>
                <small><?php echo date("d/m/Y H:i", strtotime($fila['fecha'])); ?></small>
                <p><?php echo nl2br(htmlspecialchars($fila['contenido'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay noticias por el momento.</p>
    <?php endif; ?>

    <a href="index.php">⬅ Volver al inicio</a>

</body>
</html>

<?php
$conn->close();
?>
