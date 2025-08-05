<?php
require_once __DIR__ . '/db/conexion.php';

// Validar si se recibe el ID por la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ ID de producto inválido.";
    exit;
}

$id = intval($_GET['id']);

// Preparar y ejecutar la eliminación
$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
if ($stmt->execute([$id])) {
    echo "<p style='color: green; font-weight: bold;'>✅ Producto eliminado correctamente.</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Error al eliminar el producto.</p>";
}

echo "<p><a href='admin_productos.php'>← Volver al panel</a></p>";
?>
