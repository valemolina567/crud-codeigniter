# Despliegue en Render + MySQL en Aiven

## 1. Base de datos (Aiven, plan gratuito)
1. Crear cuenta en https://aiven.io y un servicio **MySQL** con el plan *Free*.
2. Cuando esté en estado *Running*, copiar de "Connection information": **Host, Port, User, Password, Database** (`defaultdb`).

## 2. Servicio web (Render)
1. En https://render.com: **New → Web Service** → conectar el repositorio de GitHub.
2. **Language/Runtime: Docker** (detecta el `Dockerfile` de la raíz). Rama `main`.
3. En **Environment** agregar:

| Variable | Valor |
|---|---|
| `APP_BASE_URL` | `https://NOMBRE-DEL-SERVICIO.onrender.com/` (con la barra final) |
| `DB_HOST` | host de Aiven |
| `DB_PORT` | puerto de Aiven |
| `DB_NAME` | `defaultdb` |
| `DB_USER` | usuario de Aiven (`avnadmin`) |
| `DB_PASSWORD` | contraseña de Aiven |
| `DB_SSL` | `1` |
| `ADMIN_PASSWORD` | contraseña que tendrá el usuario `admin` |
| `SEED` | `1` solo la primera vez (datos de ejemplo); luego se puede quitar |

4. **Create Web Service**. Cada `git push` a `main` redespliega y el arranque ejecuta `php spark migrate`.

## 3. Notas
- Las imágenes subidas se guardan en disco efímero: se pierden en cada redeploy.
- Un servicio gratuito de Render se "duerme" tras un rato sin visitas; la primera carga tarda.
