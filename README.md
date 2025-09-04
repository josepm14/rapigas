# 📌 Sistema de Gestión de Empleados

Este es un proyecto desarrollado en **PHP + MySQL** para la gestión de empleados en la empresa RAPIGAS.

## 🚀 Funcionalidades
- Listado completo de empleados
- Registro de nuevos empleados
- Edición de datos de empleados
- Eliminación de registros
- Búsqueda de empleados por nombre, apellido o DNI

## 📂 Estructura del proyecto
```
gestion_empleados/
├── api/
│   ├── usuarios.php    # API REST para CRUD de usuarios
│   └── search.php      # API para búsqueda de usuarios
├── pages/
│   ├── principal.php   # Página principal con listado y formularios
│   └── busqueda.php    # Página de búsqueda de usuarios
├── conexion.php        # Configuración de conexión a base de datos
├── index.php          # Punto de entrada de la aplicación
└── README.md          # Documentación del proyecto
```

## 🛠 Tecnologías utilizadas
- PHP 8.2
- MySQL/MariaDB
- HTML5
- CSS3
- JavaScript (Fetch API)
- Bootstrap 5

## 📊 Base de datos
La base de datos `gestion_empleados` contiene una tabla principal:

### Tabla: usuarios
- id (INT, Primary Key, Auto Increment)
- nombre (VARCHAR)
- apellido (VARCHAR)
- dni (VARCHAR)
- direccion (VARCHAR)
- telefono (VARCHAR)
- cargo (VARCHAR)
- responsabilidad (VARCHAR)
- usuario (VARCHAR)

## 🔧 Instalación
1. Clonar el repositorio en el directorio web:
   ```bash
   git clone https://github.com/usuario/gestion_empleados.git
   ```
2. Importar el archivo SQL de la base de datos
3. Configurar los datos de conexión en `conexion.php`
4. Acceder a través del navegador: `http://localhost/gestion_empleados`

## 💻 Uso
- **Listado**: La página principal muestra todos los empleados registrados
- **Agregar**: Click en "Agregar Usuario" y completar el formulario
- **Editar**: Click en el botón "Editar" junto al registro deseado
- **Eliminar**: Click en el botón "Eliminar" (requiere confirmación)
- **Buscar**: Usar la barra de búsqueda para filtrar por nombre/apellido/DNI

## 🔍 API Endpoints
- GET `/api/usuarios.php` - Listar todos los usuarios
- POST `/api/usuarios.php` - Crear nuevo usuario
- PUT `/api/usuarios.php` - Actualizar usuario existente
- DELETE `/api/usuarios.php` - Eliminar usuario
- GET `/api/search.php?q=texto` - Buscar usuarios

## 🔒 Seguridad
- Protección contra SQL Injection usando PDO
- Validación de datos en formularios
- Sanitización de salidas HTML
- Manejo de errores y excepciones



## 📝 Licencia
Este proyecto está bajo la Licencia MIT.

## ✉️ Grupo 5:
- Estudiante 1: [`PINARES MEJIA Jose Eriberto`]
- Estudiante 2: [`SOSA ORTIZ Rafael`]
- Estudiante 3: [`SENGUÑA CCANA Carlos`]
