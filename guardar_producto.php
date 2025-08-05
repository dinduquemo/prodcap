<?php
include_once('db/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $stock = $_POST['stock'];
    $categoria = $_POST['categoria'];
    $tipo_cabello = $_POST['tipo_cabello'];

    // Manejo de la imagen subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['imagen']['name'];
        $tipoArchivo = $_FILES['imagen']['type'];
        $tamanoArchivo = $_FILES['imagen']['size'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];

        // Puedes validar el tipo y tamaño aquí (opcional)
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if (in_array($extension, $extensionesPermitidas)) {
            $nuevoNombre = uniqid() . "." . $extension;
            $rutaDestino = "imagenes_productos/" . $nuevoNombre;

            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                // Inserción en la base de datos
                $sql = "INSERT INTO productos (nombre, precio, descripcion, stock, categoria, tipo_cabello, imagen) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sdsiiss", $nombre, $precio, $descripcion, $stock, $categoria, $tipo_cabello, $rutaDestino);

                if ($stmt->execute()) {
                    header("Location: admin_productos.php");
                    exit();
                } else {
                    echo "Error al guardar el producto: " . $conexion->error;
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "Tipo de archivo no permitido.";
        }
    } else {
        echo "No se recibió imagen o hubo un error.";
    }
} else {
    echo "Método no permitido.";
}
?>
