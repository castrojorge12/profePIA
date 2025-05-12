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
        <li><a href="#servicios">SERVICIOS</a></li>
        <li><a href="#contacto">CONTACTO</a></li>
      </ul>
    </div>

    <header>
<<<<<<< HEAD:index.html
        
        <!-- Botón de Iniciar Sesión -->
      <div class="boton">
        <a href="login.php" target="_blank">
          <button class="btnInicio">Iniciar Sesión</button>
        </a>
      </div>
=======
      <h1>Rancho la Escondida</h1>

      <!-- Mostrar mensaje de bienvenida y botón de cerrar sesión -->
      <?php if ($usuario): ?>
        <p style="text-align: center; margin: 10px;">Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
        <form action="logout.php" method="post" style="text-align: center; margin-top: 10px;">
          <button type="submit">Cerrar sesión</button>
        </form>
      <?php else: ?>
        <!-- Botón de Iniciar Sesión si no hay usuario -->
        <div style="margin-top: 20px; text-align: center;">
          <a href="login.php">
            <button>Iniciar Sesión</button>
          </a>
        </div>
      <?php endif; ?>
>>>>>>> ca64ca37c0678b6c6fd0e1549c973e0b78086738:index.php
    </header>

    <section id="inicio" class="hero">
      <h1>Pasto siempre verde</h1>
      <p>Con la calidad, servicio y experiencia de más de 20 años que nos respaldan.</p>
    </section>

    <section id="variedades">
      <h2>Variedades de Pasto</h2>
      <ul>
        <li>Pasto San Agustín</li>
        <li>Bermuda 419</li>
        <li>Zoysia</li>
      </ul>
    </section>

    <section id="servicios">
      <h2>Servicios</h2>
      <p>Especializados en la producción, venta e instalación de pasto en rollo recién cosechado y de la más alta calidad.</p>
    </section>

    <section id="contacto">
      <h2>Contacto</h2>
      <a class="whatsapp-button" href="https://wa.me/528184627394" target="_blank">Whatsapp Click Aquí</a>
      <p><strong>Teléfono:</strong> 81 84 62 73 94</p>
      <p><strong>Correo Electrónico:</strong> info@pastolaescondida.com</p>
      <p><strong>Ubicación:</strong> Juárez, Nuevo León</p>
    </section>

    <footer>
      <p>&copy; 2025 Rancho La Escondida. Todos los derechos reservados.</p>
    </footer>
    
  </div>
</body>
</html>
