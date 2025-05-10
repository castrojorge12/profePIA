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
        
        // Añadir los detalles del producto al elemento
        itemElement.innerHTML = `
            <h3>${item.title}</h3> <!-- Título del producto -->
            <span>Precio unitario: $${item.price.toFixed(2)}</span> <!-- Precio del producto -->
            <span>Cantidad: ${item.quantity}</span> <!-- Cantidad de productos (si se ha agregado) -->
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



// parte agregada el codigo

document.addEventListener('DOMContentLoaded', () => {
    const metrosSelect = document.getElementById('metrosSelect');

    for (let i = 20; i <= 200; i += 20) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${i} mts²`;
        metrosSelect.appendChild(option);
    }

    // Mostrar modal
    const continuarBtn = document.getElementById('continuarCompraBtn');
    const modal = document.getElementById('metroModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const confirmBtn = document.getElementById('confirmBtn');

    continuarBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    confirmBtn.addEventListener('click', () => {
        const metrosSeleccionados = metrosSelect.value;
        localStorage.setItem('metrosCuadrados', metrosSeleccionados);
        
        modal.style.display = 'none';
        window.location.href = 'payment.html';
    });
});

