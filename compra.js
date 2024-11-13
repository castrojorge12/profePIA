// Código para la página del carrito
document.addEventListener('DOMContentLoaded', () => {
    const cartItemsContainer = document.getElementById('cart-items');
    const totalElement = document.getElementById('total');
    
    // Recuperar los productos desde localStorage
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    
    if (cartItems.length === 0) {
        cartItemsContainer.innerHTML = '<p>El carrito está vacío.</p>';
        totalElement.innerText = 'Total: $0.00';
        return;
    }

    let total = 0;
    cartItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        itemElement.innerHTML = `
            <h3>${item.title}</h3>
            <p>${item.description}</p>
            <span>Precio: $${item.price.toFixed(2)}</span>
            <hr>
        `;
        cartItemsContainer.appendChild(itemElement);
        total += item.price;
    });

    totalElement.innerText = `Total: $${total.toFixed(2)}`;
});
