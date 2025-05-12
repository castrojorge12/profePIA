<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'login_rancho';
$user = 'root'; // Ajusta el usuario de tu base de datos
$pass = 'Laspalmas721'; // Ajusta la contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el último pedido
    $stmt = $pdo->query("SELECT * FROM ventas ORDER BY fecha DESC LIMIT 1");
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);
    $venta_id = $venta['id'];

    // Obtener los productos del pedido
    $stmt = $pdo->prepare("SELECT p.nombre, p.precio, dv.cantidad, dv.subtotal
                           FROM detalle_ventas dv
                           JOIN productos p ON p.id = dv.producto_id
                           WHERE dv.venta_id = ?");
    $stmt->execute([$venta_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcular totales
$totalConIVA = 0;
foreach ($productos as $producto) {
    $totalConIVA += $producto['subtotal'];
}

// Calcular IVA incluido (16% de 116%)
$iva = $totalConIVA * (16 / 116);
$subtotalSinIVA = $totalConIVA - $iva;

    // Convertir los productos a formato JSON
    $productosJSON = json_encode($productos);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Facturación</title>
    <link rel="stylesheet" href="styles_fac.css">
</head>
<body>
    <header>
        <h1>Datos de Facturación</h1>
    </header>

    <main>
        <!-- Desglose del total -->
        <h2>Desglose de la compra</h2>
        <div id="cart-details">
            <p><strong>Subtotal sin IVA:</strong> <span id="subtotal"><?php echo number_format($subtotalSinIVA, 2); ?></span></p>
            <p><strong>IVA (incluido en el precio):</strong> <span id="iva"><?php echo number_format($iva, 2); ?></span></p>
            <p><strong>Total (ya con IVA):</strong> <span id="total-with-tax"><?php echo number_format($totalConIVA, 2); ?></span></p>
        </div>

        <form id="billing-form">
            <label for="billing-type">Selecciona el tipo de facturación:</label>
            <select id="billing-type" required>
                <option value="">Selecciona...</option>
                <option value="fisica">Persona Física</option>
                <option value="moral">Persona Moral</option>
                <option value="organizacion">Organización</option>
            </select>

            <!-- Campos para Persona Física -->
            <div id="fisica-fields" class="hidden">
                <label for="name">Nombre Completo:</label>
                <input type="text" id="name" placeholder="Ingresa tu nombre completo">
                <label for="rfc">RFC:</label>
                <input type="text" id="rfc" placeholder="RFC (Ej. ABCD123456XYZ)">
            </div>

            <!-- Campos para Persona Moral -->
            <div id="moral-fields" class="hidden">
                <label for="company-name">Razón Social:</label>
                <input type="text" id="company-name" placeholder="Razón Social de la empresa">
                <label for="company-rfc">RFC de la Empresa:</label>
                <input type="text" id="company-rfc" placeholder="RFC (Ej. MOR123456XYZ)">
                <label for="representative">Representante Legal:</label>
                <input type="text" id="representative" placeholder="Nombre del representante">
            </div>

            <!-- Campos para Organización -->
            <div id="organizacion-fields" class="hidden">
                <label for="org-name">Nombre de la Organización:</label>
                <input type="text" id="org-name" placeholder="Nombre de la organización">
                <label for="contact-name">Nombre del Contacto:</label>
                <input type="text" id="contact-name" placeholder="Nombre del contacto principal">
                <label for="org-rfc">RFC de la Organización:</label>
                <input type="text" id="org-rfc" placeholder="RFC (Ej. ORG123456XYZ)">
            </div>

            <!-- Dirección de Facturación -->
            <label for="address">Dirección:</label>
            <input type="text" id="address" placeholder="Ingresa tu dirección">

            <button type="button" id="generate-invoice">Generar Factura</button>
            <button type="button" id="skip-invoice">No quiero factura</button>

        </form>
    </main>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        const productos = <?php echo $productosJSON; ?>;

        document.addEventListener("DOMContentLoaded", function () {
            const billingType = document.getElementById("billing-type");
            const fisicaFields = document.getElementById("fisica-fields");
            const moralFields = document.getElementById("moral-fields");
            const organizacionFields = document.getElementById("organizacion-fields");
            const generateInvoiceButton = document.getElementById("generate-invoice");

            billingType.addEventListener("change", function () {
                fisicaFields.classList.add("hidden");
                moralFields.classList.add("hidden");
                organizacionFields.classList.add("hidden");

                if (billingType.value === "fisica") {
                    fisicaFields.classList.remove("hidden");
                } else if (billingType.value === "moral") {
                    moralFields.classList.remove("hidden");
                } else if (billingType.value === "organizacion") {
                    organizacionFields.classList.remove("hidden");
                }
            });

            generateInvoiceButton.addEventListener("click", function () {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                const billingTypeValue = billingType.value;
                const address = document.getElementById("address").value;
                let clientName = "", clientRFC = "";

                if (billingTypeValue === "fisica") {
                    clientName = document.getElementById("name").value;
                    clientRFC = document.getElementById("rfc").value;
                } else if (billingTypeValue === "moral") {
                    clientName = document.getElementById("company-name").value;
                    clientRFC = document.getElementById("company-rfc").value;
                } else if (billingTypeValue === "organizacion") {
                    clientName = document.getElementById("org-name").value;
                    clientRFC = document.getElementById("org-rfc").value;
                }

                if (!clientName || !clientRFC || !address) {
                    alert("Por favor, completa todos los campos obligatorios.");
                    return;
                }

                doc.setFontSize(16);
                doc.text("Factura Electrónica", 20, 20);
                doc.setFontSize(12);
                doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 20, 30);
                doc.text(`Tipo de Facturación: ${billingTypeValue.toUpperCase()}`, 20, 40);
                doc.text(`Nombre / Razón Social: ${clientName}`, 20, 50);
                doc.text(`RFC: ${clientRFC}`, 20, 60);
                doc.text(`Dirección: ${address}`, 20, 70);

                doc.text("Detalle de la Compra:", 20, 90);
                doc.text(`Subtotal (sin IVA): $${document.getElementById("subtotal").innerText}`, 20, 100);
                doc.text(`IVA incluido (16%): $${document.getElementById("iva").innerText}`, 20, 110);
                doc.text(`Total pagado (ya con IVA): $${document.getElementById("total-with-tax").innerText}`, 20, 120);


                doc.text("Productos Adquiridos:", 20, 140);
                let yPosition = 150;
                productos.forEach(function(producto) {
                    doc.text(`${producto.nombre} - Cantidad: ${producto.cantidad} - Precio unitario: $${producto.precio} - Total: $${producto.subtotal}`, 20, yPosition);
                    yPosition += 10;
                });

                doc.save(`Factura_${clientName}.pdf`);

                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            });

            document.getElementById("skip-invoice").addEventListener("click", function () {
                if (confirm("¿Estás seguro de que no deseas factura?")) {
                    window.location.href = 'index.php';
                }
            });
        });
    </script>

</body>
</html>
