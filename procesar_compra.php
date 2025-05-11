<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // <--- Necesario para acceder al usuario_id

require_once 'conexion.php'; // Ya crea $conexion como PDO

// Registrar contenido recibido para depuración
file_put_contents('log_request.txt', file_get_contents('php://input'));

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

// Obtener y decodificar JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar formato de datos
if (!$data || !isset($data['productos']) || !is_array($data['productos'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$productos = $data['productos'];
$fecha = date('Y-m-d H:i:s');
$productos_comprados = [];

try {
    $conexion->beginTransaction();

    // Insertar nueva venta con usuario_id
    $stmtVenta = $conexion->prepare("INSERT INTO ventas (fecha, usuario_id) VALUES (:fecha, :usuario_id)");
    $stmtVenta->execute([
        ':fecha' => $fecha,
        ':usuario_id' => $usuario_id
    ]);
    $id_venta = $conexion->lastInsertId();

    foreach ($productos as $producto) {
        $nombre = trim($producto['title'] ?? '');
        $cantidad = floatval($producto['quantity'] ?? 0);
        $precio = floatval($producto['price'] ?? 0);

        if ($nombre === '' || $cantidad <= 0 || $precio <= 0) {
            throw new Exception("Datos inválidos en producto.");
        }

        // Buscar el producto por nombre
        $stmtBuscar = $conexion->prepare("SELECT id, metros_disponibles FROM productos WHERE nombre = :nombre");
        $stmtBuscar->execute([':nombre' => $nombre]);
        $row = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Producto '$nombre' no encontrado.");
        }

        $id_producto = $row['id'];
        $stock = floatval($row['metros_disponibles']);

        if ($cantidad > $stock) {
            throw new Exception("No hay suficiente stock de '$nombre'. Disponible: $stock, solicitado: $cantidad");
        }

        // Calcular subtotal
        $subtotal = $precio * $cantidad;

        // Insertar detalle de venta
        $stmtDetalle = $conexion->prepare("INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, subtotal) 
                                           VALUES (:venta_id, :producto_id, :cantidad, :subtotal)");
        $stmtDetalle->execute([
            ':venta_id' => $id_venta,
            ':producto_id' => $id_producto,
            ':cantidad' => $cantidad,
            ':subtotal' => $subtotal
        ]);

        // Actualizar stock
        $nuevoStock = $stock - $cantidad;
        $stmtUpdate = $conexion->prepare("UPDATE productos SET metros_disponibles = :nuevoStock WHERE id = :id_producto");
        $stmtUpdate->execute([
            ':nuevoStock' => $nuevoStock,
            ':id_producto' => $id_producto
        ]);

        // Guardar producto comprado
        $productos_comprados[] = [
            'nombre' => $nombre,
            'cantidad' => $cantidad,
            'precio' => $precio,
            'subtotal' => $subtotal
        ];
    }

    // Confirmar transacción
    $conexion->commit();

    // Responder con éxito
    echo json_encode([
        'success' => true,
        'productos' => $productos_comprados
    ]);

} catch (Exception $e) {
    $conexion->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>