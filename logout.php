<?php
session_start();
session_unset();
session_destroy();
header("Location: index.html"); // Redirige a index.html al cerrar sesión
exit();
?>
