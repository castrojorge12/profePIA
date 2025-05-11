<?php
session_start();

// Configuración de la base de datos
$servidor = "localhost";  // o "127.0.0.1"
$usuarioBD = "root";  // Nombre de usuario de MySQL
$contrasenaBD = "Laspalmas721";  // Contraseña de MySQL (deja vacío si no tiene contraseña)
$nombreBD = "login_rancho";  // Nombre de la base de datos

// Conexión a la base de datos
$conn = new mysqli($servidor, $usuarioBD, $contrasenaBD, $nombreBD);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario_valido = "";
$contrasena_valida = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Consulta para verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);  // Vincula el parámetro (usuario)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si el usuario existe, verificar la contraseña
        $row = $result->fetch_assoc();
        $usuario_valido = $row["usuario"];
        $contrasena_valida = $row["contrasena"];

        // Verificar la contraseña (usamos password_verify si se usó hash en el registro)
        if (password_verify($contrasena, $contrasena_valida)) {
            // Si las credenciales son correctas, iniciar sesión
            $_SESSION["usuario"] = $usuario;
            header("Location: index.php");  // Redirige a la página principal de la tienda
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estphp.css" />
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)) echo "<p class='mensaje-error'>$error</p>"; ?>
        <form method="POST" action="">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
            
            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>
            
            <button type="submit">Ingresar</button>
        </form>

        <p>¿No tienes cuenta?</p>
        <div class="enlace">
            <button onclick="location.href='registro.php'">Registrar Usuario</button>
        </div>

        <button class="volver" onclick="location.href='index.php'">Volver al inicio</button>
    </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>
