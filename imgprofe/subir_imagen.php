<?php
$servername = "localhost";
$username = "root";
$password = "Laspalmas721";
$dbname = "login_rancho";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreProducto = $_POST['nombre'];
    $precio = $_POST['precio'];

    $imagenNombre = $_FILES['imagen']['name'];
    $imagenTmp = $_FILES['imagen']['tmp_name'];
    $rutaDestino = 'imgprofe/' . basename($imagenNombre);

    if (move_uploaded_file($imagenTmp, $rutaDestino)) {
        $sql = "INSERT INTO productos (nombre, precio, imagen) VALUES ('$nombreProducto', $precio, '$rutaDestino')";
        if ($conn->query($sql) === TRUE) {
            echo "Producto agregado exitosamente.";
        } else {
            echo "Error al insertar: " . $conn->error;
        }
    } else {
        echo "Error al mover la imagen.";
    }
} else {
    echo "No se subió ninguna imagen.";
}

$conn->close();
?>
