<?php
// Configurar para que no se muestren errores
ini_set('display_errors', 0);  // Desactivar mostrar errores
ini_set('log_errors', 1);      // Activar log de errores
ini_set('error_log', '/path/to/your/php-error.log'); // Cambia esto a una ruta válida de log en tu servidor

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "Laspalmas721";
$dbname = "login_rancho";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    // Registrar el error en el log
    error_log("Conexión fallida: " . $conn->connect_error);
    
    // Mostrar un mensaje amigable para el usuario
    echo json_encode(["error" => "Error de conexión a la base de datos."]);
    exit();
}

// Consulta para obtener los productos
$sql = "SELECT id, nombre, descripcion, precio, metros_disponibles, imagen FROM productos";
$result = $conn->query($sql);

// Verificar si hay productos
$productos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Retornar los productos como JSON
echo json_encode($productos);

// Cerrar la conexión
$conn->close();
?>
