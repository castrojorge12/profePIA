document.addEventListener("DOMContentLoaded", function () {
    const billingType = document.getElementById("billing-type");
    const fisicaFields = document.getElementById("fisica-fields");
    const moralFields = document.getElementById("moral-fields");
    const organizacionFields = document.getElementById("organizacion-fields");
    const generateInvoiceButton = document.getElementById("generate-invoice");

    // Mostrar campos según tipo de facturación
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

    // Generación de la factura en PDF
    generateInvoiceButton.addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Datos de facturación del cliente
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

        // Validar que los campos no estén vacíos
        if (!clientName || !clientRFC || !address) {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Generar PDF
        doc.setFontSize(16);
        doc.text("Factura Electrónica", 20, 20);
        doc.setFontSize(12);
        doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 20, 30);
        doc.text(`Tipo de Facturación: ${billingTypeValue.toUpperCase()}`, 20, 40);
        doc.text(`Nombre / Razón Social: ${clientName}`, 20, 50);
        doc.text(`RFC: ${clientRFC}`, 20, 60);
        doc.text(`Dirección: ${address}`, 20, 70);

        // Detalles de la compra
        doc.text("Detalle de la Compra:", 20, 90);
        doc.text(`Subtotal (sin IVA): $${document.getElementById("subtotal").innerText}`, 20, 100);
        doc.text(`IVA (16%): $${document.getElementById("iva").innerText}`, 20, 110);
        doc.text(`Total con IVA: $${document.getElementById("total-with-tax").innerText}`, 20, 120);

        // Lista de productos
        doc.text("Productos Adquiridos:", 20, 140);
        let yPosition = 150;
        productos.forEach(function(producto) {
            doc.text(`${producto.nombre} - Cantidad: ${producto.cantidad} - Precio unitario: $${producto.precio} - Total: $${producto.subtotal}`, 20, yPosition);
            yPosition += 10;
        });

        // Guardar el PDF
        doc.save(`Factura_${clientName}.pdf`);
    });
});
