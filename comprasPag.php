
<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "Laspalmas721";
$dbname = "login_rancho"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener productos desde la base de datos
$sql = "SELECT * FROM productos"; // Obtener todos los productos
$result = $conn->query($sql);

session_start();
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión activa, redirige a login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" /> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tienda</title> 
    <style>
      .icon-cart {
    width: 40px;
    height: 40px;
    stroke: #fff;
}

.icon-cart:hover {
    cursor: pointer;
}

.container-icon {
    position: relative;
}

.count-products {
    position: absolute;
    top: 0;
    right: 0;
    background-color: #ff0000;
    color: #fff;
    width: 25px;
    height: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    font-size: 14px;
    font-weight: bold; 
}

#contador-productos {
    font-size: 12px;
}

/* CARRITO DE COMPRAS */
.container-cart-products {
    position: absolute;
    top: 60px; 
    right: 0;
    background-color: #fff;
    width: 400px;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
    border-radius: 10px;
    overflow: hidden; 
}

.cart-product {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1); 
}

.info-cart-product {
    display: flex;
    justify-content: space-between;
    flex: 0.8;
    color:black;    
}

.titulo-producto-carrito {
    font-size: 18px; 
    font-weight: 600; 
    color:black;
}

.precio-producto-carrito {
    font-weight: 700;
    font-size: 18px; 
    margin-left: 10px;
    color: green;
}

.cantidad-producto-carrito {
    font-weight: 400;
    font-size: 18px;
    color:black;
}

.icon-close {
    width: 25px;
    height: 25px;
    transition: stroke 0.3s;
    color:black;
}

.icon-close:hover {
    stroke: #ff0000;
    cursor: pointer;
}

.cart-total {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    gap: 20px;
}

.cart-total h3 {
    font-size: 18px;
    font-weight: 700;
}

.total-pagar {
    font-size: 22px;
    font-weight: 900;
    color: green;
}

.hidden-cart {
    display: none;
}

/* CARRITO VACÍO */
.cart-empty {
    padding: 20px;
    text-align: center;
    font-size: 18px;
    color: #555;
}

.hidden {
    display: none;
}

body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #4CAF50;
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h1 {
    margin: 0;
}

.container-icon {
    position: relative;
    cursor: pointer;
}

.icon-cart {
    width: 30px;
    height: 30px;
}

.count-products {
    background-color: red;
    color: white;
    border-radius: 50%;
    font-size: 14px;
    padding: 2px 6px;
    position: absolute;
    top: -10px;
    right: -10px;
}

.container-items {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

.item {
    background: white;
    border-radius: 10px;
    margin: 15px;
    padding: 15px;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.item:hover {
    transform: scale(1.05);
}

.item img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.info-product {
    text-align: center;
}

.price {
    color: green;
    font-weight: bold;
}

.btn-add-cart {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
}

.btn-add-cart:hover {
    background-color: #45a049;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.hidden {
    display: none;
}

.modal-buttons {
    margin-top: 20px;
}

.modal-button {
    margin: 0 10px;
    padding: 10px 20px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
}

.modal-button.cancel {
    background-color: #ccc;
}

.modal-button.add {
    background-color: #4CAF50;
    color: white;
}
.boton-inicio {
    background-color: #2e5e14;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    margin-right: 20px; /* espacio con el ícono */
}

.boton-inicio:hover {
    background-color: #4c8c2c;
    transform: scale(1.05);
}


</style> 
</head>

<!-- Modal flotante -->
<div id="modal-metros" class="modal hidden">
  <div class="modal-content">
    <h3 id="modal-title" class="modal-title">¿Cuántos metros cuadrados deseas?</h3>
    <input type="number" id="input-metros" class="modal-input" min="1" placeholder="Ej. 10" />
    <div class="modal-buttons">
      <button id="btn-cancelar" class="modal-button cancel">Cancelar</button>
      <button id="btn-agregar" class="modal-button add">Agregar</button>
    </div>
  </div>
</div>

<body>

    <!-- Botón de navegación a la página 'aboutUs.html' -->
   


    <!-- Encabezado con nombre de la tienda y carrito -->
    <header>
      
       <!-- Botón de navegación a la página 'aboutUs.html' -->
    <button class="boton-inicio" onclick="location.href='index.php'">Inicio</button>
    <div class="logo-contenedor">
    <img src="imgprofe/logoRancho.png" alt="LogoEmpresa" class="logo-rancho" />
    
  </div>

        

        <!-- Contenedor del ícono del carrito y el contador de productos -->
        <div class="container-icon">
            <div class="container-cart-icon">
                <!-- Icono de carrito de compras en formato SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-cart">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <!-- Contador de productos en el carrito -->
                <div class="count-products">
                    <span id="contador-productos">0</span> <!-- Inicialmente 0 productos en el carrito -->
                </div>
            </div>

            <!-- Contenedor del carrito de productos (oculto por defecto) -->
            <div class="container-cart-products hidden-cart">
                <div class="row-product hidden"> <!-- Fila de productos (vacía al principio) -->
                </div>

                <!-- Total del carrito y botón para finalizar compra -->
                <div class="cart-total hidden">
                    <h3>Total:</h3>
                    <span class="total-pagar">$0</span> <!-- Total de la compra -->
                    <button id="btn-finalizar-compra">Finalizar Compra</button> <!-- Botón de finalizar compra -->
                </div>
                <p class="cart-empty">El carrito está vacío</p> <!-- Mensaje si el carrito está vacío -->
            </div>
        </div>
    </header>

    <!-- Sección de productos -->
    <div class="container-items">
        <?php
        // Verificar si hay productos
        if ($result->num_rows > 0) {
            // Mostrar productos desde la base de datos
            while($row = $result->fetch_assoc()) {
                echo '<div class="item">
                        <figure>
                            <img src="imgprofe/' . $row["imagen"] . '" alt="producto" /> <!-- Imagen del producto -->
                        </figure>
                        <div class="info-product">
                            <h2>' . $row["nombre"] . '</h2> <!-- Nombre del producto -->
                            <p class="description">' . $row["descripcion"] . '</p> <!-- Descripción del producto -->
                            <p class="price">$' . $row["precio"] . ' m2</p> <!-- Precio del producto -->
                            <button class="btn-add-cart" data-product="' . $row["nombre"] . '" data-price="' . $row["precio"] . '">Añadir al carrito </button>
                        </div>
                    </div>';
            }
        } else {
            echo "<p>No se encontraron productos</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Vincula el archivo JavaScript para la funcionalidad del carrito -->
    <script src="scripts.js"></script>
    <!-- Script para manejar el modal y guardar en localStorage -->
<script>
  let productoSeleccionado = null;

  document.addEventListener('DOMContentLoaded', () => {
    const botonesAgregar = document.querySelectorAll('.btn-add-cart');
    const modal = document.getElementById('modal-metros');
    const inputMetros = document.getElementById('input-metros');
    const btnAgregar = document.getElementById('btn-agregar');
    const btnCancelar = document.getElementById('btn-cancelar');

    botonesAgregar.forEach(btn => {
      btn.addEventListener('click', () => {
        productoSeleccionado = {
          nombre: btn.getAttribute('data-product'),
          precio: parseFloat(btn.getAttribute('data-price'))
        };
        inputMetros.value = '';
        modal.classList.remove('hidden');
      });
    });

    btnCancelar.addEventListener('click', () => {
      modal.classList.add('hidden');
    });

    btnAgregar.addEventListener('click', () => {
      const metros = parseFloat(inputMetros.value);
      if (!isNaN(metros) && metros > 0) {
        const producto = {
          nombre: productoSeleccionado.nombre,
          precio: productoSeleccionado.precio,
          metros: metros,
          total: metros * productoSeleccionado.precio
        };

        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        cartItems.push(producto);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        modal.classList.add('hidden');
        alert("Producto agregado al carrito.");
        console.log("Producto agregado:", producto);  // Verifica en la consola del navegador
      } else {
        alert("Ingresa un número válido de metros.");
      }
    });
  });
</script>


</body>
</html>
