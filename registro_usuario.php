<?php
// Habilitar cabeceras para aceptar solicitudes del frontend
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

// Incluir la conexión a la base de datos
include "db/conexion.php";

// Leer el JSON recibido desde el frontend (React o Postman)
$datos = json_decode(file_get_contents("php://input"), true);

// Validar que lleguen todos los campos necesarios
if (
    !isset($datos['nombre']) ||
    !isset($datos['apellido']) ||
    !isset($datos['email']) ||
    !isset($datos['password'])
) {
    echo json_encode(["mensaje" => "Datos incompletos"]);
    exit;
}

// Limpiar los datos para evitar inyecciones SQL
$nombre = mysqli_real_escape_string($conexion, $datos['nombre']);
$apellido = mysqli_real_escape_string($conexion, $datos['apellido']);
$email = mysqli_real_escape_string($conexion, $datos['email']);
$password = mysqli_real_escape_string($conexion, $datos['password']);

// Insertar en la tabla (asegúrate de que los campos existen)
$sql = "INSERT INTO usuarios (nombre, apellido, email, contraseña) 
        VALUES ('$nombre', '$apellido', '$email', '$password')";

// Enviar respuesta al frontend
if (mysqli_query($conexion, $sql)) {
    echo json_encode(["mensaje" => "Usuario registrado correctamente"]);
} else {
    echo json_encode(["mensaje" => "Error al registrar: " . mysqli_error($conexion)]);
}
?>

