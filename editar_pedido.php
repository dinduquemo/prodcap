<?php
require_once __DIR__ . '/db/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ ID de pedido inválido.");
}

$pedido_id = intval($_GET['id']);

// Obtener datos actuales del pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("❌ Pedido no encontrado.");
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = $_POST['estado'];

    $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    if ($stmt->execute([$nuevo_estado, $pedido_id])) {
        header("Location: admin_pedidos.php");
        exit;
    } else {
        $error = "❌ Error al actualizar el estado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            padding: 50px;
        }
        .formulario {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        select, button {
            padding: 10px;
            margin-top: 10px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4a2e13;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h2>Editar estado del pedido</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" required>
                <option value="Pendiente" <?= $pedido['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="Enviado" <?= $pedido['estado'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                <option value="Entregado" <?= $pedido['estado'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
            </select>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</body>
</html>
