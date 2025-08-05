<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NB - Tienda</title>
  <link rel="stylesheet" href="estilo.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
/* Estilos responsive para barra de navegación */
@media (max-width: 768px) {
  .navegacion-contenedor {
    flex-direction: column;
    align-items: center;
    padding: 15px;
    background-color: #fdf8f4;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    border-radius: 0 0 10px 10px;
    gap: 10px;
  }

  .logo-contenedor {
    text-align: center;
    margin-bottom: 8px;
  }

  .logo-marca {
    font-size: 2rem;
    color: #8c6e4a;
    text-decoration: none;
    font-weight: bold;
  }

  .menu-principal {
    flex-direction: column;
    align-items: center;
    width: 100%;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .menu-principal li {
    width: 100%;
    border-top: 1px solid #dcd0c3;
  }

  .menu-principal li:first-child {
    border-top: none;
  }

  .menu-principal a {
    display: block;
    padding: 12px 0;
    width: 100%;
    text-align: center;
    color: #322203;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
  }

  .menu-principal a:hover {
    background-color: #f2e5dc;
  }

  .submenu {
    background-color: #fdf8f4;
    padding: 0;
    margin: 0;
  }

  .submenu li {
    border-top: 1px dashed #d9cfc5;
  }

  .submenu li:first-child {
    border-top: none;
  }

  .submenu a {
    font-size: 0.95rem;
    padding: 10px 0;
    color: #5e4b3c;
  }

  .buscador {
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .buscador form {
    display: flex;
    width: 90%;
    border: 1px solid #ccc;
    border-radius: 20px;
    overflow: hidden;
    background-color: #fff;
  }

  .buscador input {
    flex: 1;
    padding: 8px 12px;
    border: none;
    outline: none;
    font-size: 0.95rem;
  }

  .buscador button {
    padding: 0 15px;
    background-color: #8c6e4a;
    color: white;
    border: none;
    font-size: 1rem;
    cursor: pointer;
  }

  .acceso-usuario {
    display: flex;
    justify-content: center;
    gap: 25px;
    margin-top: 10px;
    font-size: 1.2rem;
  }

  .icono-usuario, .icono-carrito {
    color: #322203;
    text-decoration: none;
    position: relative;
  }

  .contador-carrito {
    position: absolute;
    top: -5px;
    right: -10px;
    background-color: #c86c50;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 50%;
  }
}

</style>

<body>

<header class="header-fijo">
  <nav class="navegacion-contenedor">

    <div class="logo-contenedor">
      <a href="index.php" class="logo-marca">NB</a>
    </div>


    <ul class="menu-principal" id="menu-principal">
      <li><a href="Inicio.php">INICIO</a></li>
      <li class="menu-desplegable">
        <a href="#">PRODUCTOS <i class="fas fa-chevron-down"></i></a>
        <ul class="submenu">
          <li><a href="productos.php?categoria=shampoo">Shampoos</a></li>
          <li><a href="productos.php?categoria=acondicionador">Acondicionador</a></li>
          <li><a href="productos.php?categoria=tratamiento">Tratamientos</a></li>
           <li><a href="productos.php?categoria=todos">Ver todos</a></li> 
          <!-- <li><a href="kits.php">Kits</a></li> -->
        </ul>
      </li>
      <li><a href="blogs.php">BLOG</a></li>
    </ul>

    <div class="buscador">
      <form id="formulario-busqueda" role="search">
        <input type="text" id="busqueda-input" name="q" placeholder="Buscar productos" aria-label="Buscar productos">
        <button type="submit" aria-label="Buscar producto"><i class="fas fa-search"></i></button>
      </form>
    </div>

    <div class="acceso-usuario">
      <a href="Iniciar_sesion.php" class="icono-usuario" title="Mi cuenta">
        <i class="fas fa-user"></i>
      </a>
      <a href="carrito.php" class="icono-carrito" title="Carrito de compras">
        <i class="fas fa-shopping-cart"></i>
        <span class="contador-carrito">0</span>
      </a>
    </div>

  </nav>
</header>
