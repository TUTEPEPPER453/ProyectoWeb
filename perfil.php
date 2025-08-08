<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit; 
}

$user_id = $_SESSION['user_id'];  // ¡Aquí debe ir primero!

// Obtener productos del carrito del usuario
 $sql_carrito = "SELECT id, nombre, precio, cantidad FROM carrito WHERE user_id = ?";
$stmt_carrito = $conn->prepare($sql_carrito);
$stmt_carrito->bind_param("i", $user_id);
$stmt_carrito->execute();
$resultado_carrito = $stmt_carrito->get_result();

$carrito_productos = [];
while ($fila = $resultado_carrito->fetch_assoc()) {
    $carrito_productos[] = $fila;
}
$stmt_carrito->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_id'])) {
    $id_eliminar = intval($_POST['eliminar_id']);

    // Solo eliminar si el producto pertenece al usuario logueado
    $sql_delete = "DELETE FROM carrito WHERE id = ? AND user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $id_eliminar, $user_id);
    $stmt_delete->execute();

    // Redirige para evitar reenvío del formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}



$user_id = $_SESSION['user_id'];

// Procesar cambio de imagen de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["nueva_foto"])) {
    $imagen_tmp = $_FILES["nueva_foto"]["tmp_name"];
    $imagen_tipo = $_FILES["nueva_foto"]["type"];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imagen_tipo, $allowed_types)) {
        echo "Tipo de archivo no permitido.";
        exit;
    }

    if (!getimagesize($imagen_tmp)) {
        echo "El archivo no es una imagen válida.";
        exit;
    }

    $extension = pathinfo($_FILES["nueva_foto"]["name"], PATHINFO_EXTENSION);
    $nombre_unico = uniqid("img_") . '.' . $extension;

    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    $ruta_guardado = "uploads/" . $nombre_unico;

    if (move_uploaded_file($imagen_tmp, $ruta_guardado)) {
        $sql_update = "UPDATE users SET imagen_perfil = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $ruta_guardado, $user_id);
        $stmt_update->execute();
    }
}

// Obtener datos del usuario
$sql_usuario = "SELECT nombre, imagen_perfil FROM users WHERE id = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $user_id);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result();
$usuario = $resultado_usuario->fetch_assoc();

$imagen_mostrar = (isset($usuario['imagen_perfil']) && file_exists($usuario['imagen_perfil']))
    ? $usuario['imagen_perfil']
    : 'default.png';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f7f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 0;
        }

        .perfil-principal {
            text-align: center;
            margin-top: 40px;
            position: relative;
        }

        .perfil-foto-nombre {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .imagen-perfil {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ddd;
        }

        form {
            margin-top: 20px;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        button {
            padding: 8px 16px;
            background-color: #4285f4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #357ae8;
        }

        @media (max-width: 600px) {
            .perfil-principal {
                margin-top: 20px;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>

<div class="perfil-principal">
    <div class="perfil-foto-nombre">
        <img src="<?= htmlspecialchars($imagen_mostrar) ?>" alt="Perfil" class="imagen-perfil">
        <h2>👤 Perfil de <?= htmlspecialchars($usuario['nombre']) ?></h2>

        <?php if (!empty($carrito_productos)): ?>
    <h3 style="margin-top: 30px;">🛒 Productos en tu carrito:</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="margin: 0 auto; background: white;">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th> 
                <th>Acción</th>
            </tr> 
        </thead> 
        <tbody>
            <?php foreach ($carrito_productos as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td>$<?= number_format($item['precio'], 2) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="eliminar_id" value="<?= $item['id'] ?>">
                            <button type="submit" style="background-color: #e53935; color: white; border: none; padding: 4px 8px; cursor: pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="margin-top: 30px;">🛒 Tu carrito está vacío.</p>
<?php endif; ?>


    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="nueva_foto">Cambiar foto de perfil:</label><br>
        <input type="file" name="nueva_foto" accept="image/*" required><br>
        <button type="submit">Actualizar Foto</button>
    </form>
    <form action="logout.php" method="POST" style="margin-top: 20px;">
    <button type="submit" style="background-color: #e53935;">Cerrar Sesión</button>
</form>

</form>
    <form action="index.html" method="POST" style="margin-top: 20px;">
    <button type="submit" style="background-color: #e53935;">inicio</button>
</form>
</div>

</body>
</html>

