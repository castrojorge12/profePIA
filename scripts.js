const btnCart = document.querySelector('.container-cart-icon');
const containerCartProducts = document.querySelector('.container-cart-products');

btnCart.addEventListener('click', () => {
    containerCartProducts.classList.toggle('hidden-cart');
});

const rowProduct = document.querySelector('.row-product');
const productsList = document.querySelector('.container-items');
let allProducts = JSON.parse(localStorage.getItem('cartItems')) || [];

const valorTotal = document.querySelector('.total-pagar');
const countProducts = document.querySelector('#contador-productos');
const cartEmpty = document.querySelector('.cart-empty');
const cartTotal = document.querySelector('.cart-total');

// Modal variables
let selectedProduct = null;
const modal = document.getElementById('modal-metros');
const inputMetros = document.getElementById('input-metros');
const modalTitle = document.getElementById('modal-title');

// Evento al hacer clic en un botón de agregar al carrito
productsList.addEventListener('click', e => {
    if (e.target.classList.contains('btn-add-cart')) {
        const product = e.target.parentElement;

        selectedProduct = {
            title: product.querySelector('h2').textContent,
            price: parseFloat(product.querySelector('.price').textContent.slice(1))
        };

        modalTitle.textContent = `¿Cuántos metros cuadrados de "${selectedProduct.title}" deseas?`;
        inputMetros.value = '';
        modal.classList.remove('hidden');
    }
});

// Evento para cerrar el modal
document.getElementById('btn-cancelar').addEventListener('click', () => {
    modal.classList.add('hidden');
    selectedProduct = null;
});

document.getElementById('btn-agregar').addEventListener('click', () => {
    const metros = parseFloat(inputMetros.value);

    if (isNaN(metros) || metros <= 0) {
        alert("Ingresa una cantidad válida en metros cuadrados.");
        return;
    }

    // Usamos trim() para eliminar cualquier espacio extra
    const selectedTitle = selectedProduct.title.trim();

    // Condiciones por tipo de césped
    if (selectedTitle.toLowerCase() === 'san agustin' && metros < 201) {
        alert('Para San Agustin, el mínimo es 201 metros cuadrados.');
        return;
    }
    if (selectedTitle.toLowerCase() === 'bermuda' && metros < 201) {
        alert('Para Bermuda, el mínimo es 201 metros cuadrados.');
        return;
    }
    if (selectedTitle.toLowerCase() === 'zoysia' && metros < 51) {
        alert('Para Zoysia, el mínimo es 51 metros cuadrados.');
        return;
    }

    const existingProduct = allProducts.find(p => p.title === selectedProduct.title);

    if (existingProduct) {
        existingProduct.quantity += metros;
    } else {
        allProducts.push({ ...selectedProduct, quantity: metros });
    }

    saveToLocalStorage();
    showHTML();

    modal.classList.add('hidden');
    selectedProduct = null;
});


// Evento para eliminar un producto del carrito
rowProduct.addEventListener('click', e => {
    if (e.target.classList.contains('icon-close')) {
        const product = e.target.parentElement;
        const title = product.querySelector('.titulo-producto-carrito').textContent;

        allProducts = allProducts.filter(p => p.title !== title);
        saveToLocalStorage();
        showHTML();
    }
});

// Guardar en localStorage
const saveToLocalStorage = () => {
    localStorage.setItem('cartItems', JSON.stringify(allProducts));
};

// Redirigir a carrito completo
document.getElementById('btn-finalizar-compra').addEventListener('click', () => {
    window.location.href = 'cart.html';
});

// Mostrar el carrito actualizado
const showHTML = () => {
    if (!allProducts.length) {
        cartEmpty.classList.remove('hidden');
        rowProduct.classList.add('hidden');
        cartTotal.classList.add('hidden');
        valorTotal.innerText = '$0.00';
        countProducts.innerText = '0';
        return;
    }

    cartEmpty.classList.add('hidden');
    rowProduct.classList.remove('hidden');
    cartTotal.classList.remove('hidden');

    rowProduct.innerHTML = '';
    let total = 0;
    let totalOfProducts = 0;

    allProducts.forEach(product => {
        const containerProduct = document.createElement('div');
        containerProduct.classList.add('cart-product');

        containerProduct.innerHTML = `
            <div class="info-cart-product">
                <span class="cantidad-producto-carrito">${product.quantity}</span>
                <p class="titulo-producto-carrito">${product.title}</p>
                <span class="precio-producto-carrito">$${(product.price * product.quantity).toFixed(2)}</span>
            </div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="icon-close"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        `;

        rowProduct.append(containerProduct);
        total += product.price * product.quantity;
        totalOfProducts += product.quantity;
    });

    valorTotal.innerText = `$${total.toFixed(2)}`;
    countProducts.innerText = totalOfProducts;
};

showHTML();
