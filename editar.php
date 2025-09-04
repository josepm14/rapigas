<?php
require 'conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no válido");
}

$sql = "SELECT * FROM empleados WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$emp = $stmt->fetch();
if (!$emp) {
    die("Usuario no encontrado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Editar Usuario</h2>
    <form action="actualizar.php" method="post">
        <input type="hidden" name="id" value="<?= $emp['id'] ?>">
        <input type="text" name="nombre" value="<?= $emp['nombre'] ?>" required><br>
        <input type="text" name="Apellido" value="<?= $emp['Apellido'] ?>" required><br>
        <input type="number" name="DNI" value="<?= $emp['DNI'] ?>" required><br>
        <input type="text" name="Direccion" value="<?= $emp['Direccion'] ?>" required><br>
        <input type="number" name="Telefono" value="<?= $emp['Telefono'] ?>" required><br>
        <input type="text" name="Cargo" value="<?= $emp['Cargo'] ?>" required><br>
        <input type="text" name="Responsabilidad" value="<?= $emp['Responsabilidad'] ?>" required><br>
        <input type="text" name="usuario" value="<?= $emp['usuario'] ?>" required><br>
        <button type="submit">Actualizar</button>
    </form>
    <a href="index.php">⬅ Volver</a>
</body>
</html>
