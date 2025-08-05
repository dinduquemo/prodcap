<?php
session_start();
include "db/conexion.php";

// Validar ID del producto
if (!isset($_GET['producto_id']) || !is_numeric($_GET['producto_id'])) {
    die("Producto no especificado correctamente.");
}

$producto_id = (int)$_GET['producto_id'];
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

if (!$usuario_id) {
    die("Debes iniciar sesión para añadir una reseña.");
}

// Verificar si ya existe una reseña de este usuario para el producto
$stmt = $pdo->prepare("SELECT id FROM reseñas WHERE producto_id = :producto_id AND usuario_id = :usuario_id");
$stmt->execute(['producto_id' => $producto_id, 'usuario_id' => $usuario_id]);
$yaExiste = $stmt->fetch();

$mensaje = "";

// Procesar el formulario al enviarse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $puntuacion = (int) $_POST['puntuacion'];
    $comentario = trim($_POST['comentario']);

    if ($yaExiste) {
        $mensaje = "❌ Ya has enviado una reseña para este producto.";
    } elseif ($puntuacion < 1 || $puntuacion > 5 || empty($comentario)) {
        $mensaje = "❌ Todos los campos son obligatorios y la puntuación debe ser entre 1 y 5.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reseñas (usuario_id, producto_id, puntuacion, comentario) 
                               VALUES (:usuario_id, :producto_id, :puntuacion, :comentario)");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'producto_id' => $producto_id,
            'puntuacion' => $puntuacion,
            'comentario' => $comentario
        ]);

        header("Location: detalle.php?id=$producto_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Reseña</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            background-color: #f5f0eb;
            font-family: Arial, sans-serif;
            padding: 50px;
        }

        .formulario {
            background-color: white;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #8c6e4a;
            text-align: center;
            font-family: 'Hatton', serif;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .boton {
            margin-top: 20px;
            background-color: #8c6e4a;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            display: block;
            width: 100%;
        }

        .mensaje-error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .enlace-volver {
            text-align: center;
            margin-top: 20px;
        }

        .enlace-volver a {
            color: #8c6e4a;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Añadir Reseña</h2>

        <?php if ($mensaje): ?>
            <p class="mensaje-error"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="puntuacion">Puntuación:</label>
            <select name="puntuacion" id="puntuacion" required>
                <option value="">Selecciona una opción</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?> estrella<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>

            <label for="comentario">Comentario:</label>
            <textarea name="comentario" id="comentario" rows="5" required></textarea>

            <button type="submit" class="boton">Enviar reseña</button>
        </form>

        <div class="enlace-volver">
            <a href="detalle.php?id=<?= $producto_id ?>">← Volver al producto</a>
        </div>
    </div>
</body>
</html>
