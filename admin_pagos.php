<?php
require_once __DIR__ . '/db/conexion.php';

$sql = "SELECT * FROM pagos ORDER BY fecha_pago DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Pagos</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2, footer {
            text-align: center;
        }

        .contenedor {
            max-width: 95%;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

        tr:hover {
            background-color: #f5f0eb;
        }

        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #555;
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>

<div class="contenedor">
    <h2>Administración de Pagos</h2>

    <table>
        <thead>
            <tr>
                <th>ID Pago</th>
                <th>ID Pedido</th>
                <th>Método</th>
                <th>Estado</th>
                <th>Fecha de Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagos as $pago): ?>
                <tr>
                    <td><?= $pago['id'] ?></td>
                    <td><?= $pago['pedido_id'] ?></td>
                    <td><?= htmlspecialchars($pago['metodo_pago']) ?></td>
                    <td><?= htmlspecialchars($pago['estado_pago']) ?></td>
                    <td><?= htmlspecialchars($pago['fecha_pago']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
</body>
</html>
