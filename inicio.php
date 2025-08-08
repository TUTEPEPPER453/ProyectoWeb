<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Página de Inicio - Victorious</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('https://images.unsplash.com/photo-1557682224-5b8590cd9ec5') no-repeat center center fixed;
      background-size: cover;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .fondo-negro {
      background-color: rgba(0, 0, 0, 0.75);
      padding: 40px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }

    h1 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 30px;
    }

    a {
      text-decoration: none;
      color: white;
      background-color: #c000c0;
      padding: 10px 20px;
      margin: 10px;
      border-radius: 5px;
      transition: background 0.3s;
    }

    a:hover {
      background-color: #a000a0;
    }
  </style>
</head>
<body>

  <div class="fondo-negro">
    <h1>Bienvenido a Victorious</h1>
    <p>No has iniciado sesión aún.</p>
    <a href="login.php">Iniciar sesión</a>
    <a href="registro.php">Registrarse</a>
  </div>

</body>
</html>

