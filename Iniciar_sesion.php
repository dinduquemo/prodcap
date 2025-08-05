<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio_sesion</title>
    <link rel="stylesheet" href="estilo.css">
    <!-- Agrega Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>

<body>

    <header class="franja-superior">
        <div style="margin-TOP: 20px;" class="logo">INICIA SESIÓN</div>
        <style>/* RESPONSIVE para formulario de inicio de sesión */
@media (max-width: 768px) {
  .formulario {
    width: 90%;
    padding: 2rem 1rem;
    margin: 0 auto;
    box-sizing: border-box;
  }

  .campo {
    flex-direction: column;
    align-items: stretch;
    gap: 8px;
  }

  .campo input {
    width: 100%;
    font-size: 1rem;
  }

  .boton {
    width: 100%;
    font-size: 1rem;
    padding: 12px;
    margin-top: 10px;
  }

  .mensaje-login {
    font-size: 0.95rem;
    text-align: center;
    margin-top: 20px;
  }

  .mensaje-login a {
    display: inline-block;
    margin-top: 5px;
  }
}
</style>
    </header>

    <main class="FONDO_REGISTRO">
        <img src="imagenes/INICIO_SESION.png" alt="INICIO_SESION" width="298" height="auto">
        <form action="db/ingreso.php" method="post">
            <div class="formulario">
                <div class="campo">
                    <h2>EMAIL:</h2>
                    <input type="text" name="email" placeholder=" Escriba su email">
                </div>

                <div class="campo">
                    <h2>CONTRASEÑA:</h2>
                    <input type="password" name="contrasenia" placeholder=" Escribe su contraseña">
                </div>

                <div class="campo">
                    <input type="submit" name="button" id="button" value="Login" />
                </div>
                <div class="mensaje-login">
                    ¿Ya tienes cuenta? <a href="registro.php">REGISTRATE</a>
                </div>
            </div>
        </form>



    </main>
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
    <script src="buscador.js"></script>
</body>

</html>