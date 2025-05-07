<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "login_rancho");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];
    $confirmar = $_POST["confirmar"];

    // Validar contraseña segura: al menos 8 caracteres, una mayúscula, un número, un símbolo
    $regex = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($regex, $contrasena)) {
        die("La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.");
    }

    if ($contrasena !== $confirmar) {
        die("Las contraseñas no coinciden.");
    }

    // Validar que el usuario o correo no existan
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo = ?");
    $stmt->bind_param("ss", $usuario, $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("El usuario o correo ya están registrados.");
    }
    $stmt->close();

    // Encriptar la contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, correo, contrasena) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $usuario, $correo, $hash);

    if ($stmt->execute()) {
        echo "Registro exitoso. <a href='login.php'>Iniciar sesión</a>";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
