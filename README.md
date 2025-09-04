# 📌 Sistema de Gestión de Empleados RAPIGAS

Este es un proyecto desarrollado en **PHP + MySQL** para gestionar empleados de la empresa RAPIGAS, permitiendo realizar operaciones CRUD, reportes y búsquedas avanzadas.

## 🎯 Características Principales
- ✨ Interfaz intuitiva y responsive
- 📋 Gestión completa de empleados (CRUD)
- 🔍 Búsqueda avanzada por múltiples campos
- 🔒 Sistema seguro con validaciones
- ⚙️ Panel de configuración del sistema

## 🚀 Funcionalidades
- **Gestión de Empleados**
  - Listado con paginación
  - Alta de nuevos empleados
  - Edición de datos
  - Eliminación segura
  - Búsqueda por nombre/apellido/DNI
  - Reportes

- **Panel de Configuración**
  - Configuración de base de datos
  - Datos de la empresa
  - Parámetros del sistema

## 📂 Estructura del Proyecto
```
gestion_empleados/
├── api/
│   ├── usuarios.php    # API REST para CRUD
│   └── search.php      # Endpoint de búsqueda
├── pages/
│   ├── principal.php   # Dashboard principal
│   ├── busqueda.php    # Sistema de búsqueda
│   └── configuracion.php# Panel de configuración
├── assets/
│   ├── css/           # Estilos CSS
│   └── js/            # Scripts JavaScript
├── config/
│   └── config.json    # Configuraciones del sistema
├── conexion.php       # Conexión a base de datos
├── index.php         # Punto de entrada
└── README.md         # Documentación
```

## 🛠️ Tecnologías Utilizadas
- **Backend**: PHP 8.2
- **Base de datos**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5
- **APIs**: REST, Fetch API
- **Herramientas**: Git, VS Code

## 📊 Estructura de Base de Datos
### Tabla: usuarios
| Campo           | Tipo         | Descripción               |
|----------------|--------------|---------------------------|
| id             | INT          | ID único autoincremental  |
| nombre         | VARCHAR(50)  | Nombre del empleado       |
| apellido       | VARCHAR(50)  | Apellidos del empleado    |
| dni            | VARCHAR(8)   | Documento de identidad    |
| direccion      | VARCHAR(100) | Dirección domiciliaria    |
| telefono       | VARCHAR(15)  | Número de contacto        |
| cargo          | VARCHAR(50)  | Cargo en la empresa       |
| responsabilidad| VARCHAR(100) | Área de responsabilidad   |
| usuario        | VARCHAR(50)  | Nombre de usuario sistema |

## ⚙️ Instalación y Configuración
1. **Clonar Repositorio**
   ```bash
   git clone https://github.com/usuario/gestion_empleados.git
   cd gestion_empleados
   ```

2. **Configurar Base de Datos**
   - Importar el archivo SQL de la base de datos `database.sql`
   - Configurar credenciales en `conexion.php`

3. **Configurar Servidor Web**
   - Copiar archivos a directorio web
   - Configurar permisos de escritura
   - Verificar requisitos de PHP

4. **Acceder al Sistema**
   ```
   http://localhost/gestion_empleados
   ```

## 💻 Guía de Uso
1. **Acceso al Sistema**
   - Abrir navegador web
   - Ingresar a la URL del sistema

2. **Gestión de Empleados**
   - Usar menú principal para navegación
   - Seguir formularios para operaciones CRUD

3. **Búsquedas**
   - Utilizar barra de búsqueda superior
   - Filtrar por nombre/apellido/DNI

4. **Configuración**
   - Acceder a panel de configuración
   - Ajustar parámetros según necesidad


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
- Validación de entradas
- Protección contra SQL Injection usando PDO
- Prevención de SQL Injection
- Validación de datos en formularios
- Control de accesos
- Sanitización de salidas HTML
- Manejo de errores y excepciones

## 👥 Equipo de Desarrollo - Grupo 5
- 👨‍💻 Estudiante 1: [PINARES MEJIA Jose Eriberto]
- 👨‍💻 Estudiante 2: [SOSA ORTIZ Rafael]
- 👨‍💻 Estudiante 3: [SENGUÑA CCANA Carlos]
