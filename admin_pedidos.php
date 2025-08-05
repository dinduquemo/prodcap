<?php
require_once __DIR__ . '/db/conexion.php'; // conexión con $pdo

$sql = "SELECT id, 
               nombre_envio, apellido_envio, email_envio, 
               direccion_envio, ciudad_envio, telefono_envio, 
               total_pedido, estado, fecha_pedido 
        FROM pedidos 
        ORDER BY fecha_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de pedidos</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            max-width: 95%;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .boton-agregar {
            display: inline-block;
            background-color: #4a2e13;
            color: white;
            padding: 10px 18px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 20px;
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

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .acciones a {
            margin: 0 5px;
            text-decoration: none;
            color: #4a2e13;
            font-weight: bold;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>

<div class="contenedor">
    <h2>Administración de pedidos</h2>
    <a href="agregar_pedido.php" class="boton-agregar">+ Agregar pedido</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Teléfono</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= htmlspecialchars($pedido['nombre_envio']) ?></td>
                <td><?= htmlspecialchars($pedido['apellido_envio']) ?></td>
                <td><?= htmlspecialchars($pedido['email_envio']) ?></td>
                <td><?= htmlspecialchars($pedido['direccion_envio']) ?></td>
                <td><?= htmlspecialchars($pedido['ciudad_envio']) ?></td>
                <td><?= htmlspecialchars($pedido['telefono_envio']) ?></td>
                <td>$<?= number_format($pedido['total_pedido'], 0) ?> COP</td>
                <td><?= htmlspecialchars($pedido['estado']) ?></td>
                <td><?= htmlspecialchars($pedido['fecha_pedido']) ?></td>
                <td class="acciones">
                    <a href="editar_pedido.php?id=<?= $pedido['id'] ?>">Editar</a> |
                    <a href="eliminar_pedido.php?id=<?= $pedido['id'] ?>" onclick="return confirm('¿Eliminar este pedido?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
>

<?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
</body>
</html>
