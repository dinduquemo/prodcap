<?php
// === PARTE BACKEND ===
// Conexión a la base de datos
require_once __DIR__ . '/db/conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];
    $tipo_cabello = $_POST['tipo_cabello'];

    // Prepara y ejecuta el UPDATE
    $sql = "UPDATE productos SET 
        nombre = ?, 
        descripcion = ?, 
        precio = ?, 
        imagen = ?, 
        stock = ?, 
        categoria = ?, 
        tipo_cabello = ? 
        WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
        $nombre, $descripcion, $precio, $imagen, $stock, $categoria, $tipo_cabello, $id
    ]);

    if ($resultado) {
        echo "<h2>✅ Producto actualizado con éxito.</h2>";
        echo '<a href="admin_productos.php">← Volver al panel</a>';
        exit;
    } else {
        echo "❌ Error al actualizar el producto.";
    }
}


// Función para obtener el producto por ID
function obtenerProductoPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Procesamiento del formulario (si se envió por POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];
    $tipo_cabello = $_POST['tipo_cabello'];

    // Consulta UPDATE para modificar el producto
    $sql = "UPDATE productos SET 
                nombre = ?, 
                descripcion = ?, 
                precio = ?, 
                imagen = ?, 
                stock = ?, 
                categoria = ?, 
                tipo_cabello = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nombre, $descripcion, $precio, $imagen, $stock, $categoria, $tipo_cabello, $id
    ]);

    echo "<h2>✅ Producto actualizado con éxito</h2>";
    echo "<a href='admin_productos.php'>← Volver al panel</a>";
    exit;
}

// Si llega por GET (para mostrar el formulario)
if (!isset($_GET['id'])) {
    die("⚠️ ID de producto no especificado.");
}

$id = $_GET['id'];
$producto = obtenerProductoPorId($pdo, $id);

if (!$producto) {
    die("❌ Producto no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
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
        <h2>Editar Producto: <?= htmlspecialchars($producto['nombre']) ?></h2>

        <form action="editar_producto.php" method="POST">
            <input type="hidden" name="id" value="<?= $producto['id'] ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>">

            <label>Descripción:</label>
            <textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>

            <label>Precio:</label>
            <input type="number" name="precio" value="<?= $producto['precio'] ?>">

            <label>Imagen:</label>
            <input type="text" name="imagen" value="<?= htmlspecialchars($producto['imagen']) ?>">

            <label>Stock:</label>
            <input type="text" name="stock" value="<?= htmlspecialchars($producto['stock']) ?>">

            <label>Categoría:</label>
            <input type="text" name="categoria" value="<?= htmlspecialchars($producto['categoria']) ?>">

            <label>Tipo de Cabello:</label>
            <input type="text" name="tipo_cabello" value="<?= htmlspecialchars($producto['tipo_cabello']) ?>">

            <div class="campo">
                <input type="submit" name="button" id="button" value="Guardar Cambios" />
            </div>

            <!-- <button type="submit"></button> -->
        </form>
    </div>
    
</body>
</html>
