<?php
$host = "localhost";
$db = "login_rancho";
$user = "root";
$pass = "Laspalmas721"; // Deja vacío si no tienes contraseña en XAMPP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    $confirmar = $_POST["confirmar"];

    // Validación de seguridad
    if (strlen($contrasena) < 8 || 
        !preg_match("/[A-Z]/", $contrasena) ||
        !preg_match("/[a-z]/", $contrasena) ||
        !preg_match("/\d/", $contrasena) ||
        !preg_match("/[^a-zA-Z\d]/", $contrasena)) {
        $mensaje = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.";
    } elseif ($contrasena !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        // Encriptar y guardar
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $correo, $hash);

        if ($stmt->execute()) {
            $mensaje = "Usuario registrado correctamente.";
        } else {
            $mensaje = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estregisterphp.css" />
</head>
<body>
    <div class="container">
        <h2>Registro</h2>
        <?php if (!empty($mensaje)) echo "<p class='mensaje-error'>$mensaje</p>"; ?>

        <form method="POST" action="">
            <label>Usuario:</label>
            <input type="text" name="usuario" required><br><br>

            <label>Correo electrónico:</label>
            <input type="email" name="correo" required><br><br>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required><br><br>

            <label>Confirmar contraseña:</label>
            <input type="password" name="confirmar" required><br><br>

            <button type="submit">Registrar</button>
        </form>

        <div class="enlace">
            <button class="volver" onclick="location.href='login.php'">Volver al Login</button>
        </div>
    </div>
</body>
</html>
