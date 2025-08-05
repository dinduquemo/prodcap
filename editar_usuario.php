<?php
// === PARTE BACKEND ===
// Conexión a la base de datos
require_once __DIR__ . '/db/conexion.php';

// Asegúrate de que $pdo esté disponible desde conexion.php

// 1. Procesamiento del formulario POST (cuando se envían los cambios)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $contraseña_nueva = $_POST['contrasena_nueva'] ?? ''; // Nuevo campo para la contraseña
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    // Validar ID
    if (empty($id) || !is_numeric($id)) {
        die("⚠️ ID de usuario no válido.");
    }

    // Validar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($email) || empty($rol)) {
        echo "<p style='color: red; font-weight: bold;'>Error: Nombre, Apellido, Email y Rol son campos obligatorios.</p>";
        echo "<p><a href='editar_usuario.php?id=" . htmlspecialchars($id) . "'>← Volver al formulario de edición</a></p>";
        exit;
    }

    // Opcional: Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red; font-weight: bold;'>Error: Formato de email inválido.</p>";
        echo "<p><a href='editar_usuario.php?id=" . htmlspecialchars($id) . "'>← Volver al formulario de edición</a></p>";
        exit;
    }

    // Consulta SQL base para UPDATE
    $sql = "UPDATE usuarios SET 
                nombre = ?, 
                apellido = ?, 
                email = ?, 
                rol = ?, 
                direccion = ?, 
                telefono = ?";
    $params = [$nombre, $apellido, $email, $rol, $direccion, $telefono];

    // Si se proporcionó una nueva contraseña, la hasheamos y la añadimos a la consulta
    if (!empty($contraseña_nueva)) {
        $hashPassword = password_hash($contraseña_nueva, PASSWORD_DEFAULT);
        $sql .= ", contraseña = ?";
        $params[] = $hashPassword;
    }

    // Añadir la condición WHERE al final
    $sql .= " WHERE id = ?";
    $params[] = $id;

    try {
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute($params);

        if ($resultado) {
            echo "<p style='color: green; font-weight: bold;'>✅ Usuario actualizado con éxito.</p>";
            echo '<p><a href="admin_usuarios.php">← Volver al panel de usuarios</a></p>';
            exit;
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ Error al actualizar el usuario.</p>";
            echo "<p><a href='editar_usuario.php?id=" . htmlspecialchars($id) . "'>← Volver al formulario de edición</a></p>";
            exit;
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos (ej. email duplicado si es UNIQUE)
        if ($e->getCode() == 23000) { // Código para "Duplicate entry" en MySQL
            echo "<p style='color: red; font-weight: bold;'>Error: El email '$email' ya está registrado para otro usuario. Por favor, usa otro.</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>Error al actualizar usuario: " . $e->getMessage() . "</p>";
        }
        echo "<p><a href='editar_usuario.php?id=" . htmlspecialchars($id) . "'>← Volver al formulario de edición</a></p>";
        exit;
    }
}

// 2. Obtención de datos para mostrar el formulario (cuando se carga la página por primera vez con un ID)
// Si no es un POST, significa que se está intentando acceder al formulario de edición.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("⚠️ ID de usuario no especificado o no válido.");
}

$id = $_GET['id'];

// Función para obtener el usuario por ID
function obtenerUsuarioPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$usuario = obtenerUsuarioPorId($pdo, $id);

if (!$usuario) {
    die("❌ Usuario no encontrado.");
}

// === PARTE FRONTEND (HTML) ===
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            background-color: #f4e3d3;
            font-family: Arial, sans-serif;
        }
        .contenedor {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4a2e13;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-top: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        select {
            height: 40px;
            background-color: #fff;
            cursor: pointer;
        }
        .boton {
            background-color: #4a2e13;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            margin-top: 25px;
            cursor: pointer;
            font-weight: bold;
            display: block;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .boton:hover {
            background-color: #5c3a1c;
        }
        p {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1em;
        }
        p a {
            color: #4a2e13;
            text-decoration: none;
            font-weight: bold;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>
    <div class="contenedor">
        <h2>Editar Usuario: <?= htmlspecialchars($usuario['nombre']) ?></h2>

        <form action="editar_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="admin" <?= ($usuario['rol'] === 'admin') ? 'selected' : '' ?>>Administrador</option>
                <option value="cliente" <?= ($usuario['rol'] === 'cliente') ? 'selected' : '' ?>>Cliente</option>
            </select>

            <label for="contrasena_nueva">Nueva Contraseña (dejar vacío para no cambiar):</label>
            <input type="password" id="contrasena_nueva" name="contrasena_nueva" placeholder="Ingrese nueva contraseña si desea cambiarla">
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>">

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" pattern="[0-9]{10}" placeholder="Ej: 3001234567">
            <small>Formato: 10 dígitos (ej. 1234567890)</small>

            <button type="submit" class="boton">Guardar Cambios</button>
        </form>
    </div>
    <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
    <script src="buscador.js"></script>
</body>
</html>