<?php
require_once __DIR__ . '/db/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ ID de pedido inválido.");
}

$pedido_id = intval($_GET['id']);

// Eliminar el pedido
$stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
if ($stmt->execute([$pedido_id])) {
    header("Location: admin_pedidos.php");
    exit;
} else {
    echo "❌ Error al eliminar el pedido.";
}
?>
