<?php
// Conectar a la base de datos
require_once __DIR__ . '/db/conexion.php';

// Consultar los productos de la tabla 'productos'
$stmt = $pdo->prepare("SELECT * FROM productos ORDER BY id ASC");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Productos</title>
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, footer {
            text-align: center;
        }

        .contenedor {
            max-width: 90%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
        }

        .boton-agregar {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: #4a2e13;
            color: white;
            padding: 10px 18px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
        }

        .boton-agregar:hover {
            background-color: #5c3a1c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #4a2e13;
            color: white;
            padding: 10px;
        }
 
    .acciones a {
            margin: 0 5px;
            text-decoration: none;
            color: #4a2e13;
            font-weight: bold;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        img {
            width: 50px;
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>

<div class="contenedor">
    <h2>Administración de Productos</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="agregar_producto.php" class="boton-agregar">+ Agregar nuevo producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Tipo Cabello</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['id'] ?></td>
                <td>  <img src="imagenes/<?= htmlspecialchars($producto['imagen']) ?>" 
       alt="<?= htmlspecialchars($producto['nombre']) ?>" 
       width="50"></td>           
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                <td>$<?= number_format($producto['precio']) ?> COP</td>
                <td><?= htmlspecialchars($producto['stock']) ?></td>
                <td><?= htmlspecialchars($producto['categoria']) ?></td>
                <td><?= htmlspecialchars($producto['tipo_cabello']) ?></td>
                <td class="acciones">
    <a href="editar_producto.php?id=<?= $producto['id'] ?>">Editar</a>
    <a href="eliminar_producto.php?id=<?= $producto['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')">Eliminar</a>
</td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
<script src="buscador.js"></script>
</body>
</html>
