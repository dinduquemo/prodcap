<?php
require_once __DIR__ . '/db/conexion.php';

// Obtener todos los usuarios y productos
$usuarios = $pdo->query("SELECT id, nombre, apellido FROM usuarios WHERE rol = 'cliente'")->fetchAll(PDO::FETCH_ASSOC);
$productos = $pdo->query("SELECT id, nombre, precio FROM productos")->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $productos_seleccionados = $_POST['producto'];
    $cantidades = $_POST['cantidad'];

    $subtotal = 0;
    $detalle_items = [];

    // Calcular subtotal y preparar items
    foreach ($productos_seleccionados as $index => $producto_id) {
        $cantidad = (int)$cantidades[$index];
        $stmt = $pdo->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch();

        if ($producto && $cantidad > 0) {
            $subtotal_item = $producto['precio'] * $cantidad;
            $subtotal += $subtotal_item;
            $detalle_items[] = [
                'producto_id' => $producto_id,
                'nombre_producto' => $producto['nombre'],
                'cantidad' => $cantidad,
                'precio_unitario' => $producto['precio'],
                'subtotal_item' => $subtotal_item
            ];
        }
    }

    // Impuestos y envío (fijo o calculado)
    $impuestos = $subtotal * 0.19; // IVA 19%
    $envio = 9000; // Por ejemplo
    $total_pedido = $subtotal + $impuestos + $envio;

    // Insertar pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos 
        (user_id, estado, total_pedido, subtotal, impuestos, costo_envio, 
         nombre_envio, apellido_envio, direccion_envio, ciudad_envio, email_envio, metodo_pago) 
        VALUES (?, 'Pendiente', ?, ?, ?, ?, '', '', '', '', '', 'manual')");
    $stmt->execute([$user_id, $total_pedido, $subtotal, $impuestos, $envio]);
    $pedido_id = $pdo->lastInsertId();

    // Insertar detalle del pedido
    foreach ($detalle_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO detalle_pedido 
            (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario, subtotal_item) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $pedido_id,
            $item['producto_id'],
            $item['nombre_producto'],
            $item['cantidad'],
            $item['precio_unitario'],
            $item['subtotal_item']
        ]);
    }

    header("Location: admin_pedidos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Pedido</title>
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        .formulario {
            background-color: white;
            padding: 25px;
            max-width: 700px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4a2e13;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .grupo-producto {
            margin-top: 20px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            background-color: #4a2e13;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5c3a1c;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Agregar nuevo pedido</h2>
        <form method="post">
            <label for="user_id">Cliente:</label>
            <select name="user_id" required>
                <option value="">Seleccionar cliente</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['nombre'] . ' ' . $u['apellido'] ?></option>
                <?php endforeach; ?>
            </select>

            <div class="grupo-producto">
                <label>Productos y cantidades:</label>
                <?php foreach ($productos as $index => $producto): ?>
                    <div style="margin-bottom: 8px;">
                        <input type="checkbox" name="producto[]" value="<?= $producto['id'] ?>" id="producto<?= $index ?>">
                        <label for="producto<?= $index ?>"><?= $producto['nombre'] ?> - $<?= number_format($producto['precio'], 0) ?></label>
                        <input type="number" name="cantidad[]" placeholder="Cantidad" min="1" value="1" style="width: 80px;">
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit">Guardar pedido</button>
        </form>
    </div>
</body>
</html>
