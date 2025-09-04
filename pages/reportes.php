<?php
require_once "conexion.php";

// Función para obtener reportes de usuarios
function fetchUserReports($pdo) {
    $stmt = $pdo->query("SELECT nombre, apellido, dni, telefono FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener reportes
$reports = fetchUserReports($pdo);

// Obtener conteo de usuarios por cargo
function fetchCargoCounts($pdo) {
    $stmt = $pdo->query("SELECT cargo, COUNT(*) as cantidad FROM usuarios GROUP BY cargo");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$cargoCounts = fetchCargoCounts($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Usuarios</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        #cargoChart {
            max-width: 500px;
            max-height: 300px;
            width: 100%;
            height: 300px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body>
    <h1>Reportes de Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo htmlspecialchars($report['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($report['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($report['dni']); ?></td>
                    <td><?php echo htmlspecialchars($report['telefono']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Dashboard de Usuarios</h1>
    <canvas id="cargoChart"></canvas>
    <script>
        const cargos = <?php echo json_encode(array_column($cargoCounts, 'cargo')); ?>;
        const cantidades = <?php echo json_encode(array_column($cargoCounts, 'cantidad')); ?>;

        const ctx = document.getElementById('cargoChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: cargos,
                datasets: [{
                    label: 'Cantidad de usuarios por cargo',
                    data: cantidades,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>