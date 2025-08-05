<?php
session_start();
require_once __DIR__ . '/db/conexion.php';

// Validar que el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    echo "❌ Debes iniciar sesión para eliminar una reseña.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Validar si se recibe el ID de la reseña por la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ ID de reseña inválido.";
    exit;
}

$resena_id = intval($_GET['id']);

// Verificar que la reseña le pertenece al usuario logueado
$stmt = $pdo->prepare("SELECT * FROM reseñas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$resena_id, $usuario_id]);
$resena = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resena) {
    echo "❌ No tienes permiso para eliminar esta reseña o no existe.";
    exit;
}

// Eliminar la reseña
$stmt = $pdo->prepare("DELETE FROM reseñas WHERE id = ?");
if ($stmt->execute([$resena_id])) {
    echo "<p style='color: green; font-weight: bold;'>✅ Reseña eliminada correctamente.</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Error al eliminar la reseña.</p>";
}

// Regresar al panel de reseñas (cambia el archivo según tu estructura)
echo "<p><a href='admin_resenas.php'>← Volver al panel de reseñas</a></p>";
?>

