# JoJo Fandom Hub — Administración (CodeIgniter 4)

Plataforma web para seguidores de la franquicia *JoJo's Bizarre Adventure*. Este repositorio contiene la **base del sistema (autenticación con roles)** y el **módulo de administración** que alimenta el Core «Perfil de afinidad JoJo» definido en el Documento de Especificación Funcional.

## Enlaces

| Recurso | Enlace |
|---|---|
| Aplicación desplegada | https://crud-codeigniter-yixl.onrender.com |
| Video | https://youtu.be/6L3kbUTxo0Y |

> El plan gratuito de Render "duerme" el servicio tras un rato sin visitas: la primera carga puede tardar casi un minuto.

## 1. Descripción del proyecto

El sistema se desarrolla con el patrón **MVC (Modelo-Vista-Controlador)** sobre **CodeIgniter 4** y una base de datos relacional **MySQL**. El proyecto completo contempla:

- **Gestión de cuentas:** inicio de sesión con roles *Usuario* y *Administrador*.
- **Catálogo de personajes** por partes de la saga, con descripción e imágenes.
- **Catálogo de anime y géneros**, administrado por los administradores.
- **Core (Perfil de afinidad JoJo):** test de compatibilidad con personajes, perfil de intereses, recomendación de anime y de usuarios afines.

**Estado actual:** el módulo de administración (Tarea 8) está completo. Reúne los datos que consumirá el Core. La parte del usuario final (test, perfil y recomendaciones) se construirá en las siguientes entregas; por ahora un usuario con rol `USER` ve una página de bienvenida.

## 2. Tecnologías utilizadas

- **Framework:** CodeIgniter 4.7
- **Lenguaje:** PHP 8.2 o superior
- **Base de datos:** MySQL (local con XAMPP; en producción, Aiven for MySQL)
- **Arquitectura:** Modelo-Vista-Controlador (MVC)
- **Dependencias:** Composer
- **Frontend:** HTML, CSS y JavaScript sin librerías (tema morado y dorado en `public/css/jojo-theme.css`)
- **Despliegue:** Docker (PHP 8.2 + Apache) en Render
- **Control de versiones:** Git y GitHub

## 3. Roles y acceso

| Rol | Acceso |
|---|---|
| **ADMIN** | Panel de administración completo (`/admin`) |
| **USER** | Página de bienvenida (`/bienvenida`). Si intenta entrar a `/admin` recibe un error 403 |
| Visitante | Solo `/login`. Cualquier otra ruta lo redirige al login |

## 4. Módulo de administración

| Módulo | Ruta | Qué hace |
|---|---|---|
| Inicio | `/admin` | Contadores del sistema y orden recomendado de carga |
| Partes | `/admin/partes` | CRUD de las partes de la saga |
| Personajes | `/admin/personajes` | CRUD con imagen; cada personaje pertenece a una parte |
| Géneros | `/admin/generos` | CRUD de géneros |
| Anime | `/admin/animes` | CRUD con portada y relación N:M con géneros |
| Test | `/admin/preguntas` | CRUD de preguntas, opciones y **pesos 0-3** hacia personajes y géneros |
| Usuarios | `/admin/usuarios` | Listar, crear, cambiar rol o contraseña y eliminar usuarios |

### Cómo alimenta el Core cada módulo

- **Preguntas, opciones y pesos** → cálculo de compatibilidad con personajes y géneros del test.
- **Partes y personajes** → resultado del test (personaje principal con imagen).
- **Géneros y anime** → recomendación de anime por coincidencia de géneros.
- **Usuarios y roles** → autorización y, más adelante, usuarios afines.

## 5. Seguridad y validaciones en back-end

Todas las validaciones se ejecutan **en el servidor**, antes de guardar en la base de datos; el HTML y el JavaScript solo son una ayuda visual.

**Autenticación y autorización**

- Filtros de CodeIgniter: `auth` (sesión iniciada), `admin` (rol ADMIN) y `csrf` (token en todos los formularios POST).
- Contraseñas con `password_hash()` y `password_verify()`.
- Las acciones destructivas usan POST, no enlaces GET.

**Datos sensibles del Core**

| Dato | Validación |
|---|---|
| Peso de una opción de respuesta | `in_list[0,1,2,3]` (alimenta las fórmulas de compatibilidad) |
| Calificación (rating) del anime | numérica entre 0 y 10 (desempata las recomendaciones) |
| Activar una pregunta del test | mínimo 2 opciones y pesos de personajes en cada opción; si deja de cumplirse se desactiva sola |
| Imágenes | `is_image`, `mime_in` (JPG, PNG, WEBP), máximo 2 MB y nombre aleatorio |
| Nombre de género, número de parte y usuario | únicos (`is_unique`) y, además, `UNIQUE` en la base de datos |

**Cuentas y permisos**

- Contraseña: mínimo 8 caracteres, con mayúscula, minúscula y número.
- Rol: solo `ADMIN` o `USER` (`in_list`).
- Un administrador no puede quitarse su propio rol ni eliminar su propia cuenta.
- Siempre debe existir al menos un administrador.

**Claves foráneas sin inputs manuales**

- Todas se eligen en desplegables o casillas de selección, y el servidor comprueba con `is_not_unique[tabla.id]` que el registro exista.
- **Dropdown dependiente Parte → Personaje:** al asignar pesos se elige primero la parte y el segundo desplegable se llena por AJAX (`GET /admin/api/partes/{id}/personajes`, respuesta JSON). El servidor vuelve a comprobar que el personaje pertenezca a la parte elegida.

## 6. Estructura del proyecto

```text
crud-codeigniter/
├── app/
│   ├── Config/            Routes.php, Filters.php, Database.php ...
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── BienvenidaController.php
│   │   └── Admin/         Dashboard, Parte, Personaje, Genero, Anime,
│   │                      Pregunta, Opcion, Peso y Usuario
│   ├── Database/
│   │   ├── Migrations/    tablas de usuarios y del Core
│   │   └── Seeds/         CoreSeeder y AdminSeeder
│   ├── Filters/           AuthFilter.php y AdminFilter.php
│   ├── Language/es/       mensajes de validación en español
│   ├── Models/            Usuario, Parte, Personaje, Genero, Anime, Pregunta, Opcion
│   └── Views/
│       ├── auth/login.php
│       ├── bienvenida.php
│       └── admin/         layout y vistas de cada módulo
├── docker/entrypoint.sh
├── public/
│   ├── css/jojo-theme.css
│   └── uploads/           personajes/ y animes/
├── Dockerfile
├── DEPLOY.md
└── composer.json
```

## 7. Modelo de datos

Basado en el Diagrama Entidad-Relación aprobado. Tablas creadas por las migraciones:

`usuarios` (con columna `rol`), `jojo_parts`, `characters`, `character_images`, `genres`, `animes`, `anime_genres`, `quiz_questions`, `quiz_options`, `option_character_weights` y `option_genre_weights`.

Nota: `animes.rating` se define como `DECIMAL(4,2)` (y no `(3,2)`) para poder guardar la calificación 10.00.

## 8. Instalación en local

### Requisitos

- PHP 8.2 o superior (extensiones `intl`, `mysqli` y `mbstring`)
- Composer
- MySQL (por ejemplo con XAMPP)

### Pasos

```bash
# 1. Clonar e instalar dependencias
git clone https://github.com/valemolina567/crud-codeigniter.git
cd crud-codeigniter
composer install

# 2. Crear el archivo .env (copia de "env") y configurar la base de datos
#    database.default.hostname = 127.0.0.1
#    database.default.database = ingenieria_web
#    database.default.username = root
#    database.default.password =
#    database.default.DBDriver = MySQLi
#    database.default.port = 3306
```

3. En phpMyAdmin, crea una base de datos vacía llamada `ingenieria_web`.

```bash
# 4. Crear las tablas
php spark migrate

# 5. Crear el usuario administrador (elige tu propia contraseña)
#    Windows PowerShell:
$env:ADMIN_PASSWORD = "TuClave123"
php spark db:seed AdminSeeder
#    Linux / macOS:
#    ADMIN_PASSWORD=TuClave123 php spark db:seed AdminSeeder

# 6. (Opcional) Datos de ejemplo: 8 partes, 7 géneros y 6 personajes
php spark db:seed CoreSeeder

# 7. Iniciar el servidor
php spark serve
```

La aplicación queda disponible en http://localhost:8080 (redirige a `/login`). Entra con el usuario `admin` y la contraseña definida en `ADMIN_PASSWORD`.

> No publiques contraseñas reales ni el archivo `.env` en el repositorio.

## 9. Despliegue

La aplicación está desplegada en **Render** (contenedor Docker) con la base de datos en **Aiven for MySQL**. Cada `git push` a `main` redespliega automáticamente y el arranque ejecuta `php spark migrate`. Las variables de entorno necesarias y los pasos están en [DEPLOY.md](DEPLOY.md).

Consideraciones:

- En Render el disco es efímero: las imágenes subidas se pierden en cada despliegue.
- Los datos de producción (Aiven) y los locales (XAMPP) son bases distintas: lo que se carga en un entorno no aparece en el otro.

## 10. Pruebas realizadas

- Inicio de sesión con credenciales correctas e incorrectas, y mensaje de error.
- Redirección al login al intentar entrar a rutas protegidas sin sesión.
- Acceso al panel como ADMIN y error 403 para un usuario con rol USER.
- CRUD completo de partes, personajes (con imagen), géneros, anime (con géneros) y usuarios.
- Creación de preguntas con opciones y pesos mediante el dropdown dependiente Parte → Personaje.
- Rechazo en el servidor de pesos fuera de 0-3, ratings inválidos, roles inexistentes y contraseñas débiles.
- Rechazo de nombres de usuario duplicados.
- Intento de activar una pregunta incompleta.
- Intento de eliminar la propia cuenta o al único administrador.
- Despliegue en Render y verificación de las migraciones en Aiven.

## 11. Autoría

Proyecto académico de **Ingeniería Web** — Universidad de las Américas.

**Autora:** Valeria Molina · **Docente:** Juan León.
