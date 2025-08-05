<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>REGISTRO DE INFORMACIÓN</title>
  <link rel="stylesheet" href="estilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>

  <header class="franja-superior">
    <div style="margin-top: 20px;" class="logo">REGISTRO DE INFORMACIÓN</div>
  </header>

  <main class="FONDO_REGISTRO">
    <form action="db/registrarse.php" method="post">
      <div class="formulario">
        <div class="campo">
          <h2>Nombre:</h2>
          <input type="text" name="nombre" placeholder="Ingrese su nombre" required>
        </div>
        <div class="campo">
          <h2>Apellido:</h2>
          <input type="text" name="apellido" placeholder="Ingrese su apellido" required>
        </div>
        <div class="campo">
          <h2>Correo electrónico:</h2>
          <input type="email" name="email" placeholder="Ingrese su email" required>
        </div>
        <div class="campo">
          <h2>Contraseña:</h2>
          <input type="password" name="password" placeholder="Ingrese su contraseña" required>
        </div>
        <div class="campo">
          <input type="submit" value="Registrarse" />
        </div>
      </div>
    </form>
  </main>

  <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
  <script src="buscador.js"></script>
</body>
</html>
