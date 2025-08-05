<?php
include "db/conexion.php";

// Función auxiliar para construir la parte JOIN de la consulta
function getResenaJoinClause() {
    return " LEFT JOIN reseñas r ON p.id = r.producto_id ";
}

// Función auxiliar para construir la parte SELECT de la consulta
// Se seleccionan todos los campos de productos (p.*)
// y luego se calculan el promedio de puntuación y el conteo de reseñas.
function getResenaSelectClause() {
    return " , AVG(r.puntuacion) AS rating_promedio, COUNT(r.id) AS num_resenas ";
}

// SQL base para obtener productos con su promedio de rating y conteo de reseñas
$sql_base = "SELECT p.* " . getResenaSelectClause() . " FROM productos p " . getResenaJoinClause();
$group_by_clause = " GROUP BY p.id "; // Agrupamos por ID de producto

// Lógica para ordenar los productos
$order_by_clause = " ORDER BY p.creado_en DESC"; // Orden por defecto por fecha de creación

// Si se recibe un parámetro de ordenamiento, se puede ajustar la cláusula ORDER BY
// (Aunque tu JS ya maneja el ordenamiento en el cliente, si quieres que el orden inicial venga del servidor, lo harías aquí)
// Por ejemplo, para "mejor valorados" en el servidor:
// if (!empty($_GET['orden']) && $_GET['orden'] == 'mejor-valorados') {
//     $order_by_clause = " ORDER BY rating_promedio DESC, p.creado_en DESC";
// }


if (!empty($_GET['busqueda'])) {
    $termino = '%' . strtolower($_GET['busqueda']) . '%';
    $sql = $sql_base . " WHERE LOWER(p.nombre) LIKE :termino OR LOWER(p.descripcion) LIKE :termino " . $group_by_clause . $order_by_clause;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termino', $termino, PDO::PARAM_STR);
    $stmt->execute();
} elseif (!empty($_GET['categoria']) && strtolower($_GET['categoria']) !== 'todos') {
    $categoriaFiltro = strtolower($_GET['categoria']);
    $sql = $sql_base . " WHERE LOWER(p.categoria) = :categoriaFiltro " . $group_by_clause . $order_by_clause;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':categoriaFiltro', $categoriaFiltro, PDO::PARAM_STR);
    $stmt->execute();
} else {
    $sql  = $sql_base . $group_by_clause . $order_by_clause;
    $stmt = $pdo->query($sql);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>PRODUCTOS</title>
  <link rel="stylesheet" href="estilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
  <style>
    @font-face {
      font-family: 'Hatton';
      src: url('fonts/Hatton.otf') format('opentype');
      font-weight: normal;
      font-style: normal;
    }
    .filtros-productos {
      display: flex;
      justify-content: center;
      gap: 15px;
      padding: 20px;
      background-color: #e7d7c9;
      flex-wrap: wrap;
    }
    .filtros-productos select {
      padding: 8px 15px;
      border: 1px solid #d1c0b0;
      border-radius: 20px;
      font-family: 'TT Commons Pro Expanded', sans-serif;
      background-color: #f5f0eb;
    }
    .badge-stock {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #8c6e4a;
      color: white;
      padding: 3px 10px;
      border-radius: 15px;
      font-size: 12px;
      text-transform: uppercase;
    }
    .valoracion {
      margin: 10px 0;
      color: #322203;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
    }
    .estrellas {
      color: #ffc107;
      font-size: 0.9rem;
    }
    .num-resenas {
      font-size: 0.8rem;
      color: #777;
    }
    /* Estilos para el contenedor de cantidad */
    .cantidad-control {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      margin-bottom: 10px; /* Espacio antes del botón de añadir al carrito */
    }
    .cantidad-control button {
      background-color: #e7e7e7;
      border: 1px solid #ccc;
      color: #322203;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      line-height: 1; /* Para que el texto sea más compacto */
    }
    .cantidad-control button:hover {
      background-color: #d1d1d1;
    }
    .cantidad-control input {
      width: 40px; /* Ancho del input de cantidad */
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 5px 0;
      font-size: 1rem;
    }

    .boton-carrito-peq {
      display: inline-block;
      background-color: #8c6e4a; /* Color principal del botón */
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.9rem;
      margin-top: 5px; /* Espacio después del control de cantidad */
      transition: background-color 0.2s;
      width: 100%; /* Para que ocupe todo el ancho disponible */
    }
    .boton-carrito-peq:hover {
      background-color: #6d5639; /* Color más oscuro al pasar el ratón */
    }
    .boton-secundario {
      display: inline-block;
      background-color: transparent;
      color: #322203;
      border: 1px solid #322203;
      padding: 0.5rem 1.5rem;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 4px;
      transition: all 0.3s ease;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .boton-secundario:hover {
      background-color: #322203;
      color: #ebebeb;
    }
    .tarjeta {
      position: relative;
      display: flex; /* Usamos flexbox para centrar contenido verticalmente */
      flex-direction: column; /* Apilar elementos verticalmente */
      align-items: center; /* Centrar horizontalmente */
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 15px;
      background-color: #fff;
      margin: 10px;
      width: 240px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .tarjeta:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .tarjeta img {
      max-width: 100%;
      height: auto;
      margin-bottom: 10px;
      border-radius: 4px;
    }
    .tarjeta h2 {
      font-family: 'Hatton', serif;
      font-size: 1.2rem;
      color: #8c6e4a;
      margin: 10px 0 5px;
    }
    .precio {
      margin: 10px 0;
      font-size: 1.1em;
      font-weight: bold;
      color: #322203;
    }
  </style>
</head>
<body>
  <header class="franja-superior">
    <div style="margin-top: 20px;" class="logo">PRODUCTOS</div>
  </header>
  <main class="contenido-principal">
    <div class="filtros-productos">
      <select id="filtro-tipo">
        <option value="todos">Todos los tipos</option>
        <option value="rizado">Para cabello rizado</option>
        <option value="liso">Para cabello liso</option>
        <option value="ondulado">Para cabello ondulado</option>
      </select>
      <select id="filtro-categoria">
        <option value="todos">Todas las categorías</option>
        <option value="shampoo">Shampoo</option>
        <option value="acondicionador">Acondicionador</option>
        <option value="tratamiento">Tratamiento</option>
        <option value="serum">Sérum</option>
        <option value="combo">Combo</option>
        <option value="accesorios">Accesorios</option>
      </select>
      <select id="orden-productos">
        <option value="recientes">Más recientes</option>
        <option value="precio-asc">Precio: menor a mayor</option>
        <option value="precio-desc">Precio: mayor a menor</option>
        <option value="mejor-valorados">Mejor valorados</option>
      </select>
    </div>
    <section class="galeria">
      <?php
      foreach ($stmt as $fila) {
          $id           = $fila['id'];
          $nombre       = htmlspecialchars($fila['nombre']);
          $desc         = htmlspecialchars($fila['descripcion']);
          $precio       = (float)$fila['precio'];
          $precio_cop   = number_format($precio, 0, ',', '.');
          $imagen       = htmlspecialchars($fila['imagen']);
          $stock        = isset($fila['stock']) ? (int)$fila['stock'] : 0;
          $badge        = ($stock > 0) ? "En stock" : "Agotado";
          
          // Ahora $fila['rating_promedio'] y $fila['num_resenas'] vendrán de la consulta SQL
          $valoracion   = isset($fila['rating_promedio']) ? (float)$fila['rating_promedio'] : 0;
          $num_resenas  = isset($fila['num_resenas']) ? (int)$fila['num_resenas'] : 0;
          
          $tipo         = isset($fila['tipo_cabello']) ? htmlspecialchars($fila['tipo_cabello']) : 'todos';
          $categoria    = isset($fila['categoria'])     ? htmlspecialchars($fila['categoria'])     : 'todos';
          $fecha        = isset($fila['creado_en'])     ? date("Ymd", strtotime($fila['creado_en'])) : '0';

          echo '<div class="tarjeta" '
               .  'data-id="' . $id . '" ' // Añadir data-id a la tarjeta para facilitar JS
               .  'data-tipo="' . $tipo . '" '
               .  'data-categoria="' . $categoria . '" '
               .  'data-precio="' . intval($precio) . '" '
               .  'data-valoracion="' . $valoracion . '" '
               .  'data-fecha="' . $fecha . '">'

               .  '<div class="badge-stock">' . $badge . '</div>'
               .  '<img src="imagenes/' . $imagen . '" alt="' . $nombre . '">'
               .  '<h2>' . $nombre . '</h2>'
               .  '<p class="precio"><strong>$' . $precio_cop . ' COP</strong></p>'

               .  '<div class="valoracion">';
          // Mostrar estrellas según rating
          $entero = floor($valoracion);
          $decimales = $valoracion - $entero;
          echo '<div class="estrellas">'; // Contenedor para las estrellas
          for ($i = 0; $i < $entero; $i++) {
              echo '★'; // Estrella rellena
          }
          if ($decimales >= 0.5) {
              echo '½'; // Media estrella
              $entero++; // Incrementa para que el bucle de estrellas vacías rellene correctamente
          }
          for ($j = $entero; $j < 5; $j++) {
              echo '☆'; // Estrella vacía
          }
          echo '</div>'; // Cierra div.estrellas
          echo      '<span class="num-resenas">(' . $num_resenas . ')</span>'
               .  '</div>';

          // AQUI ES DONDE AÑADIMOS EL CONTADOR DE CANTIDAD
          echo '<div class="cantidad-control">'
               . '<button class="disminuir-cantidad" data-id="' . $id . '">-</button>'
               . '<input type="number" class="cantidad-input" value="1" min="1" data-id="' . $id . '">'
               . '<button class="aumentar-cantidad" data-id="' . $id . '">+</button>'
               . '</div>';

          echo      '<button class="boton-carrito-peq" data-id="' . $id . '">' // Añadimos data-id al botón
               .    '<i class="fas fa-shopping-cart"></i> Agregar al carrito'
               .  '</button>'

               .  '<a href="detalle.php?id=' . $id . '" class="boton-secundario ver-detalles">'
               .    'Ver detalles'
               .  '</a>'

               . '</div>';
      }
      ?>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Event Listeners para los filtros y el ordenamiento
      document.getElementById('filtro-tipo').addEventListener('change', filtrarProductos);
      document.getElementById('filtro-categoria').addEventListener('change', filtrarProductos);
      document.getElementById('orden-productos').addEventListener('change', filtrarProductos);

      // Aplicar filtro de categoría si viene de la URL
      const parametrosURL = new URLSearchParams(window.location.search);
      const categoriaURL = parametrosURL.get('categoria');
      if (categoriaURL) {
        document.getElementById('filtro-categoria').value = categoriaURL.toLowerCase();
      }
      filtrarProductos(); // Llama a la función de filtro al cargar la página

      // Funcionalidad de los botones de cantidad
      document.querySelectorAll('.aumentar-cantidad').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const input = document.querySelector(`.cantidad-input[data-id="${id}"]`);
          // Asegurarse de que no excede el stock si tienes esa lógica
          input.value = parseInt(input.value) + 1;
        });
      });

      document.querySelectorAll('.disminuir-cantidad').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id;
          const input = document.querySelector(`.cantidad-input[data-id="${id}"]`);
          const currentValue = parseInt(input.value);
          if (currentValue > 1) { // Asegura que la cantidad no baje de 1
            input.value = currentValue - 1;
          }
        });
      });

      // Manejar el evento clic en el botón "Agregar al carrito"
      document.querySelectorAll('.boton-carrito-peq').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.dataset.id; // Obtenemos el ID del producto
          const tarjeta = this.closest('.tarjeta'); // Elemento padre de la tarjeta
          const cantidadInput = tarjeta.querySelector(`.cantidad-input[data-id="${id}"]`);
          const cantidad = parseInt(cantidadInput.value); // Cantidad seleccionada

          const producto = {
            id: id, // Es importante pasar el ID del producto
            nombre: tarjeta.querySelector('h2').textContent,
            // Limpiamos y parseamos el precio. Asumo formato $1.000.000 COP
            precio: parseFloat(tarjeta.querySelector('.precio strong').textContent.replace('$', '').replace(' COP', '').replace('.', '').replace(',', '.')), 
            imagen: tarjeta.querySelector('img').getAttribute('src'),
            cantidad: cantidad // Añadimos la cantidad seleccionada
          };

          let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

          // Verificar si el producto ya está en el carrito
          const productoExistente = carrito.find(item => item.id === producto.id);

          if (productoExistente) {
            productoExistente.cantidad += cantidad; // Si existe, suma la cantidad
            alert(`${producto.nombre}: Cantidad actualizada a ${productoExistente.cantidad} en el carrito.`);
          } else {
            carrito.push(producto); // Si no existe, añade el nuevo producto
            alert(`${producto.nombre} (x${cantidad}) fue agregado al carrito.`);
          }

          localStorage.setItem('carrito', JSON.stringify(carrito));
          // Puedes añadir aquí una llamada para actualizar la interfaz del carrito (ej. un contador en el ícono del carrito)
        });
      });
    });

    // Función de filtrado (sin cambios significativos en la lógica JS)
    function filtrarProductos() {
      const tipoSeleccionado    = document.getElementById('filtro-tipo').value;
      const categoriaSeleccionada = document.getElementById('filtro-categoria').value;
      const ordenSeleccionado   = document.getElementById('orden-productos').value;
      const productos = Array.from(document.querySelectorAll('.tarjeta'));

      const productosFiltrados = productos.filter(producto => {
        const tipoProducto    = producto.getAttribute('data-tipo');
        const categoriaProducto = producto.getAttribute('data-categoria');
        return (tipoSeleccionado === 'todos' || tipoProducto === tipoSeleccionado || tipoProducto === 'todos')
             && (categoriaSeleccionada === 'todos' || categoriaProducto === categoriaSeleccionada);
      });

      productosFiltrados.sort((a, b) => {
        const precioA     = parseInt(a.getAttribute('data-precio'));
        const precioB     = parseInt(b.getAttribute('data-precio'));
        const valoracionA = parseFloat(a.getAttribute('data-valoracion'));
        const valoracionB = parseFloat(b.getAttribute('data-valoracion'));
        const fechaA      = parseInt(a.getAttribute('data-fecha'));
        const fechaB      = parseInt(b.getAttribute('data-fecha'));
        switch (ordenSeleccionado) {
          case 'precio-asc':      return precioA - precioB;
          case 'precio-desc':     return precioB - precioA;
          case 'mejor-valorados': return valoracionB - valoracionA;
          default:                return fechaB - fechaA; // Por defecto o 'recientes'
        }
      });

      const galeria = document.querySelector('.galeria');
      productos.forEach(p => p.style.display = 'none'); // Oculta todos
      productosFiltrados.forEach(producto => {
        producto.style.display = 'flex'; // Muestra los filtrados (flex porque la tarjeta es flex)
        galeria.appendChild(producto); // Vuelve a adjuntar para el orden correcto
      });
    }

    // Funcionalidad de búsqueda si se usa un input separado para ello
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const busqueda = urlParams.get('busqueda');
      if (busqueda) {
        const busquedaInput = document.getElementById('busqueda-input'); // Asegúrate de que este ID exista en tu barra de navegación
        if (busquedaInput) {
          busquedaInput.value = busqueda;
        }
        // Aplicar la búsqueda solo a las tarjetas que ya están visibles (después de los filtros iniciales)
        document.querySelectorAll('.tarjeta[style*="display: flex"]').forEach(tarjeta => { // Busca las tarjetas visibles
          const nombre = tarjeta.querySelector('h2').textContent.toLowerCase();
          tarjeta.style.display = nombre.includes(busqueda.toLowerCase()) ? 'flex' : 'none';
        });
      }
    });
  </script>

  <script src="buscador.js"></script>
</body>

</html>