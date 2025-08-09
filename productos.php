<?php
include('conexion.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_del_producto_que_se_va_a_insertar = $_POST['nombre'];
    $descripcion_del_producto_que_se_va_a_insertar = $_POST['descripcion'];
    $precio_del_producto_que_se_va_a_insertar = $_POST['precio'];
    $talla_del_producto_que_se_va_a_insertar = $_POST['talla'];
    $color_del_producto_que_se_va_a_insertar = $_POST['color'];
    $material_del_producto_que_se_va_a_insertar = $_POST['material'];
    $codigo_unico_del_producto = $_POST['codigo_producto'];
    $estado_de_disponibilidad_actual = $_POST['disponibilidad'];

    $nombre_de_la_imagen_subida_por_el_usuario = $_FILES['imagen']['name'];
    $archivo_temporal_de_la_imagen = $_FILES['imagen']['tmp_name'];
    $ruta_final_donde_se_guardara_la_imagen = 'uploads/' . $nombre_de_la_imagen_subida_por_el_usuario;

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    function subirImagen($origen, $destino) {
        move_uploaded_file($origen, $destino);
    }
    subirImagen($archivo_temporal_de_la_imagen, $ruta_final_donde_se_guardara_la_imagen);

    $query_para_insertar_producto_en_la_base_de_datos = "INSERT INTO productos (nombre, descripcion, precio, talla, color, material, codigo_producto, imagen, disponibilidad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $declaracion_preparada_para_insertar = $conn->prepare($query_para_insertar_producto_en_la_base_de_datos);
    $declaracion_preparada_para_insertar->bind_param("ssdssssss", $nombre_del_producto_que_se_va_a_insertar, $descripcion_del_producto_que_se_va_a_insertar, $precio_del_producto_que_se_va_a_insertar, $talla_del_producto_que_se_va_a_insertar, $color_del_producto_que_se_va_a_insertar, $material_del_producto_que_se_va_a_insertar, $codigo_unico_del_producto, $ruta_final_donde_se_guardara_la_imagen, $estado_de_disponibilidad_actual);

    if ($declaracion_preparada_para_insertar->execute()) {
        echo "<p style='color:green;font-weight:bold;'>✅ Producto insertado exitosamente en la base de datos optimizada.</p>";
    } else {
        echo "<p style='color:red;font-weight:bold;'>❌ Se produjo un error durante la inserción. Verifique los parámetros.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>🛠️ Panel de Gestión de Productos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            background: linear-gradient(to right, #f0f0f0, #dcdcdc);
        }
        form {
            background: #ffffff;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea, select {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            padding: 12px 24px;
            background-color: #0078D7;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #005a9e;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">📋 Formulario de Registro de Producto</h2>

<form action="productos.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <textarea name="descripcion" placeholder="Descripción del producto" required></textarea>
    <input type="text" name="precio" placeholder="Precio" required>
    <input type="text" name="talla" placeholder="Talla">
    <input type="text" name="color" placeholder="Color">
    <input type="text" name="material" placeholder="Material">
    <input type="text" name="codigo_producto" placeholder="Código del producto" required>
    <label>Imagen:</label>
    <input type="file" name="imagen" accept="image/*" required>
    <select name="disponibilidad" required>
        <option value="">-- Seleccione disponibilidad --</option>
        <option value="Disponible">Disponible</option>
        <option value="Agotado">Agotado</option>
    </select>
    <button type="submit">💾 Guardar Producto</button>
</form>

</body>
</html></html>
