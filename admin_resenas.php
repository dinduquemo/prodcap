<?php
require_once __DIR__ . '/db/conexion.php'; // este archivo ya define $pdo

$sql = "SELECT r.id, r.puntuacion, r.comentario, r.fecha, 
               u.nombre, u.apellido, u.email, u.direccion, u.telefono, u.rol, 
               p.nombre AS producto
        FROM reseñas r
        LEFT JOIN usuarios u ON r.usuario_id = u.id
        LEFT JOIN productos p ON r.producto_id = p.id
        ORDER BY r.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$reseñas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


$resultado = $conn->query($sql);
$reseñas = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de reseñas</title>
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
    <h2>Administración de reseñas</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Producto</th>
                <th>Puntuación</th>
                <th>Comentario</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reseñas as $reseña): ?>
            <tr>
                <td><?= $reseña['id'] ?></td>
                <td><?= htmlspecialchars($reseña['nombre']) ?></td>
                <td><?= htmlspecialchars($reseña['apellido']) ?></td>
                <td><?= htmlspecialchars($reseña['email']) ?></td>
                <td><?= htmlspecialchars($reseña['direccion']) ?></td>
                <td><?= htmlspecialchars($reseña['telefono']) ?></td>
                <td><?= htmlspecialchars($reseña['rol']) ?></td>
                <td><?= htmlspecialchars($reseña['producto']) ?></td>
                <td><?= htmlspecialchars($reseña['puntuacion']) ?></td>
                <td><?= htmlspecialchars($reseña['comentario']) ?></td>
                <td><?= htmlspecialchars($reseña['fecha']) ?></td>
                <td class="acciones">
                
                    <a href="eliminar_resena.php?id=<?= $reseña['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este reseña?')">Eliminar</a>
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