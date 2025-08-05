<?php
// Recibir datos del formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso no permitido");
}

$id = intval($_POST['id']);
$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$precio = floatval($_POST['precio']);
$stock = trim($_POST['stock']);
$categoria = trim($_POST['categoria']);
$tipo_cabello = trim($_POST['tipo_cabello']);

// Aquí podrías validar datos...

// Simulamos la actualización mostrando lo que se recibió
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Producto Actualizado</title>
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            margin: 2rem;
        }
        .mensaje {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        a {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 1rem 2rem;
            background: #4a2e13;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }
        a:hover {
            background: #5c3a1c;
        }
    </style>
</head>
<body>

<div class="mensaje">
    <h2>Producto actualizado correctamente (simulado)</h2>
    <p><strong>ID:</strong> <?= htmlspecialchars($id) ?></p>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($descripcion) ?></p>
    <p><strong>Precio:</strong> $<?= number_format($precio, 2) ?> COP</p>
    <p><strong>Stock:</strong> <?= htmlspecialchars($stock) ?></p>
    <p><strong>Categoría:</strong> <?= htmlspecialchars($categoria) ?></p>
    <p><strong>Tipo de Cabello:</strong> <?= htmlspecialchars($tipo_cabello) ?></p>

    <a href="admin_productos.php">Volver al panel de administración</a>
</div>

</body>
</html>
