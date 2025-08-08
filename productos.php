<?php
include('conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $talla = $_POST['talla'];
    $color = $_POST['color'];
    $material = $_POST['material'];
    $codigo = $_POST['codigo_producto'];
    $disponibilidad = $_POST['disponibilidad'];

    // Imagen
    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $ruta_imagen = 'uploads/' . $imagen;

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    move_uploaded_file($imagen_tmp, $ruta_imagen);

    $sql = "INSERT INTO productos (nombre, descripcion, precio, talla, color, material, codigo_producto, imagen, disponibilidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssssss", $nombre, $descripcion, $precio, $talla, $color, $material, $codigo, $ruta_imagen, $disponibilidad);

    if ($stmt->execute()) {
        echo "<p>✅ Producto insertado correctamente.</p>";
    } else {
        echo "<p>❌ Error al insertar el producto.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f2f2f2;
        }
        form {
            background: white;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
        }
        input, textarea, select {
            width: 100%;
            margin-bottom: 15px;
            padding: 8px;
        }
        button {
            padding: 10px 20px;
            background-color: #4285f4;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #3367d6;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Agregar Nuevo Producto</h2>

<form action="productos.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <textarea name="descripcion" placeholder="Descripción del producto" required></textarea>
    <input type="number" step="0.01" name="precio" placeholder="Precio" required>
    <input type="text" name="talla" placeholder="Talla">
    <input type="text" name="color" placeholder="Color">
    <input type="text" name="material" placeholder="Material">
    <input type="text" name="codigo_producto" placeholder="Código del producto" required>
    <label>Imagen:</label>
    <input type="file" name="imagen" accept="image/*" required>
    <select name="disponibilidad" required>
        <option value="">-- Disponibilidad --</option>
        <option value="Disponible">Disponible</option>
        <option value="Agotado">Agotado</option>
    </select>
    <button type="submit">Guardar Producto</button>
</form>

</body>
</html>