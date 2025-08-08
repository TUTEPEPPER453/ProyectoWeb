<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Verificar si el email ya está registrado
    $check_sql = "SELECT id FROM users WHERE correo = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "❌ Este correo ya está registrado. Intenta con otro.";
        exit();
    }

    // Insertar el nuevo usuario
    $sql = "INSERT INTO users (nombre, contrasena, correo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "❌ Error al registrar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Registro - Victorious</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('https://i.pinimg.com/1200x/05/41/ae/0541aed8898fd5606978afbaca085db7.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      height: 100vh;
    }

    .logo {
      width: 100%;
      background-color: black;
      text-align: center;
    }

    .logo img {
      max-width: 100%;
      height: auto;
    }

    body::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background-color: black;
      opacity: 0.6;
      z-index: -1;
    }

    .form-wrapper {
      background-color: rgba(0, 0, 0, 0.75);
      padding: 60px;
      width: 400px;
      margin: 100px auto;
      border-radius: 4px;
      position: relative;
      z-index: 1;
    }

    h1 {
      margin-bottom: 30px;
      text-align: center;
    }

    .form-control {
      margin-bottom: 20px;
    }

    .form-control label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
    }

    .form-control input {
      width: 100%;
      padding: 10px;
      border: none;
      background: #333;
      color: white;
      border-radius: 4px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: #e50914;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 10px;
    }

    input[type="submit"]:hover {
      background-color: #f40612;
    }
  </style>
</head>
<body>
  <div class="form-wrapper">
    <h1>Registro</h1>
    <form action="registro.php" method="POST">
      <div class="form-control">
        <label for="username">Usuario</label>
        <input type="text" id="username" name="username" required>
      </div>  

      <div class="form-control">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="form-control">
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required />
      </div>

      <input type="submit" value="Registrarse">
    </form>
  </div>
</body>
</html>

