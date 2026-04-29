<?php
require_once __DIR__ . '/database.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error  = '';
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';

    if ($nombre === '' || $correo === '' || $contraseña === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = :correo');
            $stmt->execute(['correo' => $correo]);

            if ($stmt->fetch()) {
                $error = 'Este correo ya está registrado.';
            } else {
                // Guardando contraseña en texto plano (sin cifrar)
                $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, correo, `contraseña`) VALUES (:nombre, :correo, :pass)');
                $stmt->execute([
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'pass'   => $contraseña
                ]);

                setFlash('success', '¡Registro exitoso! Inicia sesión.');
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($correo) ?>" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contraseña" required>
            </div>
            <button type="submit">Registrarse</button>
        </form>
        <p style="text-align:center"><a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>
