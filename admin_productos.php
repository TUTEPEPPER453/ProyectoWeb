<<?php
session_start();
include('conexion.php');

// Verifica si el usuario es administrador (usando el campo 'rol')
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}


// Obtener todos los productos
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Productos</title>
    <style>
        table {
            width: 95%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #444;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border: none;
            color: white;
            border-radius: 5px;
        }
        .editar {
            background-color: #007bff;
        }
        .eliminar {
            background-color: #dc3545;
        }
        .agregar {
            background-color: #28a745;
            margin: 15px;
            padding: 10px 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <h2 style="text-align:center;">🛠️ Gestión de Productos</h2>
    <a href="admin_panel.php" class="btn agregar">Panel de Control</a>

    <div style="text-align:center;">
        <a href="productos.php" class="btn agregar">➕ Agregar Producto</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Talla</th>
            <th>Color</th>
            <th>Material</th>
            <th>Código</th>
            <th>Imagen</th>
            <th>Disponibilidad</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td>$<?= $row['precio'] ?></td>
            <td><?= htmlspecialchars($row['talla']) ?></td>
            <td><?= htmlspecialchars($row['color']) ?></td>
            <td><?= htmlspecialchars($row['material']) ?></td>
            <td><?= htmlspecialchars($row['codigo_producto']) ?></td>
            <td><img src="<?= $row['imagen'] ?>" width="50" height="50" alt="Imagen"></td>
            <td><?= htmlspecialchars($row['disponibilidad']) ?></td>
            <td>
                <a class="btn editar" href="editar_producto.php?id=<?= $row['id'] ?>">Editar</a>
                <a class="btn eliminar" href="eliminar_producto.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>