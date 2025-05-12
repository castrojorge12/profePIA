<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => false]);
} else {
    echo json_encode([
        'autenticado' => true,
        'correo' => $_SESSION['correo'], // guarda este dato en el login
        'nombre' => $_SESSION['nombre']  // opcional, para personalizar el mensaje
    ]);
}
?>
