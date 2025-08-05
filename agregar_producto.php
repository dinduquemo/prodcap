<?php
require_once __DIR__ . '/db/conexion.php'; // Asegúrate de tener este archivo creado y funcionando

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $stock = $_POST['stock'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $tipo_cabello = $_POST['tipo_cabello'] ?? '';
    $imagen = $_POST['imagen'] ?? ''; // Aquí asumimos que se escribe el nombre de la imagen manualmente

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, stock, categoria, tipo_cabello, creado_en)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->execute([$nombre, $descripcion, $precio, $imagen, $stock, $categoria, $tipo_cabello]);

    echo "<p style='color: green; font-weight: bold;'>✅ Producto agregado correctamente.</p>";
    echo "<p><a href='admin_productos.php'>← Volver al panel</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Producto</title>
  <style>
    body {
      background-color: #f4e3d3;
      font-family: Arial, sans-serif;
    }

    .contenedor {
      max-width: 600px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 15px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
      margin-top: 5px;
    }

    .boton {
      background-color: #4a2e13;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      margin-top: 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .boton:hover {
      background-color: #5c3a1c;
    }
  </style>
  <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>
  <div class="contenedor">
    <h2>Agregar Nuevo Producto</h2>

    <form method="POST" action="agregar_producto.php">
      <label>Nombre:</label>
      <input type="text" name="nombre" required>

      <label>Descripción:</label>
      <textarea name="descripcion" required></textarea>

      <label>Precio (COP):</label>
      <input type="number" name="precio" required>

      <label>Nombre del archivo de imagen (ej: shampoo.png):</label>
      <input type="text" name="imagen" required>

      <label>Stock:</label>
      <input type="text" name="stock" required>

      <label>Categoría:</label>
      <input type="text" name="categoria" required>

      <label>Tipo de cabello:</label>
      <select id="tipo_cabello" name="tipo_cabello" required>
        <option value="">Selecciona un tipo</option>
        <option value="rizado">Rizado</option>
        <option value="ondulado">Ondulado</option>
        <option value="liso">Liso</option>
      </select>

      <button type="submit" class="boton">Agregar Producto</button>
    </form>
  </div>
  <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
<script src="buscador.js"></script>
</body>
</html>
