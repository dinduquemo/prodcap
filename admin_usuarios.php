<?php
// Conectar a la base de datos
require_once __DIR__ . '/db/conexion.php';

// Consultar los usuarios de la tabla 'usuarios'
$stmt = $pdo->prepare("SELECT * FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de usuarios</title>
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
    <h2>Administración de usuarios</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="agregar_usuario.php" class="boton-agregar">+ Agregar nuevo usuario</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <!-- <th>Contraseña</th> -->
                <th>Dirección</th>
                <th>Telefono</th>
                <th>Rol</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>        
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                <td><?=  htmlspecialchars($usuario['email']) ?> </td>
                <!-- <td><?= htmlspecialchars($usuario['contraseña']) ?></td> -->
                <td><?= htmlspecialchars($usuario['direccion']) ?></td>
                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                <td><?= htmlspecialchars($usuario['rol']) ?></td>
                <td class="acciones">
                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">Editar</a> |
                    <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">Eliminar</a>
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
