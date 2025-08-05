<?php
session_start(); // Iniciar sesión para posiblemente obtener user_id si el usuario está logueado
require_once __DIR__ . '/db/conexion.php'; // Asegúrate de que esta ruta sea correcta

// Constantes de cálculo (deben ser las mismas que en carrito.php)
const TASA_IVA = 0.19; // Ejemplo: 19% de IVA para Colombia
const COSTO_ENVIO = 15000; // Ejemplo: 15,000 COP por envío

// Función para formatear el precio a formato colombiano con "COP" (reutilizado de carrito.php)
function formatPrice($price) {
    return new Intl.NumberFormat('es-CO', [
        'style' => 'decimal',
        'minimumFractionDigits' => 0,
        'maximumFractionDigits' => 0
    ])->format($price);
}

// === LÓGICA DE PROCESAMIENTO DEL FORMULARIO (POST) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recolectar y validar los datos del formulario
    $nombre_envio = trim($_POST['nombre_envio'] ?? '');
    $apellido_envio = trim($_POST['apellido_envio'] ?? '');
    $email_envio = trim($_POST['email_envio'] ?? '');
    $direccion_envio = trim($_POST['direccion_envio'] ?? '');
    $ciudad_envio = trim($_POST['ciudad_envio'] ?? '');
    $departamento_envio = trim($_POST['departamento_envio'] ?? '');
    $codigo_postal_envio = trim($_POST['codigo_postal_envio'] ?? '');
    $telefono_envio = trim($_POST['telefono_envio'] ?? '');
    $metodo_pago = $_POST['metodo_pago'] ?? '';
    
    $errores = [];

    if (empty($nombre_envio)) $errores[] = "El nombre de envío es obligatorio.";
    if (empty($apellido_envio)) $errores[] = "El apellido de envío es obligatorio.";
    if (empty($email_envio) || !filter_var($email_envio, FILTER_VALIDATE_EMAIL)) $errores[] = "El email de envío es obligatorio y debe ser válido.";
    if (empty($direccion_envio)) $errores[] = "La dirección de envío es obligatoria.";
    if (empty($ciudad_envio)) $errores[] = "La ciudad de envío es obligatoria.";
    if (empty($departamento_envio)) $errores[] = "El departamento de envío es obligatorio.";
    if (empty($telefono_envio)) $errores[] = "El teléfono de envío es obligatorio.";
    if (empty($metodo_pago)) $errores[] = "Debe seleccionar un método de pago.";

    // Validar datos de tarjeta si el método es 'tarjeta'
    if ($metodo_pago === 'tarjeta') {
        $numero_tarjeta = trim($_POST['numero_tarjeta'] ?? '');
        $mes_exp = trim($_POST['mes_exp'] ?? '');
        $anio_exp = trim($_POST['anio_exp'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        if (empty($numero_tarjeta) || !preg_match('/^[0-9]{13,16}$/', $numero_tarjeta)) $errores[] = "Número de tarjeta inválido.";
        if (empty($mes_exp) || !preg_match('/^(0[1-9]|1[0-2])$/', $mes_exp)) $errores[] = "Mes de expiración inválido.";
        if (empty($anio_exp) || !preg_match('/^[0-9]{2,4}$/', $anio_exp) || (int)$anio_exp < date('y')) $errores[] = "Año de expiración inválido o caducado.";
        if (empty($cvv) || !preg_match('/^[0-9]{3,4}$/', $cvv)) $errores[] = "CVV inválido.";
        
        // ADVERTENCIA DE SEGURIDAD: Nunca procesar o almacenar datos de tarjeta directamente en tu servidor sin PCI DSS compliance.
        // Usa pasarelas de pago como Stripe, PayPal, PayU, etc.
    }

    // 2. Obtener datos del carrito (se recomienda validarlos contra la BD si es posible para precios)
    $carrito_json = $_POST['carrito_data'] ?? '[]'; // Los datos del carrito vienen del JS en un campo oculto
    $carrito = json_decode($carrito_json, true);

    if (empty($carrito) || !is_array($carrito)) {
        $errores[] = "El carrito de compras está vacío o es inválido.";
    }

    // 3. Recalcular totales en el servidor (¡CRÍTICO para evitar manipulación de precios!)
    $subtotal_calculado = 0;
    foreach ($carrito as $item) {
        $precio_unitario = (float)str_replace(['$', ' COP', '.', ','], ['', '', '', '.'], $item['precio']); // Limpiar precio si viene formateado
        $cantidad = (int)$item['cantidad'];
        // Aquí deberías idealmente consultar el precio real del producto en tu BD por ID
        // para asegurarte de que el precio no haya sido manipulado en el cliente.
        // $producto_db = obtenerPrecioProductoDesdeDB($item['id']);
        // $precio_unitario = $producto_db['precio'];
        
        $subtotal_calculado += ($precio_unitario * $cantidad);
    }

    $impuestos_calculados = $subtotal_calculado * TASA_IVA;
    $envio_calculado = $subtotal_calculado > 0 ? COSTO_ENVIO : 0;
    $total_final = $subtotal_calculado + $impuestos_calculados + $envio_calculado;

    if (count($errores) > 0) {
        // Mostrar errores y no proceder
        echo "<p style='color: red; font-weight: bold;'>Errores encontrados:</p>";
        echo "<ul>";
        foreach ($errores as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='proceso_pago.php'>← Volver y corregir</a></p>";
        exit;
    }

    // 4. Guardar el pedido en la base de datos
    try {
        $pdo->beginTransaction(); // Iniciar una transacción para asegurar atomicidad

        $user_id = $_SESSION['user_id'] ?? null; // Obtener user_id si el usuario está logueado

        $stmt_pedido = $pdo->prepare("INSERT INTO pedidos (user_id, total_pedido, subtotal, impuestos, costo_envio, 
                                                    nombre_envio, apellido_envio, email_envio, direccion_envio, 
                                                    ciudad_envio, departamento_envio, codigo_postal_envio, telefono_envio, 
                                                    metodo_pago)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_pedido->execute([
            $user_id, $total_final, $subtotal_calculado, $impuestos_calculados, $envio_calculado,
            $nombre_envio, $apellido_envio, $email_envio, $direccion_envio,
            $ciudad_envio, $departamento_envio, $codigo_postal_envio, $telefono_envio,
            $metodo_pago
        ]);
        $pedido_id = $pdo->lastInsertId(); // Obtener el ID del pedido recién insertado

        // 5. Guardar los detalles del pedido
        $stmt_detalle = $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, nombre_producto, cantidad, precio_unitario, subtotal_item)
                                        VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($carrito as $item) {
            $precio_unitario_item = (float)str_replace(['$', ' COP', '.', ','], ['', '', '', '.'], $item['precio']);
            $cantidad_item = (int)$item['cantidad'];
            $subtotal_item_calculado = $precio_unitario_item * $cantidad_item;

            $stmt_detalle->execute([
                $pedido_id, 
                $item['id'], // ID del producto (asumiendo que tu producto tiene ID)
                $item['nombre'], 
                $cantidad_item, 
                $precio_unitario_item, 
                $subtotal_item_calculado
            ]);
        }

        $pdo->commit(); // Confirmar la transacción

        // 6. Limpiar el carrito de localStorage (se hace vía JS en la página de confirmación)
        // Opcional: podrías ponerlo aquí y redirigir con un parámetro de éxito para la confirmación
        // echo "<script>localStorage.removeItem('carrito');</script>";

        // 7. Redirigir a la página de confirmación
        header("Location: confirmacion_pedido.php?pedido_id=" . $pedido_id);
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack(); // Deshacer la transacción en caso de error
        echo "<p style='color: red; font-weight: bold;'>Error al procesar el pedido: " . $e->getMessage() . "</p>";
        echo "<p><a href='proceso_pago.php'>← Volver al formulario de pago</a></p>";
        exit;
    }
}

// === LÓGICA PARA CARGAR LA PÁGINA (GET) ===
// Si la página se carga por GET, verificamos el carrito de localStorage
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Finalizar Compra - Natural Beauty</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-background-main: #f5f0eb;
            --color-text-main: #322203;
            --color-primary-brand: #8c6e4a;
            --color-primary-brand-hover: #6d5639;
            --color-light-bg: #ffffff;
            --color-light-border: #eee;
            --color-accent-stars: #ffc107;
            --color-secondary-bg: #e7d7c9;
            --color-error: #e74c3c;
            --color-error-hover: #c0392b;
            --color-text-light: #777777;
        }

        body {
            font-family: 'TT Commons Pro Expanded', sans-serif;
            background-color: var(--color-background-main);
            color: var(--color-text-main);
            margin: 0;
            padding: 0;
        }

        .contenedor-checkout {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .seccion-form {
            flex: 2;
            min-width: 350px;
            background: var(--color-light-bg);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 30px;
        }

        .seccion-resumen {
            flex: 1;
            min-width: 300px;
            background: var(--color-secondary-bg);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            padding: 30px;
            height: fit-content;
        }

        h1, .seccion-resumen h2 {
            font-family: 'Hatton', serif;
            color: var(--color-primary-brand);
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
        }
        .seccion-resumen h2 {
            font-size: 1.8rem;
            color: var(--color-text-main);
            border-bottom: 1px solid var(--color-primary-brand);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        /* Formulario */
        .campo-grupo {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .campo-grupo > div {
            flex: 1;
        }
        .campo-grupo.full-width {
            display: block; /* Para campos que deben ocupar todo el ancho */
        }
        .campo-grupo.full-width > div {
            flex: none;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--color-text-main);
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-light-border);
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
            margin-bottom: 10px;
            background-color: var(--color-light-bg);
            color: var(--color-text-main);
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .metodo-pago-opcion {
            display: block;
            margin-bottom: 15px;
            padding: 15px;
            background-color: var(--color-background-main); /* Un tono más claro para las opciones */
            border: 1px solid var(--color-light-border);
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
        }
        .metodo-pago-opcion:hover {
            background-color: #f0ebe6;
            border-color: var(--color-primary-brand);
        }
        .metodo-pago-opcion input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2); /* Agrandar radio buttons */
        }
        .metodo-pago-opcion label {
            display: inline-block;
            font-weight: normal;
            margin-bottom: 0;
            cursor: pointer;
        }
        .tarjeta-info {
            border: 1px dashed var(--color-primary-brand);
            padding: 20px;
            margin-top: 15px;
            border-radius: 8px;
            background-color: #fffaf5;
        }

        .resumen-linea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
            color: var(--color-text-main);
        }
        .resumen-linea.total {
            font-weight: bold;
            font-size: 1.3rem;
            color: var(--color-primary-brand);
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--color-primary-brand);
        }

        .lista-productos-resumen {
            max-height: 250px; /* Limitar altura para scroll */
            overflow-y: auto;
            border-bottom: 1px solid var(--color-light-border);
            margin-bottom: 20px;
            padding-bottom: 15px;
        }
        .item-resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.95rem;
            color: var(--color-text-light);
        }
        .item-resumen span:first-child {
            max-width: 70%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .boton-finalizar-compra {
            background-color: var(--color-primary-brand);
            color: white;
            border: none;
            padding: 18px 25px;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 20px;
        }
        .boton-finalizar-compra:hover {
            background-color: var(--color-primary-brand-hover);
        }

        .error-mensaje {
            color: var(--color-error);
            background-color: #ffe0e0;
            border: 1px solid var(--color-error);
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .contenedor-checkout {
                flex-direction: column;
                margin: 30px auto;
                padding: 15px;
                gap: 30px;
            }
            .seccion-form, .seccion-resumen {
                width: calc(100% - 30px);
                padding: 20px;
            }
            .campo-grupo {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>
    <main class="contenedor-checkout">
        <section class="seccion-form">
            <h1>Detalles de Envío y Pago</h1>
            <form id="checkoutForm" method="POST" action="proceso_pago.php">
                <h2>Información de Envío</h2>
                <div class="campo-grupo">
                    <div>
                        <label for="nombre_envio">Nombre:</label>
                        <input type="text" id="nombre_envio" name="nombre_envio" required>
                    </div>
                    <div>
                        <label for="apellido_envio">Apellido:</label>
                        <input type="text" id="apellido_envio" name="apellido_envio" required>
                    </div>
                </div>
                <div class="campo-grupo full-width">
                    <div>
                        <label for="email_envio">Email:</label>
                        <input type="email" id="email_envio" name="email_envio" required>
                    </div>
                </div>
                <div class="campo-grupo full-width">
                    <div>
                        <label for="direccion_envio">Dirección:</label>
                        <input type="text" id="direccion_envio" name="direccion_envio" placeholder="Calle, número, apartamento" required>
                    </div>
                </div>
                <div class="campo-grupo">
                    <div>
                        <label for="ciudad_envio">Ciudad:</label>
                        <input type="text" id="ciudad_envio" name="ciudad_envio" required>
                    </div>
                    <div>
                        <label for="departamento_envio">Departamento/Estado:</label>
                        <input type="text" id="departamento_envio" name="departamento_envio" required>
                    </div>
                </div>
                <div class="campo-grupo">
                    <div>
                        <label for="codigo_postal_envio">Código Postal:</label>
                        <input type="text" id="codigo_postal_envio" name="codigo_postal_envio">
                    </div>
                    <div>
                        <label for="telefono_envio">Teléfono:</label>
                        <input type="tel" id="telefono_envio" name="telefono_envio" pattern="[0-9]{7,10}" title="Mínimo 7 y máximo 10 dígitos numéricos" required>
                    </div>
                </div>

                <h2>Método de Pago</h2>
                <div class="metodo-pago-opcion">
                    <input type="radio" id="pago_tarjeta" name="metodo_pago" value="tarjeta" checked>
                    <label for="pago_tarjeta"><i class="fas fa-credit-card"></i> Tarjeta de Crédito/Débito</label>
                </div>
                <div id="tarjeta_info" class="tarjeta-info">
                    <div class="campo-grupo full-width">
                        <div>
                            <label for="numero_tarjeta">Número de Tarjeta:</label>
                            <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="XXXX XXXX XXXX XXXX" pattern="[0-9]{13,16}" title="13 a 16 dígitos" required>
                        </div>
                    </div>
                    <div class="campo-grupo">
                        <div>
                            <label for="mes_exp">Fecha de Expiración (MM/AA):</label>
                            <div style="display:flex; gap: 10px;">
                                <input type="text" id="mes_exp" name="mes_exp" placeholder="MM" pattern="(0[1-9]|1[0-2])" title="Mes (01-12)" required style="width: 50%;">
                                <input type="text" id="anio_exp" name="anio_exp" placeholder="AA" pattern="[0-9]{2}" title="Año (Últimos 2 dígitos)" required style="width: 50%;">
                            </div>
                        </div>
                        <div>
                            <label for="cvv">CVV:</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" pattern="[0-9]{3,4}" title="3 o 4 dígitos" required>
                        </div>
                    </div>
                    <p style="font-size:0.85em; color:var(--color-error); text-align:left;">
                        <strong>¡ADVERTENCIA DE SEGURIDAD!</strong> No guardamos la información de tu tarjeta de crédito directamente en nuestro servidor. Para una implementación real, se debe integrar una pasarela de pago segura (ej. Stripe, PayPal, PayU).
                    </p>
                </div>

                <div class="metodo-pago-opcion">
                    <input type="radio" id="pago_efectivo" name="metodo_pago" value="efectivo">
                    <label for="pago_efectivo"><i class="fas fa-money-bill-wave"></i> Pago en Efectivo / Contra Entrega</label>
                </div>
                <div class="metodo-pago-opcion">
                    <input type="radio" id="pago_pse" name="metodo_pago" value="pse">
                    <label for="pago_pse"><i class="fas fa-university"></i> PSE (Pago Seguro en Línea)</label>
                </div>

                <input type="hidden" name="carrito_data" id="carrito_data">

                <button type="submit" class="boton-finalizar-compra">Finalizar Compra</button>
            </form>
        </section>

        <aside class="seccion-resumen">
            <h2>Resumen del Pedido</h2>
            <div id="productos-resumen" class="lista-productos-resumen">
                <p style="text-align:center; color:var(--color-text-light);">El carrito está vacío.</p>
            </div>
            
            <div class="resumen-linea">
                <span>Subtotal:</span>
                <span id="resumen-subtotal">$0 COP</span>
            </div>
            <div class="resumen-linea">
                <span>Envío:</span>
                <span id="resumen-envio">$0 COP</span>
            </div>
            <div class="resumen-linea">
                <span>Impuestos (IVA):</span>
                <span id="resumen-impuestos">$0 COP</span>
            </div>
            <div class="resumen-linea total">
                <span>Total a Pagar:</span>
                <span id="resumen-total">$0 COP</span>
            </div>
        </aside>
    </main>

    <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
    <script src="buscador.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carritoDataInput = document.getElementById('carrito_data');
            const productosResumenDiv = document.getElementById('productos-resumen');
            const resumenSubtotalSpan = document.getElementById('resumen-subtotal');
            const resumenEnvioSpan = document.getElementById('resumen-envio');
            const resumenImpuestosSpan = document.getElementById('resumen-impuestos');
            const resumenTotalSpan = document.getElementById('resumen-total');

            const tarjetaInfoDiv = document.getElementById('tarjeta_info');
            const radioPagoTarjeta = document.getElementById('pago_tarjeta');
            const radioPagoEfectivo = document.getElementById('pago_efectivo');
            const radioPagoPse = document.getElementById('pago_pse');

            const TASA_IVA = 0.19; // Debe coincidir con PHP
            const COSTO_ENVIO = 15000; // Debe coincidir con PHP

            function formatPrice(price) {
                return new Intl.NumberFormat('es-CO', {
                    style: 'decimal',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(price);
            }

            // Función para cargar y mostrar el resumen del carrito
            function cargarResumenCarrito() {
                let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                productosResumenDiv.innerHTML = ''; // Limpiar resumen

                if (carrito.length === 0) {
                    productosResumenDiv.innerHTML = '<p style="text-align:center; color:var(--color-text-light);">El carrito está vacío.</p>';
                    // Deshabilitar botón de finalizar compra si el carrito está vacío
                    document.querySelector('.boton-finalizar-compra').disabled = true;
                    // También resetear totales
                    resumenSubtotalSpan.textContent = '$0 COP';
                    resumenEnvioSpan.textContent = '$0 COP';
                    resumenImpuestosSpan.textContent = '$0 COP';
                    resumenTotalSpan.textContent = '$0 COP';
                    return;
                } else {
                     document.querySelector('.boton-finalizar-compra').disabled = false;
                }

                let subtotalProductos = 0;
                carrito.forEach(item => {
                    // Asegurarse de que el precio sea un número flotante, limpiando formato
                    const itemPrecioNumerico = parseFloat(String(item.precio).replace('$', '').replace(' COP', '').replace(/\./g, '').replace(',', '.'));
                    const itemSubtotal = itemPrecioNumerico * item.cantidad;
                    subtotalProductos += itemSubtotal;

                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('item-resumen');
                    itemDiv.innerHTML = `
                        <span>${item.nombre} (x${item.cantidad})</span>
                        <span>$${formatPrice(itemSubtotal)} COP</span>
                    `;
                    productosResumenDiv.appendChild(itemDiv);
                });

                const impuestosCalculados = subtotalProductos * TASA_IVA;
                const envioCalculado = subtotalProductos > 0 ? COSTO_ENVIO : 0;
                const totalAPagar = subtotalProductos + impuestosCalculados + envioCalculado;

                resumenSubtotalSpan.textContent = `$${formatPrice(subtotalProductos)} COP`;
                resumenEnvioSpan.textContent = `$${formatPrice(envioCalculado)} COP`;
                resumenImpuestosSpan.textContent = `$${formatPrice(impuestosCalculados)} COP`;
                resumenTotalSpan.textContent = `$${formatPrice(totalAPagar)} COP`;

                // Poner los datos del carrito en el campo oculto para enviarlo al PHP
                carritoDataInput.value = JSON.stringify(carrito);
            }

            // Función para mostrar/ocultar campos de tarjeta
            function toggleTarjetaFields() {
                if (radioPagoTarjeta.checked) {
                    tarjetaInfoDiv.style.display = 'block';
                    // Requerir campos de tarjeta
                    document.getElementById('numero_tarjeta').required = true;
                    document.getElementById('mes_exp').required = true;
                    document.getElementById('anio_exp').required = true;
                    document.getElementById('cvv').required = true;
                } else {
                    tarjetaInfoDiv.style.display = 'none';
                    // No requerir campos de tarjeta si no es el método seleccionado
                    document.getElementById('numero_tarjeta').required = false;
                    document.getElementById('mes_exp').required = false;
                    document.getElementById('anio_exp').required = false;
                    document.getElementById('cvv').required = false;
                }
            }

            // Asignar event listeners a los radios de método de pago
            radioPagoTarjeta.addEventListener('change', toggleTarjetaFields);
            radioPagoEfectivo.addEventListener('change', toggleTarjetaFields);
            radioPagoPse.addEventListener('change', toggleTarjetaFields);

            // Cargar el resumen al cargar la página
            cargarResumenCarrito();
            // Inicializar la visibilidad de los campos de tarjeta
            toggleTarjetaFields(); 
        });
    </script>
</body>
</html>