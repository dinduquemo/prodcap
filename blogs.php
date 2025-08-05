<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog Capilar - Natural Beauty</title>
  <link rel="stylesheet" href="estilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
     
<header>
  <header class="franja-superior">
    <div class="logo">BLOG DE CUIDADO CAPILAR</div> 
  </header>
  <h2>10 consejos prácticos para lucir un cabello sano y brillante</h2>
</header>

<section class="contenedor-blogs">

  <!-- Repite este bloque para cada entrada -->
  <article class="blog">
    <h2>1. ¿Cada cuánto debo lavarme el cabello?</h2>
    <p>Depende del tipo de cabello. En general, 2 a 3 veces por semana es lo ideal, aunque los cabellos grasos pueden requerir lavados más frecuentes.</p>
  </article>

  <article class="blog imagen-lateral">
      <img src="imagenes/ARGAN-COCO.jpg" alt="Prevenir caída del cabello">
      <div>
          <h2>2. ¿Qué shampoo usar para cabello seco?</h2>
          <p>Elige productos sin sulfatos, ricos en aceites naturales como el argán o coco. Estos ayudan a restaurar la hidratación sin dañar la fibra capilar.</p>
      </div>
  </article>

    <article class="blog">
    <h2>3. Tratamientos naturales caseros</h2>
    <p>Una mascarilla con aguacate y miel es perfecta para nutrir tu cabello seco. Aplica una vez por semana para mejores resultados.</p>
  </article>

  <article class="blog imagen-lateral">
      <img src="imagenes/CAIDA-DE-CABELLO.jfif" alt="Prevenir caída del cabello">
      <div>
        <h2>4. ¿Cómo prevenir la caída del cabello?</h2>
        <p>Evita el estrés, mantén una dieta equilibrada rica en hierro y vitaminas, y masajea tu cuero cabelludo para estimular la circulación.</p>
      </div>
    </article>

  <article class="blog">
    <h2>5. ¿El agua fría da más brillo?</h2>
    <p>Sí. Enjuagar el cabello con agua fría al final ayuda a cerrar la cutícula y mejora el brillo natural.</p>
  </article>

  <article class="blog imagen-lateral">
      <img src="imagenes/mantener-pelo-fuerte.jpg.webp" alt="Prevenir caída del cabello">
      <div>
          <h2>6. La importancia del cepillado</h2>
          <p>Usa un cepillo de cerdas suaves y evita hacerlo cuando el cabello está mojado para no quebrarlo.</p>
      </div>
  </article>

  <article class="blog">
    <h2>7. Protección térmica ante el calor</h2>
    <p>Antes de usar planchas o secadores, aplica un protector térmico para evitar daños por calor excesivo.</p>
  </article>

  <article class="blog imagen-lateral">
      <img src="imagenes/ALIMENTOS-PARA-FORTALECER.jfif" alt="Prevenir caída del cabello">
      <div>
          <h2>8. ¿Qué alimentos fortalecen el cabello?</h2>
          <p>Incluye huevo, espinaca, salmón y frutos secos en tu dieta. Son ricos en biotina, omega 3 y zinc.</p>
      </div>
  </article>

  <article class="blog">
    <h2>9. Cómo evitar el frizz</h2>
    <p>Seca tu cabello con una toalla de microfibra, evita peinarlo en seco y usa productos anti-frizz sin alcohol.</p>
  </article>

  <article class="blog imagen-lateral">
      <img src="imagenes/SOL.png" alt="Prevenir caída del cabello">
      <div>
          <h2>10. ¿El sol daña el cabello?</h2>
          <p>Sí. El exceso de exposición solar deshidrata la fibra capilar. Usa gorros o sprays con filtro UV.</p>
  </div>
  </article>
</section>
  <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
 
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
<!-- Al final de TODOS tus .html -->
<script src="buscador.js"></script>

</body>
</html>
