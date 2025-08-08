<?php
include('conexion.php');
session_start();

$sql = "SELECT * FROM productos WHERE disponibilidad = 'Disponible'"; // Solo productos disponibles
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .contenedor-productos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .producto {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 280px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .producto img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .producto h3 {
            margin: 10px 0 5px;
        }
        .producto p {
            font-size: 14px;
            color: #555;
        }
        .btn-comprar {
            display: inline-block;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn-comprar:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<a href="index.html" class="btn ">inicio</a>

<h1 style="text-align:center;">🛒 Tienda</h1>

<div class="contenedor-productos">
<?php while($producto = $resultado->fetch_assoc()): ?>
    <div class="producto">
        <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Producto">
        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
        <p><strong>Precio:</strong> $<?= htmlspecialchars($producto['precio']) ?></p>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($producto['descripcion']) ?></p>
        <p><strong>Talla:</strong> <?= htmlspecialchars($producto['talla']) ?></p>
        <p><strong>Color:</strong> <?= htmlspecialchars($producto['color']) ?></p>
        <form method="POST" action="comprar.php">
            <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
            <button type="submit" class="btn-comprar">Comprar</button>
        </form>
        <form action="agregar_carrito.php" method="POST">
   <input type="hidden" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">

    <input type="hidden" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>"
    >
    <button type="submit" class="btn-comprar">Agregar al carrito</button>
</form>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>