<?php
include('conexion.php');
session_start();

// Asegúrate de que solo un administrador pueda acceder
// Puedes agregar validación aquí si es necesario

// Eliminar usuario si se envía ID por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $id = $_POST['eliminar_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Obtener todos los usuarios
$sql = "SELECT id, nombre, correo, imagen_perfil, creado_en FROM users";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
        }

        table {
            margin: auto;
            width: 90%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border: none;
            color: black;
            border-radius: 5px;
        }
    </style>
</head>
<body>
 <a href="admin_panel.php" class="btn">Panel de Control</a>
<h1>👤 Gestión de Usuarios</h1>


<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>imagen_perfil</th>
         <th>creando_en</th>
    </tr>
    <?php while($usuario = $resultado->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($usuario['id']) ?></td>
        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
        <td><?= htmlspecialchars($usuario['correo']) ?></td>
        <td><?= htmlspecialchars($usuario['creado_en']) ?></td>
<td><?= htmlspecialchars($usuario['imagen_perfil']) ?></td>
        <td>
            <form method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                <input type="hidden" name="eliminar_id" value="<?= $usuario['id'] ?>">
                <button type="submit" class="btn-eliminar">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>