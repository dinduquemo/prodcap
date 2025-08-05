<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido - Natural Beauty</title>
    <link rel="stylesheet" href="estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'TT Commons Pro Expanded', sans-serif;
            background-color: #f5f0eb;
            color: #322203;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .contenedor-confirmacion {
            max-width: 700px;
            margin: 80px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 {
            font-family: 'Hatton', serif;
            color: #8c6e4a;
            font-size: 3rem;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.15rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .icono-confirmacion {
            color: #5cb85c; /* Verde de éxito */
            font-size: 5rem;
            margin-bottom: 30px;
        }
        .detalle-pedido-id {
            font-size: 1.2rem;
            font-weight: bold;
            color: #322203;
            background-color: #e7d7c9;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
        .boton-volver {
            display: inline-block;
            background-color: #8c6e4a;
            color: white;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 30px;
            font-size: 1.1rem;
        }
        .boton-volver:hover {
            background-color: #6d5639;
        }
    </style>
    <?php include(__DIR__ . "/Barra_de_navegacion.php"); ?>
</head>
<body>
    <main>
        <div class="contenedor-confirmacion">
            <i class="fas fa-check-circle icono-confirmacion"></i>
            <h1>¡Pedido Realizado con Éxito!</h1>
            <p>Gracias por tu compra en Natural Beauty. Tu pedido ha sido procesado y será enviado pronto.</p>
            <?php
                $pedido_id = $_GET['pedido_id'] ?? 'N/A';
            ?>
            <p>Tu número de pedido es:</p>
            <div class="detalle-pedido-id">#NB-<?php echo htmlspecialchars($pedido_id); ?></div>
            <p>Recibirás un email de confirmación con los detalles de tu compra.</p>
            <a href="productos.php" class="boton-volver">Continuar Comprando</a>
        </div>
    </main>
    <?php include(__DIR__ . "/Pie_de_Pag.php"); ?>
    <script src="buscador.js"></script>
    <script>
        // Vaciar el carrito de localStorage una vez que el pedido es confirmado
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.removeItem('carrito');
            // Opcional: Actualizar algún contador de carrito en la barra de navegación si lo tienes
        });
    </script>
</body>
</html>