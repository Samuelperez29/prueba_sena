<?php
/**
 * Configuración de base de datos y sesión.
 */

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
function getConnection(): PDO
{
    $dsn = 'mysql:host=localhost;dbname=login;charset=utf8mb4';

    return new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
}

// Verificar si hay sesión activa
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

// Redirigir si no está logueado
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Mensajes flash entre redirecciones
function setFlash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
