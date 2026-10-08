# CRUD de Personas con Login — CodeIgniter 4

## 1. Descripción del proyecto

Esta aplicación web permite gestionar un registro de personas mediante las operaciones CRUD (Crear, Leer, Actualizar y Eliminar). Fue desarrollada con **CodeIgniter 4**, utilizando el patrón arquitectónico **MVC (Modelo-Vista-Controlador)** y una base de datos MySQL.

Además, incorpora un sistema de autenticación mediante usuario y contraseña, protección de rutas y almacenamiento seguro de contraseñas mediante funciones de hash de PHP.

El objetivo del proyecto es aplicar los fundamentos del desarrollo web con MVC, la conexión con una base de datos y los mecanismos básicos de autenticación y autorización.

## 2. Tecnologías utilizadas

* **Framework:** CodeIgniter 4.
* **Lenguaje de programación:** PHP 8.2 o superior.
* **Base de datos:** MySQL.
* **Arquitectura:** Modelo-Vista-Controlador (MVC).
* **Gestor de dependencias:** Composer.
* **Servidor de desarrollo:** servidor integrado de CodeIgniter.
* **Frontend:** HTML y CSS.

## 3. Funcionalidades

### Gestión de personas (CRUD)

* Registrar nuevas personas.
* Consultar la lista de personas registradas.
* Editar los datos de una persona.
* Eliminar registros.
* Subir imágenes al registrar una persona.
* Visualizar y actualizar las imágenes de las personas.

### Sistema de autenticación

* Inicio de sesión con usuario y contraseña.
* Validación de las credenciales ingresadas.
* Mensaje de error cuando el usuario o la contraseña son incorrectos.
* Protección de las rutas del CRUD mediante un filtro de autenticación.
* Cierre de sesión.
* Almacenamiento de contraseñas mediante `password_hash()` y verificación con `password_verify()`.

## 4. Arquitectura MVC

El proyecto utiliza el patrón MVC para separar las responsabilidades de la aplicación.

* **Modelo (Model):** gestiona el acceso a los datos de MySQL.
* **Vista (View):** contiene las interfaces del login, el listado de personas y los formularios de registro y edición.
* **Controlador (Controller):** procesa las solicitudes, valida las credenciales y coordina las operaciones entre los modelos y las vistas.

### Estructura principal del proyecto

```text
crud-codeigniter/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── PersonaController.php
│   ├── Filters/
│   │   └── AuthFilter.php
│   ├── Models/
│   │   ├── PersonaModel.php
│   │   └── UsuarioModel.php
│   ├── Views/
│   │   ├── auth/
│   │   │   └── login.php
│   │   └── personas/
│   │       ├── index.php
│   │       ├── crear.php
│   │       └── editar.php
│   └── Config/
│       ├── Routes.php
│       └── Filters.php
├── public/
│   └── uploads/
│       └── personas/
├── writable/
├── .env
├── composer.json
└── README.md
```

*Nota: la estructura puede variar ligeramente según los archivos generados por CodeIgniter.*

## 5. Requisitos previos

Para ejecutar el proyecto se necesita:

* PHP 8.2 o superior.
* Composer.
* MySQL.
* CodeIgniter 4.
* Un navegador web.

Se puede utilizar XAMPP para iniciar el servicio de MySQL y administrar la base de datos mediante phpMyAdmin.

## 6. Instalación y configuración

### Paso 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
cd crud-codeigniter
```

Reemplaza `URL_DEL_REPOSITORIO` por la dirección real de tu repositorio.

### Paso 2. Instalar las dependencias

```bash
composer install
```

### Paso 3. Configurar el entorno

Crea el archivo `.env` a partir de `env` si todavía no existe y configura la conexión a MySQL.

Ejemplo para un entorno local de XAMPP:

```ini
database.default.hostname = localhost
database.default.database = ingenieria_web
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

Ajusta los datos de conexión según la configuración de tu equipo. No publiques contraseñas reales ni credenciales privadas en el repositorio.

### Paso 4. Crear la base de datos

En phpMyAdmin, crea una base de datos llamada `ingenieria_web`.

Después, ejecuta el siguiente SQL para crear la tabla de personas:

```sql
CREATE TABLE personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    imagen VARCHAR(255) NOT NULL
);
```

Crea también la tabla de usuarios para el login:

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

### Paso 5. Crear el usuario inicial

Genera un hash de contraseña con PHP:

```bash
php -r "echo password_hash('Admin123!', PASSWORD_DEFAULT), PHP_EOL;"
```

Copia el hash generado y ejecuta en phpMyAdmin, sustituyendo `PEGAR_HASH_AQUI` por el valor obtenido:

```sql
INSERT INTO usuarios (usuario, password)
VALUES ('admin', 'PEGAR_HASH_AQUI');
```

Este procedimiento guarda el hash de la contraseña en la base de datos, en lugar de almacenar la contraseña original como texto.

**Importante:** la cuenta anterior es únicamente para pruebas. En un entorno real se debe configurar una contraseña propia y mantener las credenciales protegidas.

### Paso 6. Preparar la carpeta de imágenes

Comprueba que exista la siguiente carpeta:

```text
public/uploads/personas/
```

Debe tener permisos de escritura para que la aplicación pueda guardar las imágenes cargadas desde los formularios.

### Paso 7. Iniciar el servidor

Desde la carpeta del proyecto, ejecuta:

```bash
php spark serve
```

Por defecto, la aplicación estará disponible en:

http://localhost:8080

## 7. Acceso a la aplicación

### Login

http://localhost:8080/login

Credenciales de prueba, si se creó el usuario inicial descrito anteriormente:

* Usuario: `admin`
* Contraseña: `Admin123!`

### CRUD de personas

http://localhost:8080/personas

El listado de personas requiere iniciar sesión previamente.

## 8. Seguridad y protección de rutas

La aplicación utiliza un filtro de autenticación para comprobar que exista una sesión válida antes de permitir el acceso a las rutas protegidas.

Las rutas del CRUD están restringidas a usuarios autenticados. Si una persona intenta acceder directamente a ellas sin iniciar sesión, será redirigida al formulario de login.

Las contraseñas se procesan con las funciones nativas de PHP:

* `password_hash()`: genera un hash seguro de la contraseña.
* `password_verify()`: comprueba la contraseña ingresada contra el hash almacenado.

Este mecanismo es preferible a MD5 para almacenar contraseñas.

## 9. Pruebas realizadas

Las siguientes comprobaciones permiten verificar el funcionamiento de la aplicación:

* Acceso al login.
* Validación de credenciales correctas e incorrectas.
* Visualización del mensaje de error de autenticación.
* Bloqueo del acceso al CRUD sin iniciar sesión.
* Registro, consulta, edición y eliminación de personas.
* Carga y actualización de imágenes.
* Cierre de sesión y comprobación de las rutas protegidas.

## 10. Autoría

Proyecto académico desarrollado para aplicar los conceptos de desarrollo web con el patrón MVC, operaciones CRUD, autenticación y conexión con una base de datos relacional.

**Framework utilizado:** CodeIgniter 4.

**Base de datos:** MySQL.
