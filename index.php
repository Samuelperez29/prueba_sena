<?php
require_once __DIR__ . '/database.php';
requireLogin();
$nombre = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <nav>
        <span>Mi App</span>
        <div>
            <span>Hola, <?= $nombre ?></span>
            <a href="logout.php" style="margin-left:10px">Salir</a>
        </div>
    </nav>
    <div class="main-content">
        <h1>Bienvenido</h1>
        <p>Has iniciado sesión correctamente como <?= $nombre ?>.</p>
    </div>
</body>
</html>
