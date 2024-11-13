const btnCart = document.querySelector('.container-cart-icon');
const containerCartProducts = document.querySelector('.container-cart-products');

btnCart.addEventListener('click', () => {
    containerCartProducts.classList.toggle('hidden-cart');
});

/* ========================= */
const rowProduct = document.querySelector('.row-product');

// Lista de todos los contenedores de productos
const productsList = document.querySelector('.container-items');

// Variable de arreglos de Productos
let allProducts = JSON.parse(localStorage.getItem('cartItems')) || []; // Cargar desde localStorage o inicializar vacío

const valorTotal = document.querySelector('.total-pagar');
const countProducts = document.querySelector('#contador-productos');
const cartEmpty = document.querySelector('.cart-empty');
const cartTotal = document.querySelector('.cart-total');

productsList.addEventListener('click', e => {
    if (e.target.classList.contains('btn-add-cart')) {
        const product = e.target.parentElement;

        const infoProduct = {
            title: product.querySelector('h2').textContent, // Nombre del producto
            price: parseFloat(product.querySelector('.price').textContent.slice(1)), // Obtener el precio como número
            description: product.querySelector('.description').textContent // Obtener la descripción
        };

        // Verificar si el producto ya está en el carrito
        const exists = allProducts.some(p => p.title === infoProduct.title);

        if (exists) {
            alert('Este producto ya ha sido agregado al carrito.'); // Mensaje de advertencia
        } else {
            allProducts.push({ ...infoProduct, quantity: 1 }); // Agregar el producto al carrito con cantidad 1
        }

        saveToLocalStorage(); // Guardar en localStorage
        showHTML(); // Mostrar el carrito
    }
});

rowProduct.addEventListener('click', e => {
    if (e.target.classList.contains('icon-close')) {
        const product = e.target.parentElement;
        const title = product.querySelector('p').textContent;

        allProducts = allProducts.filter(p => p.title !== title); // Eliminar el producto del carrito

        saveToLocalStorage(); // Guardar en localStorage
        showHTML(); // Mostrar el carrito
    }
});

// Función para guardar los productos en localStorage
const saveToLocalStorage = () => {
    localStorage.setItem('cartItems', JSON.stringify(allProducts));
};

// Evento para finalizar compra
document.getElementById('btn-finalizar-compra').addEventListener('click', () => {
    window.location.href = 'cart.html'; // Redirigir a la nueva página
});

// Función para mostrar HTML
const showHTML = () => {
    if (!allProducts.length) {
        cartEmpty.classList.remove('hidden');
        rowProduct.classList.add('hidden');
        cartTotal.classList.add('hidden');
        valorTotal.innerText = '$0.00'; // Resetea el total a 0 si está vacío
        countProducts.innerText = '0'; // Resetea el contador a 0 si está vacío
    } else {
        cartEmpty.classList.add('hidden');
        rowProduct.classList.remove('hidden');
        cartTotal.classList.remove('hidden');
    }

    // Limpiar HTML
    rowProduct.innerHTML = '';

    let total = 0; // Inicializar el total
    let totalOfProducts = 0; // Inicializar el total de productos

    allProducts.forEach(product => {
        const containerProduct = document.createElement('div');
        containerProduct.classList.add('cart-product');

        // Solo se muestra el nombre y el precio del producto
        containerProduct.innerHTML = `
            <div class="info-cart-product">
                <span class="cantidad-producto-carrito">${product.quantity}</span>
                <p class="titulo-producto-carrito" title="${product.description}">${product.title}</p>
                <span class="precio-producto-carrito">$${product.price.toFixed(2)}</span> <!-- Formato del precio -->
                <span class="tooltip">${product.description}</span>
            </div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="icon-close"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        `;

        rowProduct.append(containerProduct);

        total += product.price; // Sumar el precio del producto al total
        totalOfProducts += product.quantity; // Acumula la cantidad total de productos
    });

    valorTotal.innerText = `$${total.toFixed(2)}`; // Muestra el total con 2 decimales
    countProducts.innerText = totalOfProducts; // Muestra la cantidad total de productos
};

// Mostrar el carrito al cargar la página
showHTML();


