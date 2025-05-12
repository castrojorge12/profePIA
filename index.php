<?php
session_start();
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rancho La Escondida</title>
  <link rel="stylesheet" href="estilos.css" />
</head>

<body>
  <div class="main-container">
    <img src="imgprofe/logoRancho.png" alt="LogoEmpresa" />
    
    <div class="side-menu">
      <ul>
        <li><a href="#inicio">INICIO</a></li>
        <li><a href="comprasPag.php">venta</a></li>
        <li><a href="aboutUs.html">SERVICIOS</a></li>
        <li><a href="contacto.html">CONTACTO</a></li>
      </ul>
    </div>

    <header>
      <h1>Rancho la Escondida</h1>

      <!-- Mostrar mensaje de bienvenida y botón de cerrar sesión -->
      <?php if ($usuario): ?>
        <div class="bienvenida">
          <p>Bienvenido, <?php echo htmlspecialchars($usuario); ?></p>
        </div>
        <form action="logout.php" method="post" class="form-logout">
            <button type="submit" class="btn-cerrar">Cerrar sesión</button>
        </form>
      <?php else: ?>
        <!-- Botón de Iniciar Sesión si no hay usuario -->
        <div class="boton"> 
            <a href="login.php">
            <button class="btnInicio">Iniciar Sesión</button>
          </a>
        </div>
      <?php endif; ?>
    </header>

   <section id="presentacion" class="seccion-bienvenida">
  <div class="contenido-bienvenida">
    <h1>Pasto siempre verde</h1>
    <p><strong>Con más de 20 años de experiencia, Rancho La Escondida se especializa en la producción, venta e instalación de pasto en rollo recién cosechado en Juárez, Nuevo León.</strong></p>
    
  </div>
</section>



    <section id="variedades" class="visor-pasto">
  <h2>Variedades de Pasto</h2>
  <div class="contenedor-pasto">
    <img id="imagen-pasto" src="imgprofe/pasto florida.jpg" alt="Pasto San Agustín">
    <p id="descripcion-pasto">Pasto San Agustín: Ideal para áreas verdes</p>
    <div class="botones-pasto">
      <button onclick="cambiarPasto(-1)">Anterior</button>
      <button onclick="cambiarPasto(1)">Siguiente</button>
    </div>
  </div>
</section>


   

 
    <footer>
      <p>&copy; 2025 Rancho La Escondida. Todos los derechos reservados.</p>
    </footer>
    
  </div>
  <script>
  const pastos = [
    {
      imagen: "imgprofe/pasto florida.jpg",
      descripcion: "Pasto San Agustín: Ideal para áreas verdes"
    },
    {
      imagen: "imgprofe/Pasto Bermuda 419.jpg",
      descripcion: "Bermuda 419: Usado en las mejores canchas deportivas"
    },
    {
      imagen: "imgprofe/Pasto Zoysia.jpg",
      descripcion: "Zoysia: Perfecto para paisajismo"
    }
  ];

  let indiceActual = 0;

  function cambiarPasto(direccion) {
    indiceActual += direccion;
    if (indiceActual < 0) indiceActual = pastos.length - 1;
    if (indiceActual >= pastos.length) indiceActual = 0;

    document.getElementById("imagen-pasto").src = pastos[indiceActual].imagen;
    document.getElementById("imagen-pasto").alt = pastos[indiceActual].descripcion;
    document.getElementById("descripcion-pasto").textContent = pastos[indiceActual].descripcion;
  }
</script>

</body>
</html>