<?php
// Asegúrate de que esta ruta sea correcta para tu archivo de conexión a la base de datos
require_once __DIR__ . '/db/conexion.php';

// Asegúrate de que $pdo esté disponible desde conexion.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $contraseña_plana = $_POST['contraseña'] ?? ''; // La contraseña en texto plano del formulario
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $rol = $_POST['rol'] ?? ''; // El valor del desplegable

    // 1. Validar datos (¡IMPORTANTE!)
    if (empty($nombre) || empty($apellido) || empty($email) || empty($contraseña_plana) || empty($rol)) {
        echo "<p style='color: red; font-weight: bold;'>Error: Todos los campos obligatorios deben ser llenados.</p>";
        echo "<p><a href='agregar_usuario.php'>← Volver al formulario</a></p>";
        exit;
    }

    // Opcional: Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red; font-weight: bold;'>Error: Formato de email inválido.</p>";
        echo "<p><a href='agregar_usuario.php'>← Volver al formulario</a></p>";
        exit;
    }

    // 2. Hashear la contraseña por seguridad
    // PASSWORD_DEFAULT utiliza el algoritmo más fuerte disponible y compatible
    $hashPassword = password_hash($contraseña_plana, PASSWORD_DEFAULT);

    // 3. Preparar e insertar en la base de datos
    // Asegúrate de que el orden de las columnas en tu tabla 'usuarios'
    // coincida con el orden de los parámetros aquí.
    // También verifica si tienes la columna 'fecha_registro' en tu tabla.
    // Si no tienes 'fecha_registro', elimina `, fecha_registro` de la SQL y `, NOW()` de los VALUES.
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, rol, contraseña, direccion, telefono, fecha_registro)
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"); // Asumo que tienes 'fecha_registro'
        
        $stmt->execute([$nombre, $apellido, $email, $rol, $hashPassword, $direccion, $telefono]);

        echo "<p style='color: green; font-weight: bold;'>✅ Usuario agregado correctamente.</p>";
        echo "<p><a href='admin_usuarios.php'>← Volver al panel de usuarios</a></p>";
        exit;

    } catch (PDOException $e) {
        // Manejo de errores de la base de datos (ej. email duplicado si es UNIQUE)
        if ($e->getCode() == 23000) { // Código para "Duplicate entry" en MySQL
            echo "<p style='color: red; font-weight: bold;'>Error: El email '$email' ya está registrado. Por favor, usa otro.</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>Error al agregar usuario: " . $e->getMessage() . "</p>";
        }
        echo "<p><a href='agregar_usuario.php'>← Volver al formulario</a></p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar usuario</title>
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra suave */
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4a2e13; /* Color de título oscuro */
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #333;
        }

        input[type="text"],
        input[type="email"], /* Para el campo email */
        input[type="password"], /* Para el campo contraseña */
        input[type="tel"], /* Para el campo teléfono */
        textarea,
        select { /* Estilo para el nuevo select */
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-top: 5px;
            box-sizing: border-box; /* Incluir padding y borde en el width */
            font-size: 16px; /* Asegurar que el texto sea legible */
        }

        select {
            height: 40px; /* Altura consistente con los inputs */
            background-color: #fff;
            cursor: pointer;
        }

        .boton {
            background-color: #4a2e13;
            color: white;
            padding: 12px 25px; /* Ajustado padding */
            border: none;
            border-radius: 10px;
            margin-top: 25px; /* Más margen superior */
            cursor: pointer;
            font-weight: bold;
            display: block; /* Ocupa todo el ancho */
            width: 100%; /* Ocupa todo el ancho */
            font-size: 18px; /* Texto más grande en el botón */
            transition: background-color 0.3s ease; /* Transición suave */
        }

        .boton:hover {
            background-color: #5c3a1c;
        }

        /* Estilos para mensajes de éxito/error */
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
        <h2>Agregar Nuevo Usuario</h2>

        <form method="POST" action="agregar_usuario.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required> 

            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="admin">Administrador</option>
                <option value="cliente">Cliente</option>
            </select>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contraseña" required> <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required pattern="[0-9]{10}"> <small>Formato: 1234567890 (10 dígitos)</small>

            <button type="submit" class="boton">Agregar usuario</button>
        </form>
    </div>
    <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
    <script src="buscador.js"></script>
</body>
</html>