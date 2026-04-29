<?php
require_once __DIR__ . '/database.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo      = trim($_POST['correo'] ?? '');
    $contraseña  = $_POST['contraseña'] ?? '';

    if ($correo === '' || $contraseña === '') {
        $error = 'Campos obligatorios.';
    } else {
        try {
            $pdo  = getConnection();
            // Comparación directa en texto plano
            $stmt = $pdo->prepare('SELECT id, nombre FROM usuarios WHERE correo = :correo AND `contraseña` = :pass');
            $stmt->execute([
                'correo' => $correo,
                'pass'   => $contraseña
            ]);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                header('Location: index.php');
                exit;
            }
            $error = 'Datos incorrectos.';
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($correo) ?>" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contraseña" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <p style="text-align:center"><a href="register.php">Regístrate</a></p>
    </div>
</body>
</html>
