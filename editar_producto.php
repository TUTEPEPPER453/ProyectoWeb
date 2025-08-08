<?php
include('conexion.php');
session_start();

// Solo admins pueden acceder (ajusta según tu sistema de roles)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica si se pasó el ID del producto
if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    exit();
}

$id = intval($_GET['id']);

// Obtener datos actuales del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Producto no encontrado.";
    exit();
}

$producto = $resultado->fetch_assoc();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $material = $_POST['material'];
    $codigo = $_POST['codigo'];
    $disponibilidad = $_POST['disponibilidad'];

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $ruta = "uploads/";
        $nombre_imagen = uniqid("prod_") . basename($_FILES["imagen"]["name"]);
        $ruta_imagen = $ruta . $nombre_imagen;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_imagen);

        $sql_update = "UPDATE productos SET nombre=?, descripcion=?, precio=?, talla=?, color=?, material=?, codigo_producto=?, imagen=?, disponibilidad=? WHERE id=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssdssssssi", $nombre, $descripcion, $precio, $talla, $color, $material, $codigo, $ruta_imagen, $disponibilidad, $id);
    } else {
        $sql_update = "UPDATE productos SET nombre=?, descripcion=?, precio=?, talla=?, color=?, material=?, codigo_producto=?, disponibilidad=? WHERE id=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssdsssssi", $nombre, $descripcion, $precio, $talla, $color, $material, $codigo, $disponibilidad, $id);
    }

    if ($stmt->execute()) {
        echo "<div class='mensaje-exito'>✅ Producto actualizado correctamente. <a href='admin_productos.php'>Volver</a></div>";
        exit();
    } else {
        echo "<div class='mensaje-error'>❌ Error al actualizar: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .form-container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .mensaje-exito, .mensaje-error {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border-radius: 5px;
        }

        .mensaje-exito {
            background-color: #d4edda;
            color: #155724;
        }

        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Editar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label>Descripción:</label>
        <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" required>

        <label>Talla:</label>
        <input type="text" name="talla" value="<?= htmlspecialchars($producto['talla']) ?>" required>

        <label>Color:</label>
        <input type="text" name="color" value="<?= htmlspecialchars($producto['color']) ?>" required>

        <label>Material:</label>
        <input type="text" name="material" value="<?= htmlspecialchars($producto['material']) ?>" required>

        <label>Código:</label>
        <input type="text" name="codigo" value="<?= htmlspecialchars($producto['codigo_producto']) ?>" required>

        <label>Disponibilidad:</label>
        <select name="disponibilidad" required>
            <option value="Disponible" <?= $producto['disponibilidad'] == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="No disponible" <?= $producto['disponibilidad'] == 'No disponible' ? 'selected' : '' ?>>No disponible</option>
        </select>

        <label>Imagen (opcional):</label>
        <input type="file" name="imagen">

        <button type="submit">Guardar Cambios</button>
    </form>
</div>

</body>
</html>
