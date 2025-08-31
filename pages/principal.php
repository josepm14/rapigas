<h2>Gestión de Usuarios</h2>

<!-- Botones de acción -->
<div>
    <button onclick="mostrarSeccion('listado')">📋 Ver Listado</button>
    <button onclick="mostrarSeccion('formAgregar')">➕ Agregar Usuario</button>
</div>

<!-- Sección listado -->
<div id="listado">
    <table border="1" id="tablaUsuarios">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>DNI</th>
                <th>Dirección</th><th>Teléfono</th><th>Cargo</th>
                <th>Responsabilidad</th><th>Usuario</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Sección Agregar -->
<div id="formAgregar" style="display:none;">
    <h3>Agregar Usuario</h3>
    <form id="agregarForm">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="apellido" placeholder="Apellido" required><br>
        <input type="text" name="dni" placeholder="DNI" required><br>
        <input type="text" name="direccion" placeholder="Dirección"><br>
        <input type="text" name="telefono" placeholder="Teléfono"><br>
        <input type="text" name="cargo" placeholder="Cargo"><br>
        <input type="text" name="responsabilidad" placeholder="Responsabilidad"><br>
        <input type="text" name="usuario" placeholder="Usuario"><br>
        <button type="submit">Guardar</button>
        <button type="button" onclick="mostrarSeccion('listado')">Cancelar</button>
    </form>
</div>

<!-- Sección Editar (se llenará dinámicamente) -->
<div id="formEditar" style="display:none;">
    <h3>Editar Usuario</h3>
    <form id="editarForm">
        <input type="hidden" name="id">
        <input type="text" name="nombre" placeholder="Nombre"><br>
        <input type="text" name="apellido" placeholder="Apellido"><br>
        <input type="text" name="dni" placeholder="DNI"><br>
        <input type="text" name="direccion" placeholder="Dirección"><br>
        <input type="text" name="telefono" placeholder="Teléfono"><br>
        <input type="text" name="cargo" placeholder="Cargo"><br>
        <input type="text" name="responsabilidad" placeholder="Responsabilidad"><br>
        <input type="text" name="usuario" placeholder="Usuario"><br>
        <button type="submit">Actualizar</button>
        <button type="button" onclick="mostrarSeccion('listado')">Cancelar</button>
    </form>
</div>

<script>
// Mostrar/Ocultar secciones
function mostrarSeccion(seccion) {
    document.getElementById("listado").style.display = "none";
    document.getElementById("formAgregar").style.display = "none";
    document.getElementById("formEditar").style.display = "none";
    document.getElementById(seccion).style.display = "block";
}

// Cargar listado
function cargarUsuarios() {
    fetch("api/usuarios.php")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tablaUsuarios tbody");
            tbody.innerHTML = "";
            data.forEach(u => {
                tbody.innerHTML += `
                    <tr>
                        <td>${u.id}</td>
                        <td>${u.nombre}</td>
                        <td>${u.apellido}</td>
                        <td>${u.dni}</td>
                        <td>${u.direccion}</td>
                        <td>${u.telefono}</td>
                        <td>${u.cargo}</td>
                        <td>${u.responsabilidad}</td>
                        <td>${u.usuario}</td>
                        <td>
                            <button onclick="editarUsuario(${u.id}, '${u.nombre}', '${u.apellido}', '${u.dni}', '${u.direccion}', '${u.telefono}', '${u.cargo}', '${u.responsabilidad}', '${u.usuario}')">✏️ Editar</button>
                            <button onclick="eliminarUsuario(${u.id})">🗑️ Eliminar</button>
                        </td>
                    </tr>`;
            });
        });
}

// Guardar nuevo
document.getElementById("agregarForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    fetch("api/usuarios.php", {
        method: "POST",
        body: JSON.stringify(data)
    }).then(() => {
        cargarUsuarios();
        mostrarSeccion("listado");
    });
});

// Preparar edición
function editarUsuario(id, nombre, apellido, dni, direccion, telefono, cargo, responsabilidad, usuario) {
    const form = document.getElementById("editarForm");
    form.id.value = id;
    form.nombre.value = nombre;
    form.apellido.value = apellido;
    form.dni.value = dni;
    form.direccion.value = direccion;
    form.telefono.value = telefono;
    form.cargo.value = cargo;
    form.responsabilidad.value = responsabilidad;
    form.usuario.value = usuario;
    mostrarSeccion("formEditar");
}

// Guardar edición
document.getElementById("editarForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    fetch("api/usuarios.php", {
        method: "PUT",
        body: JSON.stringify(data)
    }).then(() => {
        cargarUsuarios();
        mostrarSeccion("listado");
    });
});

// Eliminar
function eliminarUsuario(id) {
    if (!confirm("¿Seguro de eliminar este usuario?")) return;
    fetch("api/usuarios.php", {
        method: "DELETE",
        body: `id=${id}`
    }).then(() => cargarUsuarios());
}

// Inicial
cargarUsuarios();
</script>
