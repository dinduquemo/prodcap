<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenida</title>
  <link rel="stylesheet" href="estilo.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
</head>
<head>
  <meta charset="UTF-8">
  <title>PRODUCTOS</title>
  <link rel="stylesheet" href="estilo.css">
  <!-- Agrega Font Awesome para iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <header class="header-fijo">
<?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
  </header>
<body>

  <section class="contenedor">
    <div class="imagen">
      <img src="imagenes/bienvenida.png" alt="Descripción de la imagen">
    </div>
    <div class="texto">
      <h1>HOLA Y </h1>
        <h1>BIENVENIDA</h1>
      <p>Nos alegra que estés aquí. Explora nuestra amplia gama de productos de alta calidad, diseñados para realzar la belleza natural de tu cabello. ¡Descubre los secretos para un cabello sano y radiante!</p>
    </div>
  </section>

  <section class="seccion-doble">
    <div class="lado-izquierdo">
      <h2>IINGREDIENTES</h2>
      <h2>NATURALES Y</h2>
      <h2>ORGANICOS</h2>
      <p>Ingredientes botánicos que transforman tu cabello desde la raíz hasta las puntas, impulsados por la innovación que redefine el cuidado capilar.</p>

      <img src="imagenes/SOL.png" alt="Descripción de la imagen">
      <p> </p>
      <div class="margen-color">
        <button class="boton_con_marco">
            <a href="productos.html"  class="boton">VER LOS PRODUCTOS</a>
        </button>
      </div>
    </div>

    <div class="imagen-central">
      <img src="imagenes/Modelo-apuntando.png" alt="Producto destacado">
    </div>

    <div class="lado-derecho">
        <img src="imagenes/SOL.png" alt="Descripción de la imagen">
      <h2>TECNOLOGIA</h2>
      <h2>AVANZADA</h2>
      <p>Despierta la belleza natural de tu cabello con la fuerza de la naturaleza y la precisión de la ciencia.</p>
    </div>
  </section>

  <header class="franja-superior">
    <div style="margin-bottom: 20px;"  class="logo">CLIENTES ENAMORAD@S</div>
  </header>
  <div class="contenido-principal2">
    <section class="galeria2">
        <div class="tarjeta">
          <img src="imagenes/ELENA PAULA.png" alt="Producto 1">
          <h2>Elena Paula</h2>
          <p>¡Mis rizos nunca se han visto tan definidos y saludables! Gracias a estos productos, mi cabello rizado luce espectacular.</p>
        </div>
      
        <div class="tarjeta">
          <img src="imagenes/CATALINA.png" alt="Producto 2">
          <h2>Catalina Estévez</h2>
          <p>Gracias a estos productos, mi cabello luce radiante y saludable.</p>
        </div>
      
        <div class="tarjeta">
          <img src="imagenes/ISABEL.png" alt="Producto 3">
          <h2>Isabel Mercado </h2>
          <p style="margin-bottom: 100px;">Mi cabello decolorado luce fuerte, brillante y saludable.</p>
        </div>
      </section>
    </DIV>


    <SECtion CLASS="SOBRENOSOTROS">
        <header class="franja-superior_SOBRENOSOTROS">
            <div style="margin-bottom: 20px;" class="logo">SOBRE NOSOTROS</div>
        </header>
            <section class="contenedor">
                <div class="texto">
                  <h1>MISIÓN</h1>
                  <p style="margin-bottom: 50px;">Ofrecer productos y servicios de alta calidad para el cuidado del cabello, que satisfagan las necesidades y deseos de nuestros clientes, impulsando su belleza y bienestar a través de la salud capilar.</p>
                  <h1>VISION</h1>
                    <p style="margin-bottom: 100px;">Ser la tienda líder en el mercado de cuidado capilar, reconocida por la excelencia de sus productos, la calidad de sus servicios y la experiencia personalizada que ofrece a sus clientes, convirtiéndose en un referente de innovación y tendencias en el cuidado del cabello.</p>
                </div>
                <div class="imagen">
                  <img style="margin-bottom: 100px;"src="imagenes/SOBRENOSOTROS.png" alt="Descripción de la imagen">
                </div>
            </section>
    </SECtion>
    <section class="seccion-destacada">
        <div class="columna izquierda">
          <div class="contenido-texto">
            <h1>FORTALEZA Y<br>ELASTICIDAD</h1>
            <p>Cuidamos tu hebra capilar desde la raíz</p>
            <a href="#contacto" class="boton">CONTACTAR</a>
          </div>
        </div>
        <div class="columna derecha">
        </div>
      </section>
      <section id="contacto">
        <section class="contacto">
            <div class="contenedor-forma">
                <h1>CONTACTOS</h1>
                <p><strong>Dirección de tienda:</strong><br><em>Calle Cualquiera 123, Cualquier Lugar</em></p>
                <p><strong>Teléfonos:</strong><br>91-1234-567 & +34-91-1234-567</p>
                <p><strong>Email:</strong><br><a href="mailto:atencion@naturalbeauty.com">atencion@naturalbeauty.com</a></p>
            </div>
            
        </section>
        <footer class="footer">
            <div class="redes">
                <span data-url="https://www.instagram.com">Instagram</span>
                <span data-url="https://www.facebook.com">Facebook</span>
            </div>
          </footer>
        </section>
        <!-- <footer>
            <p>© 2025 - Natural Beauty</p>
        </footer>
     -->
      <script src="script.js"></script>
      <script>
  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const terminoBusqueda = urlParams.get('q');

    if (terminoBusqueda) {
      const productos = document.querySelectorAll('.tarjeta');
      const texto = terminoBusqueda.toLowerCase();

      productos.forEach(producto => {
        const titulo = producto.querySelector('h2')?.textContent.toLowerCase() || '';
        const descripcion = producto.querySelector('p')?.textContent.toLowerCase() || '';

        if (titulo.includes(texto) || descripcion.includes(texto)) {
          producto.style.display = 'block';
        } else {
          producto.style.display = 'none';
        }
      });

    }
  });
</script>
<script src="buscador.js"></script>
</body>
</html>
