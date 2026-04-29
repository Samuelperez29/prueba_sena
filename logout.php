<?php
/**
 * Cierra la sesión del usuario y redirige al login.
 */

require_once __DIR__ . '/database.php';
session_unset();
session_destroy();

header('Location: login.php');
exit;
