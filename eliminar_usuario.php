<?php
require_once __DIR__ . '/db/conexion.php';

// Validar si se recibe el ID por la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ ID de usuario inválido.";
    exit;
}

$id = intval($_GET['id']);

// Preparar y ejecutar la eliminación
$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
if ($stmt->execute([$id])) {
    echo "<p style='color: green; font-weight: bold;'>✅ usuario eliminado correctamente.</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Error al eliminar el usuario.</p>";
}

echo "<p><a href='admin_usuarios.php'>← Volver al panel</a></p>";
?>
