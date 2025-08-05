<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Natural Beauty</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>

    <style>
        /* Definición de la paleta de colores basada en detalle.php y productos.php */
        :root {
            --color-background-main: #f5f0eb; /* Fondo principal (cuerpo) */
            --color-text-main: #322203; /* Color de texto principal */
            --color-primary-brand: #8c6e4a; /* Color principal de la marca (botones, títulos, etc.) */
            --color-primary-brand-hover: #6d5639; /* Color al pasar el ratón por botones de marca */
            --color-light-bg: #ffffff; /* Fondo para tarjetas, contenedores internos (blanco) */
            --color-light-border: #eee; /* Bordes sutiles */
            --color-accent-stars: #ffc107; /* Color para las estrellas de calificación */
            --color-secondary-bg: #e7d7c9; /* Color secundario de fondo (para headers, etc.) */
            --color-error: #322203; /* Color para acciones de eliminar (rojo) */
            --color-error-hover: #322203; /* Rojo más oscuro al pasar el ratón */
            --color-text-light: #777777; /* Texto secundario o menos prominente */
        }

        body {
            font-family: 'TT Commons Pro Expanded', sans-serif; /* Si esta fuente está disponible */
            background-color: var(--color-background-main);
            color: var(--color-text-main);
            margin: 0;
            padding: 0;
        }

        /* Tu barra de navegación debería usar --color-secondary-bg o --color-primary-brand */
        /* nav {
            background-color: var(--color-secondary-bg);
            padding: 20px 0;
            text-align: center;
        } */

        .contenido-carrito {
            max-width: 1200px;
            margin: 50px auto; /* Aumentado margen superior/inferior */
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px; /* Aumentado el espacio entre columnas */
        }

        .items-carrito {
            flex: 2; /* Ocupa más espacio */
            min-width: 320px; /* Ajuste para evitar desbordamientos en móviles */
            background: var(--color-light-bg);
            border-radius: 8px; /* Bordes más suaves */
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); /* Sombra más pronunciada */
            padding: 30px; /* Más padding */
            min-height: 400px; /* Altura mínima para el contenedor de ítems */
        }

        .resumen-compra {
            flex: 1; /* Ocupa menos espacio */
            min-width: 280px;
            background: var(--color-secondary-bg); /* Fondo del resumen */
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 30px;
            height: fit-content; /* Se ajusta al contenido */
        }

        h1 {
            font-family: 'Hatton', serif; /* Si esta fuente está disponible */
            color: var(--color-primary-brand);
            font-size: 2.5rem; /* Tamaño de título grande */
            margin-bottom: 30px;
            text-align: center;
        }

        .resumen-compra h2 {
            font-family: 'Hatton', serif;
            color: var(--color-text-main); /* Cambiado a color de texto principal */
            font-size: 1.8rem;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--color-primary-brand); /* Línea divisoria más prominente */
            text-align: center;
        }

        .resumen-detalle p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: var(--color-text-main);
        }

        .resumen-detalle .total {
            font-weight: bold;
            font-size: 1.4rem; /* Total más grande */
            color: var(--color-primary-brand); /* Color de la marca para el total */
            margin: 30px 0 15px 0;
            padding-top: 20px;
            border-top: 1px solid var(--color-primary-brand);
        }

        .boton-comprar {
            background-color: var(--color-primary-brand);
            color: white;
            border: none;
            padding: 18px 25px; /* Botón más grande */
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            margin-bottom: 15px;
        }

        .boton-comprar:hover {
            background-color: var(--color-primary-brand-hover);
        }

        .boton-vaciar {
            background-color: var(--color-error); /* Rojo para vaciar */
            color: white;
            border: none;
            padding: 12px 20px; /* Tamaño adecuado para botón de vaciar */
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .boton-vaciar:hover {
            background-color: var(--color-error-hover);
        }

        /* Nuevo estilo para el botón "Seguir Comprando" */
        .boton-seguir-comprando {
            background-color: #6b5c4b; /* Un verde amigable */
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 15px; /* Espacio superior */
        }

        .boton-seguir-comprando:hover {
            background-color: #6b5c4b; /* Verde más oscuro al pasar el ratón */
        }


        .metodos-pago {
            display: flex;
            justify-content: center;
            gap: 20px; /* Más espacio entre iconos */
            margin-top: 25px;
        }

        .metodos-pago img {
            height: 35px; /* Iconos un poco más grandes */
            width: auto;
            opacity: 0.8; /* Ligeramente transparentes */
        }

        .carrito-vacio {
            text-align: center;
            padding: 60px; /* Más padding para centrar */
            color: var(--color-text-light);
            font-size: 1.1rem;
        }

        .carrito-vacio h3 {
            margin-bottom: 25px;
            color: var(--color-primary-brand); /* Título de vacío con color de marca */
            font-family: 'Hatton', serif;
            font-size: 1.8rem;
        }

        /* Modificado para ser un botón también si está vacío */
        .carrito-vacio .continuar-compra {
            color: white; /* Color de texto blanco para el botón */
            background-color: var(--color-primary-brand); /* Fondo del botón de marca */
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 30px;
            border: 2px solid var(--color-primary-brand); /* Borde que coincide con el fondo */
            padding: 12px 25px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .carrito-vacio .continuar-compra:hover {
            background-color: var(--color-primary-brand-hover);
            border-color: var(--color-primary-brand-hover);
        }

        /* Estilos para cada ítem del carrito (similar a producto individual) */
        .item-carrito {
            display: flex;
            align-items: center;
            gap: 25px; /* Más espacio */
            padding: 20px 0;
            border-bottom: 1px solid var(--color-light-border);
        }
        .item-carrito:last-child {
            border-bottom: none;
        }
        .item-carrito img {
            width: 100px; /* Imágenes más grandes */
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
        }
        .detalles-item {
            flex-grow: 1;
        }
        .detalles-item h3 {
            margin: 0 0 8px 0;
            font-size: 1.2rem;
            color: var(--color-text-main);
        }
        .detalles-item p {
            margin: 0;
            font-size: 1rem;
            color: var(--color-text-light);
        }
        .precio-item {
            font-weight: bold;
            color: var(--color-primary-brand);
            white-space: nowrap;
            margin-top: 5px;
        }
        .cantidad-control {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 10px;
            max-width: 130px; /* Un poco más ancho */
        }
        .cantidad-control button {
            background-color: #f0f0f0;
            border: none;
            padding: 10px 15px; /* Más padding */
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            color: var(--color-text-main);
        }
        .cantidad-control button:hover {
            background-color: #e0e0e0;
        }
        .cantidad-control input {
            width: 40px;
            text-align: center;
            border: none;
            padding: 10px 0;
            font-size: 1.1rem;
            -moz-appearance: textfield;
        }
        .cantidad-control input::-webkit-outer-spin-button,
        .cantidad-control input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .eliminar-item {
            background: none;
            border: none;
            color: var(--color-error);
            font-size: 1.5rem; /* Icono más grande */
            cursor: pointer;
            margin-left: 20px;
            transition: color 0.2s;
        }
        .eliminar-item:hover {
            color: var(--color-error-hover);
        }

        @media (max-width: 768px) {
            .contenido-carrito {
                flex-direction: column;
                margin: 30px auto;
                padding: 15px;
                gap: 30px;
            }
            .items-carrito, .resumen-compra {
                width: calc(100% - 30px); /* Ajuste por el padding */
                padding: 20px;
            }
            h1 {
                font-size: 2rem;
            }
            .item-carrito {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .item-carrito img {
                width: 80px;
                height: 80px;
            }
            .detalles-item {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .eliminar-item {
                margin-left: 0;
                margin-top: 10px;
            }
            .cantidad-control {
                margin: 10px auto; /* Centrar control de cantidad */
            }
        }
    </style>
</head>
<body>
    <main class="contenido-carrito">
        <section class="items-carrito">
            <h1>Tu Carrito</h1>
            <div id="carrito-contenido">
                <div class="carrito-vacio" id="mensaje-carrito-vacio">
                    <h3>Tu carrito está vacío</h3>
                    <p>Parece que aún no has agregado ningún producto.</p>
                    <a href="productos.php" class="continuar-compra">Continuar comprando</a>
                </div>
            </div>
            <button class="boton-seguir-comprando" id="btn-seguir-comprando-inferior">
                <i class="fas fa-arrow-left"></i> Seguir Comprando
            </button>
        </section>

        <aside class="resumen-compra">
            <h2>Resumen de Compra</h2>
            <div class="resumen-detalle">
                <p>Subtotal de productos: <span id="subtotal-productos">$0 COP</span></p>
                <p>Envío estimado: <span id="envio-estimado">$0 COP</span></p>
                <p>Impuestos (IVA): <span id="impuestos">$0 COP</span></p>
                <p class="total">Total a pagar: <span id="total-pagar">$0 COP</span></p>
                <p>Total de unidades: <span id="total-cantidad-productos">0 unidades</span></p>
            </div> 
             <form action="proceso_pago.php" method="GET"> 
                 <button type="submit" class="boton-comprar">Proceder al pago</button>
             </form>

             <button class="boton-vaciar" id="vaciar-carrito">Vaciar carrito</button>
             <button class="boton-seguir-comprando" id="btn-seguir-comprando-resumen">
                 <i class="fas fa-arrow-left"></i> Seguir Comprando
             </button>

            <div class="metodos-pago">
                <img src="imagenes/visa.png" alt="Visa">
                <img src="imagenes/mastercard.png" alt="Mastercard">
                <img src="imagenes/american-express.png" alt="American Express">
                </div>
        </aside>
    </main>

    <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
    <script src="buscador.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carritoContenido = document.getElementById('carrito-contenido');
            const mensajeCarritoVacio = document.getElementById('mensaje-carrito-vacio');
            const subtotalProductosSpan = document.getElementById('subtotal-productos');
            const envioEstimadoSpan = document.getElementById('envio-estimado');
            const impuestosSpan = document.getElementById('impuestos');
            const totalPagarSpan = document.getElementById('total-pagar');
            const totalCantidadProductosSpan = document.getElementById('total-cantidad-productos');
            const vaciarCarritoBtn = document.getElementById('vaciar-carrito');
            const btnSeguirComprandoInferior = document.getElementById('btn-seguir-comprando-inferior');
            const btnSeguirComprandoResumen = document.getElementById('btn-seguir-comprando-resumen');

            const TASA_IVA = 0.19; // Ejemplo: 19% de IVA para Colombia
            const COSTO_ENVIO = 15000; // Ejemplo: 15,000 COP por envío

            // Función para formatear el precio a formato colombiano con "COP"
            function formatPrice(price) {
                return new Intl.NumberFormat('es-CO', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(price);
            }

            // Función para cargar y renderizar el carrito
            function cargarCarrito() {
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                carritoContenido.innerHTML = ''; // Limpiar el contenido existente del carrito

                let subtotalProductos = 0;
                let totalUnidades = 0;

                if (carrito.length === 0) {
                    mensajeCarritoVacio.style.display = 'block'; // Mostrar mensaje de vacío
                    vaciarCarritoBtn.style.display = 'none'; // Ocultar botón de vaciar
                    // Ocultar el botón "Seguir Comprando" del resumen si el carrito está vacío
                    // Se asume que el botón de "Continuar Comprando" en el mensaje de vacío ya redirige a productos.php
                    btnSeguirComprandoResumen.style.display = 'none'; 
                    btnSeguirComprandoInferior.style.display = 'none'; // Ocultar el botón inferior si está vacío

                    // Resetear todos los valores del resumen
                    subtotalProductosSpan.textContent = '$0 COP';
                    envioEstimadoSpan.textContent = '$0 COP';
                    impuestosSpan.textContent = '$0 COP';
                    totalPagarSpan.textContent = '$0 COP';
                    totalCantidadProductosSpan.textContent = '0 unidades';
                    return; // Salir de la función si no hay productos
                } else {
                    mensajeCarritoVacio.style.display = 'none'; // Ocultar mensaje de vacío
                    vaciarCarritoBtn.style.display = 'block'; // Mostrar botón de vaciar
                    btnSeguirComprandoResumen.style.display = 'block'; // Mostrar el botón "Seguir Comprando"
                    btnSeguirComprandoInferior.style.display = 'block'; // Mostrar el botón inferior
                }

                const ul = document.createElement('ul');
                ul.style.listStyle = 'none';
                ul.style.padding = '0';

                carrito.forEach(item => {
                    const itemPrecioNumerico = parseFloat(String(item.precio).replace('$', '').replace(' COP', '').replace(/\./g, '').replace(',', '.'));
                    
                    if (isNaN(itemPrecioNumerico)) {
                        console.error('Error: El precio del producto no es un número válido:', item.precio, 'para el producto:', item.nombre);
                        return;
                    }

                    const itemSubtotal = itemPrecioNumerico * item.cantidad;

                    subtotalProductos += itemSubtotal;
                    totalUnidades += item.cantidad;

                    const li = document.createElement('li');
                    li.classList.add('item-carrito');
                    li.setAttribute('data-id', item.id);

                    li.innerHTML = `
                        <img src="${item.imagen}" alt="${item.nombre}">
                        <div class="detalles-item">
                            <h3>${item.nombre}</h3>
                            <p class="precio-item">Precio Unitario: $${formatPrice(itemPrecioNumerico)} COP</p>
                            <div class="cantidad-control">
                                <button class="disminuir-cantidad" data-id="${item.id}">-</button>
                                <input type="number" class="cantidad-input" value="${item.cantidad}" min="1" data-id="${item.id}">
                                <button class="aumentar-cantidad" data-id="${item.id}">+</button>
                            </div>
                            <p class="precio-item">Subtotal del ítem: $${formatPrice(itemSubtotal)} COP</p>
                        </div>
                        <button class="eliminar-item" data-id="${item.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    ul.appendChild(li);
                });
                carritoContenido.appendChild(ul);

                const impuestosCalculados = subtotalProductos * TASA_IVA;
                const envioCalculado = subtotalProductos > 0 ? COSTO_ENVIO : 0; 
                const totalAPagar = subtotalProductos + impuestosCalculados + envioCalculado;

                subtotalProductosSpan.textContent = `$${formatPrice(subtotalProductos)} COP`;
                envioEstimadoSpan.textContent = `$${formatPrice(envioCalculado)} COP`;
                impuestosSpan.textContent = `$${formatPrice(impuestosCalculados)} COP`;
                totalPagarSpan.textContent = `$${formatPrice(totalAPagar)} COP`;
                totalCantidadProductosSpan.textContent = `${totalUnidades} unidades`;

                addEventListenersToCartItems();
            }

            function addEventListenersToCartItems() {
                document.querySelectorAll('.aumentar-cantidad').forEach(button => {
                    button.removeEventListener('click', handleQuantityChange);
                    button.addEventListener('click', handleQuantityChange);
                });

                document.querySelectorAll('.disminuir-cantidad').forEach(button => {
                    button.removeEventListener('click', handleQuantityChange);
                    button.addEventListener('click', handleQuantityChange);
                });

                document.querySelectorAll('.cantidad-input').forEach(input => {
                    input.removeEventListener('change', handleQuantityChange);
                    input.addEventListener('change', handleQuantityChange);
                });

                document.querySelectorAll('.eliminar-item').forEach(button => {
                    button.removeEventListener('click', eliminarItem);
                    button.addEventListener('click', eliminarItem);
                });
            }

            function handleQuantityChange(event) {
                const id = event.target.dataset.id;
                const input = document.querySelector(`.cantidad-input[data-id="${id}"]`);
                let nuevaCantidad;

                if (event.target.classList.contains('aumentar-cantidad')) {
                    nuevaCantidad = parseInt(input.value) + 1;
                } else if (event.target.classList.contains('disminuir-cantidad')) {
                    nuevaCantidad = parseInt(input.value) - 1;
                } else {
                    nuevaCantidad = parseInt(input.value);
                }

                if (isNaN(nuevaCantidad) || nuevaCantidad < 1) {
                    nuevaCantidad = 1;
                }

                actualizarCantidadEnCarrito(id, nuevaCantidad);
            }

            function actualizarCantidadEnCarrito(id, nuevaCantidad) {
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const itemIndex = carrito.findIndex(item => item.id == id); 

                if (itemIndex > -1) {
                    carrito[itemIndex].cantidad = nuevaCantidad;
                    localStorage.setItem('carrito', JSON.stringify(carrito));
                    cargarCarrito();
                }
            }

            function eliminarItem(event) {
                const id = event.currentTarget.dataset.id;
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const nuevoCarrito = carrito.filter(item => item.id != id); 
                localStorage.setItem('carrito', JSON.stringify(nuevoCarrito));
                cargarCarrito();
            }

            function vaciarCarrito() {
                if (confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
                    localStorage.removeItem('carrito');
                    cargarCarrito();
                }
            }
            
            // Función para redirigir a productos.php
            function seguirComprando() {
                window.location.href = 'productos.php'; // Redirige a productos.php sin ningún filtro
            }

            // Asignar eventos a los botones
            vaciarCarritoBtn.addEventListener('click', vaciarCarrito);
            mensajeCarritoVacio.querySelector('.continuar-compra').addEventListener('click', seguirComprando); // El botón dentro del mensaje de carrito vacío
            btnSeguirComprandoInferior.addEventListener('click', seguirComprando);
            btnSeguirComprandoResumen.addEventListener('click', seguirComprando);


            cargarCarrito();
        });
    </script>
</body>
</html>