<?php
require_once "conexion.php";

// Definir la página actual (según parámetro GET)
$page = isset($_GET['page']) ? $_GET['page'] : 'principal';

// Sanitizar el nombre de la página para evitar accesos indebidos
$allowed_pages = ['principal', 'busqueda', 'configuracion', 'reportes'];
if (!in_array($page, $allowed_pages)) {
    $page = 'principal';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Básico</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>GESTION DE TRABAJADORES RAPIGAS</h2>
        <ul>
            <li><a href="index.php?page=principal" class="<?= $page=='principal' ? 'active' : '' ?>">🏠 Principal</a></li>
            <li><a href="index.php?page=busqueda" class="<?= $page=='busqueda' ? 'active' : '' ?>">🔍 Búsqueda</a></li>
            <li><a href="index.php?page=configuracion" class="<?= $page=='configuracion' ? 'active' : '' ?>">⚙️ Configuración</a></li>
            <li><a href="index.php?page=reportes" class="<?= $page=='reportes' ? 'active' : '' ?>">⚙️ Reportes</a></li>
        </ul>
           <div class="sidebar-logo">
        <img src="logo.png" alt="Logo">
    </div>
    </div>

   
</div>

    <!-- Contenido principal -->
    <div class="main-content">
        <header>
            <h1><?php echo ucfirst($page); ?></h1>
        </header>
        <section>
            <?php require "pages/{$page}.php"; ?>
        </section>
    </div>
</body>
</html>
