<?php
require_once 'conexion.php'; // Tu archivo de conexión a la BD

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['productos'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$productos = $data['productos'];
$fecha = date('Y-m-d H:i:s');

try {
    $conn->begin_transaction();

    // Crear la venta
    $conn->query("INSERT INTO ventas (fecha) VALUES ('$fecha')");
    $id_venta = $conn->insert_id;

    foreach ($productos as $producto) {
        $nombre = $conn->real_escape_string($producto['title']);
        $cantidad = floatval($producto['quantity']);
        $precio = floatval($producto['price']);

        // Obtener el ID del producto
        $res = $conn->query("SELECT id, metros_disponibles FROM productos WHERE nombre = '$nombre'");
        if ($res->num_rows === 0) throw new Exception("Producto '$nombre' no encontrado.");
        $row = $res->fetch_assoc();

        $id_producto = $row['id'];
        $stock = $row['metros_disponibles'];

        if ($cantidad > $stock) {
            throw new Exception("No hay suficiente stock de '$nombre'.");
        }

        // Insertar detalle venta
        $conn->query("INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario)
                      VALUES ($id_venta, $id_producto, $cantidad, $precio)");

        // Actualizar inventario
        $nuevoStock = $stock - $cantidad;
        $conn->query("UPDATE productos SET metros_disponibles = $nuevoStock WHERE id = $id_producto");
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
