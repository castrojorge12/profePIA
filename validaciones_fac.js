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

    // Calcular el total con IVA de todos los productos
    cartItems.forEach(item => {
        totalWithIVA += item.price;
    });

    // Calcular el total sin IVA
    const totalWithoutIVA = totalWithIVA / 1.16;

    // Calcular el IVA
    const iva = totalWithIVA - totalWithoutIVA;

    // Mostrar los resultados en la página de facturación
    subtotalElement.innerText = `$${totalWithoutIVA.toFixed(2)}`;
    ivaElement.innerText = `$${iva.toFixed(2)}`;
    totalWithTaxElement.innerText = `$${totalWithIVA.toFixed(2)}`;

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
        const billingType = document.getElementById("billing-type").value;
        const address = document.getElementById("address").value;
        let clientName = "", clientRFC = "";

        if (billingType === "fisica") {
            clientName = document.getElementById("name").value;
            clientRFC = document.getElementById("rfc").value;
        } else if (billingType === "moral") {
            clientName = document.getElementById("company-name").value;
            clientRFC = document.getElementById("company-rfc").value;
        } else if (billingType === "organizacion") {
            clientName = document.getElementById("org-name").value;
            clientRFC = document.getElementById("org-rfc").value;
        }

        // Validar campos
        if (!clientName || !clientRFC || !address) {
            alert("Por favor, completa todos los campos obligatorios.");
            return;
        }

        // Datos para el PDF
        doc.setFontSize(16);
        doc.text("Factura Electrónica", 20, 20);
        doc.setFontSize(12);
        doc.text(`Fecha: ${new Date().toLocaleDateString()}`, 20, 30);
        doc.text(`Tipo de Facturación: ${billingType.toUpperCase()}`, 20, 40);
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
            doc.text(`${index + 1}. ${item.title} - $${item.price.toFixed(2)}`, 20, yPosition);
            yPosition += 10;
        });

        // Guardar PDF
        doc.save(`Factura_${clientName}.pdf`);
    });
});
