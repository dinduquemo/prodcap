<?php
// detalle.php

// Incluir el archivo de conexión a la base de datos
include "db/conexion.php";

// 1. Validar la entrada del ID del producto
if (empty($_GET['id'])) {
    die("Error: ID de producto no especificado.");
}

$id = (int) $_GET['id']; // Convertir el ID a entero por seguridad (prevención de inyección SQL)

// 2. Consultar la información del producto principal desde la tabla 'productos'
$stmt_prod = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
$stmt_prod->execute(['id' => $id]);
$prod = $stmt_prod->fetch(); // Por defecto, PDO::FETCH_ASSOC si está configurado en conexion.php

// Si el producto no se encuentra en la base de datos, mostrar un error
if (!$prod) {
    die("Error: Producto no encontrado.");
}

// 3. Obtener el promedio de calificación (rating) y el número total de reseñas
// Esta es una consulta separada para obtener las estadísticas globales de las reseñas.
$stmt_rating_count = $pdo->prepare("SELECT AVG(puntuacion) AS rating_promedio, COUNT(*) AS total_resenas FROM reseñas WHERE producto_id = :producto_id");
$stmt_rating_count->execute(['producto_id' => $id]);
$resena_stats = $stmt_rating_count->fetch();

// Inicializar $rating y $num_resenas. Esto previene el "Undefined variable" si no hay reseñas.
$rating = 0; // Valor por defecto si no hay reseñas
$num_resenas = 0; // Valor por defecto si no hay reseñas

if ($resena_stats && $resena_stats['total_resenas'] > 0) {
    $rating = (float)$resena_stats['rating_promedio'];
    $num_resenas = (int)$resena_stats['total_resenas'];
}

// 4. Obtener los detalles de cada reseña para listarlas en el modal
// Esta consulta obtiene todas las reseñas individuales.
$stmt_resenas_list = $pdo->prepare("SELECT puntuacion, comentario, fecha FROM reseñas WHERE producto_id = :producto_id ORDER BY fecha DESC");
$stmt_resenas_list->execute(['producto_id' => $id]);
$reseñas = $stmt_resenas_list->fetchAll(); // Obtener todas las reseñas como un array de arrays asociativos

// 5. Preparar los datos del producto para ser mostrados en el HTML,
// escapando caracteres especiales para prevenir ataques de Cross-Site Scripting (XSS).
$nombre = htmlspecialchars($prod['nombre']);
$precio_num = (float)$prod['precio'];
$precio_form = number_format($precio_num, 0, ',', '.'); // Formatear el precio sin decimales y con separadores de miles
$moneda = htmlspecialchars($prod['moneda']);
$descripcion_larga = nl2br(htmlspecialchars($prod['descripcion_larga'])); // `nl2br` para mantener saltos de línea del texto de la DB
$imagen = htmlspecialchars($prod['imagen']);
// Si 'beneficios' es una cadena separada por saltos de línea, convertirla en un array
$beneficiosArr = explode("\n", $prod['beneficios']);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nombre; ?> - Detalles</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos generales para la página de detalles */
        body {
            font-family: 'TT Commons Pro Expanded', sans-serif; /* Asumiendo que esta fuente está disponible */
            background-color: #f5f0eb;
            color: #322203;
            margin: 0;
            padding: 0;
        }
        .contenedor-detalle {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        .galeria-imagenes {
            flex: 1;
            min-width: 300px;
        }
        .imagen-principal {
            width: 100%;
            cursor: zoom-in;
            transition: transform 0.3s;
            border-radius: 8px;
        }
        .imagen-principal.zoomed {
            transform: scale(1.8);
            cursor: zoom-out;
            position: relative;
            z-index: 100;
        }
        .info-producto {
            flex: 1;
            min-width: 300px;
        }
        .titulo-producto {
            font-family: 'Hatton', serif; /* Asumiendo que esta fuente está disponible */
            font-size: 2.5rem;
            color: #8c6e4a;
            margin-bottom: 10px;
        }
        .valoracion-detalle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            cursor: pointer;
        }
        .valoracion-detalle:hover {
            text-decoration: underline;
        }
        .estrellas-detalle {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .num-resenas {
            font-size: 0.9rem;
            color: #777;
            margin-left: 5px;
        }
        .precio-producto {
            font-size: 1.8rem;
            color: #8c6e4a;
            margin: 20px 0;
        }

        /* Contenedor para la cantidad y el botón de añadir al carrito */
        .acciones-compra {
            display: flex;
            align-items: center;
            gap: 15px; /* Espacio entre el control de cantidad y el botón */
            margin-top: 20px;
            flex-wrap: wrap; /* Permite que los elementos se envuelvan en pantallas pequeñas */
        }

        /* Estilos para el control de cantidad (botones + input) */
        .cantidad-control {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden; /* Para que los bordes del input y botones se unan */
        }
        .cantidad-control button {
            background-color: #e7e7e7;
            border: none; /* Quitamos el borde individual de los botones */
            color: #322203;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            transition: background-color 0.2s;
        }
        .cantidad-control button:hover {
            background-color: #d1d1d1;
        }
        .cantidad-control input {
            width: 50px; /* Ancho del input de cantidad */
            text-align: center;
            border: none; /* Quitamos el borde individual del input */
            padding: 10px 0;
            font-size: 1rem;
            -moz-appearance: textfield; /* Eliminar flechas en Firefox */
        }
        /* Eliminar flechas en Chrome, Safari, Edge */
        .cantidad-control input::-webkit-outer-spin-button,
        .cantidad-control input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }


        .boton-carrito {
            background-color: #8c6e4a;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            flex-grow: 1; /* Permite que el botón ocupe el espacio restante */
            min-width: 180px; /* Ancho mínimo para el botón */
        }
        .boton-carrito:hover {
            background-color: #6d5639;
        }
        .descripcion-producto {
            margin: 40px 0;
            line-height: 1.8;
        }
        .beneficios-producto {
            margin: 40px 0;
        }
        .beneficios-producto h3 {
            font-family: 'Hatton', serif; /* O la fuente que uses para títulos */
            color: #8c6e4a;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .beneficios-producto ul {
            list-style-type: none;
            padding-left: 0;
        }
        .beneficios-producto li {
            margin-bottom: 15px;
            position: relative;
            padding-left: 30px;
            font-size: 1.1rem;
        }
        .beneficios-producto li:before {
            content: "•";
            color: #8c6e4a;
            position: absolute;
            left: 0;
            font-size: 1.5rem;
        }
        .header-detalle {
            background-color: #e7d7c9;
            padding: 20px;
            text-align: center;
        }
        .logo-detalle {
            font-family: 'Hatton', serif;
            font-size: 2rem;
            color: #8c6e4a;
        }

        /* Estilos específicos para el modal de comentarios */
        .modal-comentarios {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posición fija en la pantalla */
            z-index: 1000; /* Por encima de otros elementos */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8); /* Fondo semi-transparente */
            overflow: auto; /* Permite scroll si el contenido es muy largo */
        }
        .contenido-modal {
            background-color: #f5f0eb;
            margin: 5% auto; /* Centrar vertical y horizontalmente */
            padding: 30px;
            width: 80%;
            max-width: 800px; /* Ancho máximo para el modal */
            border-radius: 10px;
            position: relative;
            max-height: 80vh; /* Altura máxima para permitir scroll interno */
            overflow-y: auto; /* Scroll interno si el contenido de comentarios es largo */
        }
        .cerrar-modal {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            color: #8c6e4a;
            cursor: pointer;
        }
        .titulo-comentarios {
            font-family: 'Hatton', serif;
            color: #8c6e4a;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .resumen-comentarios {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .puntuacion-total {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .comentario {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .encabezado-comentario {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .estrellas-comentario {
            color: #ffc107;
        }
        .fecha-comentario {
            color: #8c6e4a;
            font-size: 0.9rem;
        }
        .texto-comentario {
            line-height: 1.6;
        }
        .fuente-comentarios {
            font-style: italic;
            margin-top: 30px;
            text-align: right;
            color: #8c6e4a;
        }
        @media (max-width: 768px) {
  .contenedor-detalle {
    flex-direction: column;
    padding: 20px;
  }

  .galeria-imagenes,
  .info-producto {
    width: 100%;
  }

  .titulo-producto {
    font-size: 1.8rem;
    text-align: center;
  }

  .precio-producto {
    font-size: 1.5rem;
    text-align: center;
  }

  .valoracion-detalle {
    justify-content: center;
  }

  .boton-carrito {
    width: 100%;
    margin-top: 15px;
  }
}

    </style>
    

</head>
<body>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); // Incluye tu barra de navegación ?>

    <div class="contenedor-detalle">
        <div class="galeria-imagenes">
            <img id="imagenZoom" src="imagenes/<?php echo $imagen; ?>" alt="<?php echo $nombre; ?>" class="imagen-principal">
        </div>

        <div class="info-producto">
            <h1 class="titulo-producto"><?php echo $nombre; ?></h1>
            <div class="valoracion-detalle" id="btnResenas">
                <div class="estrellas-detalle">
                    <?php
                    // Mostrar estrellas según el rating promedio del producto
                    // Redondear el rating a un número entero para las estrellas
                    $rating_entero = floor($rating); // Parte entera de la calificación
                    $decimales = $rating - $rating_entero; // Parte decimal

                    for ($i = 0; $i < $rating_entero; $i++) {
                        echo '★'; // Estrella rellena
                    }
                    if ($decimales >= 0.5) {
                        echo '½'; // Media estrella si hay decimales >= 0.5
                        $rating_entero++; // Contar la media estrella como parte de las estrellas "rellenas" para el siguiente bucle
                    }
                    for ($j = $rating_entero; $j < 5; $j++) { // Rellenar con estrellas vacías hasta 5
                        echo '☆'; // Estrella vacía
                    }
                    ?>
                </div>
                <span class="num-resenas">(<?php echo $num_resenas; ?> reseñas)</span>
            </div>

            <div class="precio-producto">$<?php echo $precio_form . ' ' . $moneda; ?></div>

            <div class="acciones-compra">
                <div class="cantidad-control">
                    <button id="disminuirCantidad">-</button>
                    <input type="number" id="cantidadInput" value="1" min="1">
                    <button id="aumentarCantidad">+</button>
                </div>
                <button class="boton-carrito" data-id="<?php echo $id; ?>">
                    <i class="fas fa-shopping-cart"></i> Agregar al carrito
                </button>
            </div>


            <div class="descripcion-producto">
                <?php echo $descripcion_larga; ?>
            </div>

            <div class="beneficios-producto">
                <h3>BENEFICIOS</h3>
                <ul>
                    <?php
                        // Mostrar los beneficios del producto como lista
                        foreach ($beneficiosArr as $item) {
                            $li = trim($item); // Limpiar espacios en blanco
                            if ($li !== '') { // Asegurarse de que no esté vacío
                                echo '<li>' . htmlspecialchars($li) . '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div id="modalComentarios" class="modal-comentarios">
        <div class="contenido-modal">
            <span class="cerrar-modal">&times;</span>
            <h2 class="titulo-comentarios">COMENTARIOS</h2>
            <div class="resumen-comentarios">
                <div class="puntuacion-total">
                    <?php echo $num_resenas; ?> OPINIONES<br>
                    <?php echo number_format($rating, 2); ?> </div>
            </div>

            <?php if (!empty($reseñas)): ?>
                <?php foreach ($reseñas as $resena): ?>
                    <div class="comentario">
                        <div class="encabezado-comentario">
                            <div class="estrellas-comentario">
                                <?php
                                // Mostrar estrellas de la reseña individual
                                $puntuacion_resena = (int)$resena['puntuacion'];
                                for ($i = 0; $i < $puntuacion_resena; $i++) {
                                    echo '★';
                                }
                                for ($i = $puntuacion_resena; $i < 5; $i++) {
                                    echo '☆';
                                }
                                ?>
                            </div>
                            <div class="fecha-comentario"><?php echo htmlspecialchars($resena['fecha']); ?></div>
                        </div>
                        <div class="texto-comentario">
                            "<?php echo nl2br(htmlspecialchars($resena['comentario'])); ?>"
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="comentario">
                    <div class="encabezado-comentario">
                        <div class="estrellas-comentario">☆☆☆☆☆</div>
                        <div class="fecha-comentario"></div>
                    </div>
                    <div class="texto-comentario">
                        <em>No hay comentarios disponibles para este producto aún.</em>
                    </div>
                </div>
            <?php endif; ?>

            <div style="text-align: center; margin: 30px 0;">
    <a href="añadir_resena.php?producto_id=<?= $id ?>" 
       class="boton-secundario" 
       style="display: inline-block; padding: 12px 25px; border: 2px solid #8c6e4a; color: #8c6e4a; border-radius: 6px; text-decoration: none; font-weight: bold;">
       Añadir reseña
    </a>
</div>

        </div>
    </div>

    <script>
        // Funcionalidad de zoom para la imagen principal del producto
        const imagenZoom = document.getElementById('imagenZoom');
        imagenZoom.addEventListener('click', function () {
            this.classList.toggle('zoomed');
        });
        // Cierra el zoom al hacer clic fuera de la imagen
        document.addEventListener('click', function (e) {
            if (imagenZoom.classList.contains('zoomed') && !imagenZoom.contains(e.target)) {
                imagenZoom.classList.remove('zoomed');
            }
        });

        // --- Funcionalidad del contador de cantidad ---
        const cantidadInput = document.getElementById('cantidadInput');
        const disminuirBtn = document.getElementById('disminuirCantidad');
        const aumentarBtn = document.getElementById('aumentarCantidad');

        disminuirBtn.addEventListener('click', () => {
            let cantidad = parseInt(cantidadInput.value);
            if (cantidad > 1) {
                cantidadInput.value = cantidad - 1;
            }
        });

        aumentarBtn.addEventListener('click', () => {
            let cantidad = parseInt(cantidadInput.value);
            // Puedes añadir una validación de stock aquí si es necesario
            cantidadInput.value = cantidad + 1;
        });

        // Asegurarse de que el input siempre tenga un número válido (min 1)
        cantidadInput.addEventListener('change', () => {
            let cantidad = parseInt(cantidadInput.value);
            if (isNaN(cantidad) || cantidad < 1) {
                cantidadInput.value = 1;
            }
        });
        // --- Fin Funcionalidad del contador de cantidad ---


        // Funcionalidad para agregar producto al carrito (usando LocalStorage)
        document.querySelector('.boton-carrito').addEventListener('click', function () {
            const id = this.dataset.id; // Obtener el ID del producto
            const cantidad = parseInt(document.getElementById('cantidadInput').value); // Obtener la cantidad seleccionada

            const producto = {
                id: id, // Incluir el ID del producto
                nombre: document.querySelector('.titulo-producto').textContent,
                // Asegúrate de parsear el precio si lo necesitas como número para el carrito
                // Aquí se toma el texto formateado, lo limpiamos y convertimos a número
                precio: parseFloat(document.querySelector('.precio-producto').textContent.replace('$', '').replace(' COP', '').replace('.', '').replace(',', '.')), // Ajustar según tu formato de precio
                imagen: imagenZoom.src, // Asegúrate de que esta URL sea la correcta para el carrito
                cantidad: cantidad // Añadir la cantidad seleccionada
            };

            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

            // Verificar si el producto ya está en el carrito por su ID
            const productoExistente = carrito.find(item => item.id === producto.id);

            if (productoExistente) {
                productoExistente.cantidad += cantidad; // Si existe, suma la nueva cantidad
                alert(`${producto.nombre}: Cantidad actualizada a ${productoExistente.cantidad} en el carrito.`);
            } else {
                carrito.push(producto); // Si no existe, añade el nuevo producto
                alert(`${producto.nombre} (x${cantidad}) fue agregado al carrito.`);
            }
            
            localStorage.setItem('carrito', JSON.stringify(carrito));
            // Puedes añadir una función para actualizar la UI del carrito aquí
        });

        // Manejo de la apertura y cierre del modal de comentarios
        const btnResenas = document.getElementById('btnResenas');
        const modalComentarios = document.getElementById('modalComentarios');
        const cerrarModal = document.querySelector('.cerrar-modal');

        btnResenas.addEventListener('click', () => modalComentarios.style.display = 'block');
        cerrarModal.addEventListener('click', () => modalComentarios.style.display = 'none');

        // Cierra el modal si se hace clic fuera del contenido del modal
        window.addEventListener('click', function (event) {
            if (event.target == modalComentarios) {
                modalComentarios.style.display = 'none';
            }
        });
    </script>
    <?php include(__DIR__ . "/Pie_de_Pag.php"); // Incluye tu pie de página ?>
    <script src="buscador.js"></script>
</body>
</html>