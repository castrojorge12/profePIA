// Código para la página de facturación
document.addEventListener("DOMContentLoaded", function () {
    const billingType = document.getElementById("billing-type");
    const fisicaFields = document.getElementById("fisica-fields");
    const moralFields = document.getElementById("moral-fields");
    const organizacionFields = document.getElementById("organizacion-fields");
    const form = document.getElementById("billing-form");
    const subtotalElement = document.getElementById("subtotal");
    const ivaElement = document.getElementById("iva");
    const totalWithTaxElement = document.getElementById("total-with-tax");
    const generateInvoiceButton = document.getElementById("generate-invoice");

    // Recuperar los productos desde localStorage para mostrar los totales
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let totalWithIVA = 0;
    let totalWithoutIVA = 0;
    let iva = 0;

    // Calcular el total de todos los productos considerando la cantidad
    cartItems.forEach(item => {
        const itemTotal = item.price * item.quantity; // Total de cada producto (precio * cantidad)
        totalWithIVA += itemTotal; // Sumar al total con IVA
    });

    // Calcular el subtotal sin IVA (suponiendo que el IVA es del 16%)
    totalWithoutIVA = totalWithIVA / 1.16;
    iva = totalWithIVA - totalWithoutIVA;

    // Mostrar los resultados en la página de facturación
    subtotalElement.innerText = `$${totalWithoutIVA.toFixed(2)}`; // Mostrar el subtotal sin IVA
    ivaElement.innerText = `$${iva.toFixed(2)}`; // Mostrar el IVA
    totalWithTaxElement.innerText = `$${totalWithIVA.toFixed(2)}`; // Mostrar el total con IVA

    // Mostrar campos del formulario según el tipo de facturación seleccionado
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

    // Evento para generar PDF de factura
    generateInvoiceButton.addEventListener("click", function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Datos del cliente
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

        // Validar campos obligatorios
        if (!clientName || !clientRFC || !address) {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Generar PDF de factura
        doc.setFontSize(16);
        doc.text("Factura Electrónica", 20, 20);
        doc.setFontSize(12);
        doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 20, 30);
        doc.text(`Tipo de Facturación: ${billingTypeValue.toUpperCase()}`, 20, 40);
        doc.text(`Nombre / Razón Social: ${clientName}`, 20, 50);
        doc.text(`RFC: ${clientRFC}`, 20, 60);
        doc.text(`Dirección: ${address}`, 20, 70);

        // Desglose de la factura
        doc.text("Detalle de la Compra:", 20, 90);
        doc.text(`Subtotal (sin IVA): $${totalWithoutIVA.toFixed(2)}`, 20, 100);
        doc.text(`IVA (16%): $${iva.toFixed(2)}`, 20, 110);
        doc.text(`Total con IVA: $${totalWithIVA.toFixed(2)}`, 20, 120);

        // Lista de productos
        doc.text("Productos Adquiridos:", 20, 140);
        let yPosition = 150;
        cartItems.forEach((item, index) => {
            const itemTotal = item.price * item.quantity; // Total por producto (precio * cantidad)
            doc.text(
                `${index + 1}. ${item.title} - Cantidad: ${item.quantity} - Precio unitario: $${item.price.toFixed(2)} - Total: $${itemTotal.toFixed(2)}`,
                20,
                yPosition
            );
            yPosition += 10;
        });

        // Guardar PDF
        doc.save(`Factura_${clientName}.pdf`);
    });
});
