
<?php
$busqueda = isset($_GET['q']) ? $_GET['q'] : '';
$resultados = [];

?>

<div class="container">
    <h2>Búsqueda de Usuarios</h2>
    
    <form id="searchForm" class="search-form">
        <div class="form-group">
            <input type="text" 
                   name="q" 
                   class="form-control"
                   placeholder="Ingrese Nombre o Apellido o DNI" 
                   value="<?php echo htmlspecialchars($busqueda); ?>">
        </div>
        <button type="submit" class="btn btn-outline-success">Buscar</button>
    </form>

    <div id="resultados" class="results-section">
        <!-- Aquí se mostrarán los resultados dinámicamente -->
    </div>
</div>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const busqueda = this.querySelector('input[name="q"]').value;
    const resultadosDiv = document.getElementById('resultados');
    
    if (busqueda.trim() !== '') {
        fetch(`../api/search.php?q=${encodeURIComponent(busqueda)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let html = `
                        <h3>Resultados de la búsqueda</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>DNI</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Cargo</th>
                                    <th>Responsabilidad</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>`;
                    
                    data.forEach(usuario => {
                        html += `
                            <tr>
                                <td>${usuario.id}</td>
                                <td>${usuario.nombre}</td>
                                <td>${usuario.apellido}</td>
                                <td>${usuario.dni}</td>
                                <td>${usuario.direccion}</td>
                                <td>${usuario.telefono}</td>
                                <td>${usuario.cargo}</td>
                                <td>${usuario.responsabilidad}</td>
                                <td>${usuario.usuario}</td>
                            </tr>`;
                    });
                    
                    html += `
                            </tbody>
                        </table>`;
                    
                    resultadosDiv.innerHTML = html;
                } else {
                    resultadosDiv.innerHTML = `
                        <div class="alert alert-info">
                            No se encontraron resultados para "${busqueda}"
                        </div>`;
                }
            })
            .catch(error => {
                resultadosDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Error al realizar la búsqueda: ${error.message}
                    </div>`;
            });
    }
});
</script>

