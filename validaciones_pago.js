document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment-form");
    const cardNumber = document.getElementById("card-number");
    const expiryDate = document.getElementById("expiry-date");
    const cvv = document.getElementById("cvv");
    const address = document.getElementById("address");

    // Validación del formulario al hacer submit
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Evitar envío del formulario si no es válido

        let valid = true;
        let errorMessage = "";

        // Validar dirección
        if (address.value.trim() === "") {
            valid = false;
            errorMessage += "La dirección es obligatoria.\n";
        }

        // Validar número de tarjeta (debe tener 16 dígitos)
        const cardValue = cardNumber.value.replace(/\s/g, ""); // Eliminar espacios
        if (!/^\d{16}$/.test(cardValue)) {
            valid = false;
            errorMessage += "El número de tarjeta debe tener 16 dígitos.\n";
        }

        // Validar fecha de expiración (MM/AA)
        const expiryValue = expiryDate.value.trim();
        if (!/^\d{2}\/\d{2}$/.test(expiryValue)) {
            valid = false;
            errorMessage += "La fecha de expiración debe tener el formato MM/AA.\n";
        }

        // Validar CVV (debe tener 3 o 4 dígitos)
        const cvvValue = cvv.value.trim();
        if (!/^\d{3,4}$/.test(cvvValue)) {
            valid = false;
            errorMessage += "El CVV debe tener 3 o 4 dígitos.\n";
        }

        // Si los datos son válidos, continuar
        if (valid) {
            alert("Formulario de pago completado correctamente.");
            // Redirigir al index.html
            window.location.href = "index.html"; // Cambia "index.html" si es necesario
        } else {
            alert(errorMessage); // Muestra los errores en un alert
        }
    });

    // Formatear la fecha de expiración automáticamente (MM/AA)
    expiryDate.addEventListener("input", function () {
        let value = expiryDate.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4); // Añadir el slash
        }
        expiryDate.value = value.substring(0, 5); // Limitar el valor a 5 caracteres (MM/AA)
    });
});
