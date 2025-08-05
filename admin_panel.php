<?php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="estilo_admin.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #fce4ec; /* fondo rosado */
    }

    .contenedor-panel {
      max-width: 800px;
      margin: 40px auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .boton-panel {
      display: inline-block;
      margin: 15px;
      padding: 15px 30px;
      font-size: 16px;
      background-color: #3e2600;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }

    .boton-panel:hover {
      background-color: #5a3b08;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #3e2600;
    }
  </style>
</head>

<body>
  <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>

  <div class="contenedor-panel">
    <h2>Panel de Administración</h2>

    <a href="admin_productos.php" class="boton-panel">🧴 Productos</a>
    <a href="admin_usuarios.php" class="boton-panel">👥 Usuarios</a>
    <a href="admin_pedidos.php" class="boton-panel">📦 Pedidos</a>
    <a href="admin_pagos.php" class="boton-panel">💳 Pagos</a>
    <a href="admin_resenas.php" class="boton-panel">⭐ Reseñas</a>
  </div>

  <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
  <script src="buscador.js"></script>
</body>
</html>
