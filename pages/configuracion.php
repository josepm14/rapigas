<?php
// Verificar si se enviaron nuevos datos de configuración
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $config = [
            'db_host' => $_POST['db_host'],
            'db_name' => $_POST['db_name'],
            'db_user' => $_POST['db_user'],
            'db_pass' => $_POST['db_pass'],
            'company_name' => $_POST['company_name'],
            'admin_email' => $_POST['admin_email']
        ];
        
        // Guardar configuración en un archivo
        file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));
        $mensaje = "Configuración guardada exitosamente.";
    } catch (Exception $e) {
        $error = "Error al guardar la configuración: " . $e->getMessage();
    }
}

// Cargar configuración actual
$config = [];
if (file_exists('config.json')) {
    $config = json_decode(file_get_contents('config.json'), true);
}
?>

<div class="container mt-4">
    <h2>Configuración del Sistema</h2>
    
    <?php if (isset($mensaje)): ?>
        <div class="alert alert-success"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" class="mt-4">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Configuración de Base de Datos</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Host de Base de Datos</label>
                    <input type="text" name="db_host" class="form-control" 
                           value="<?php echo $config['db_host'] ?? 'localhost'; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de Base de Datos</label>
                    <input type="text" name="db_name" class="form-control" 
                           value="<?php echo $config['db_name'] ?? 'gestion_empleados'; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Usuario de Base de Datos</label>
                    <input type="text" name="db_user" class="form-control" 
                           value="<?php echo $config['db_user'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña de Base de Datos</label>
                    <input type="password" name="db_pass" class="form-control" 
                           value="<?php echo $config['db_pass'] ?? ''; ?>">
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Configuración General</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre de la Empresa</label>
                    <input type="text" name="company_name" class="form-control" 
                           value="<?php echo $config['company_name'] ?? 'RAPIGAS'; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email del Administrador</label>
                    <input type="email" name="admin_email" class="form-control" 
                           value="<?php echo $config['admin_email'] ?? ''; ?>">
                </div>
            </div>
        </div>
<br/>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Guardar Configuración</button>
        </div>
    </form>

    <div class="mt-4">
        <h4>Información del Sistema</h4>
        <table class="table">
            <tr>
                <td>Versión de PHP:</td>
                <td><?php echo phpversion(); ?></td>
            </tr>
            <tr>
                <td>Sistema Operativo:</td>
                <td><?php echo php_uname(); ?></td>
            </tr>
            <tr>
                <td>Servidor Web:</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
            </tr>
        </table>
    </div>
</div>

<style>
.container {
    max-width: 800px;
}
.card {
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.card-header {
    background-color: #f8f9fa;
}
</style>