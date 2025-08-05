<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NB - Cuidado Capilar</title>
    <link rel="stylesheet" href="estilo.css">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=TT+Commons+Pro:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Barra de navegación fija -->
    <header class="header-fijo">
      <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
    </header>

    <!-- Fondo decorativo -->
    <div class="fondo2"></div>

    <!-- Sección principal -->
    <main class="hero">
        <div class="overlay">
            <h1>Productos de cuidado capilar</h1>
            <p>Porque cuidar hace parte del amar</p>
            <a href="productos.php" class="boton">VER MÁS</a>
        </div>
    </main>

    <!-- Script JS -->
    <script>
    // Abrir/Cerrar menú móvil
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.menu-principal').classList.toggle('activo');
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
  <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
  <script src="buscador.js"></script>
</body>

</html>