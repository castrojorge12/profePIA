// Código para la página del carrito
document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.getElementById('cart-items'); // Contenedor de los productos en el carrito
    const totalElement = document.getElementById('total'); // Elemento para mostrar el total del carrito

    // Recuperar los productos desde localStorage
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // Si el carrito está vacío, mostrar un mensaje
    if (cartItems.length === 0) {
        cartItemsContainer.innerHTML = '<p>El carrito está vacío.</p>';
        totalElement.innerText = 'Total: $0.00';
        return;
    }

    let total = 0; // Variable para calcular el total del precio
    cartItems.forEach(item => {
        // Crear un elemento para cada producto en el carrito
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');

        // Verificamos si existe la descripción
        const descriptionHTML = item.description ? `<p>${item.description}</p>` : '';

        // Añadir los detalles del producto al elemento
        itemElement.innerHTML = `
            <h3>${item.title}</h3> <!-- Título del producto -->
            ${descriptionHTML} <!-- Descripción del producto si existe -->
            <span>Precio unitario: $${item.price.toFixed(2)}</span> <!-- Precio del producto -->
            <span>Cantidad: ${item.quantity}</span> <!-- Cantidad de productos -->
            <span>Subtotal: $${(item.price * item.quantity).toFixed(2)}</span> <!-- Subtotal del producto -->
            <hr>
        `;

        // Añadir el producto al contenedor del carrito
        cartItemsContainer.appendChild(itemElement);

        // Calcular el total acumulado (precio * cantidad)
        total += item.price * item.quantity;
    });

    // Mostrar el total calculado
    totalElement.innerText = `Total: $${total.toFixed(2)}`;
});
