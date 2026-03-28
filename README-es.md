# Flock Twitter Clone

Clon funcional de Twitter/X desarrollado como challenge técnico full-stack con **Laravel 13**, **Vue 3**, **Inertia.js**, **Tailwind CSS 4** y **SQLite**.

La aplicación implementa autenticación propia, timeline autenticado, publicación y eliminación de tweets, likes, follow/unfollow, perfiles de usuario, búsqueda de usuarios, seed realista y una suite de tests backend con **94.5% de cobertura**.

---

## 1. Objetivo del proyecto

Este proyecto fue construido para resolver el challenge **Twitter Clone** de The Flock, priorizando:

- funcionalidad completa end-to-end
- claridad de arquitectura
- setup simple y reproducible
- testing fuerte en backend
- código mantenible y fácil de revisar
- commits progresivos y documentación operativa

La idea fue implementar un producto usable, sin sobre-ingeniería, con un stack que permitiera iterar rápido y mantener una buena calidad técnica.

---

## 2. Stack elegido y justificación

### Backend
- **Laravel 13**
- **Laravel Fortify** para autenticación propia
- **SQLite** como base de datos relacional
- **Pest** para testing

### Frontend
- **Vue 3**
- **Inertia.js**
- **Vite**
- **Tailwind CSS 4**
- componentes UI del starter kit de Laravel/Vue

### ¿Por qué Laravel + Vue + SQLite?

Se eligió esta combinación por motivos prácticos y técnicos:

#### Laravel 13
- Permite construir un backend sólido con validaciones, middleware, sesiones, rutas protegidas y Eloquent con muy poca fricción.
- Facilita la autenticación propia sin depender de terceros, que era un requisito explícito del challenge.
- Tiene excelente soporte para testing, factories, seeders y convenciones muy claras.

#### Vue 3 + Inertia
- Permite construir una interfaz moderna sin separar el frontend y el backend en dos proyectos distintos.
- Reduce complejidad operativa y acelera el desarrollo.
- Es una muy buena opción cuando se quiere una experiencia tipo SPA sin perder simplicidad en el stack.

#### SQLite
- Fue elegido para minimizar fricción de instalación y favorecer un runbook reproducible.
- Es una base de datos relacional perfectamente válida para el scope del challenge.
- Permite clonar el repo, correr migraciones y seed, y ver contenido inmediatamente.

En resumen: el stack no fue elegido por moda, sino por **velocidad de ejecución, mantenibilidad, facilidad de setup y capacidad de cubrir bien el challenge dentro del tiempo disponible**.

---

## 3. Funcionalidades implementadas

### Autenticación
- registro de usuario
- login / logout
- rutas protegidas por sesión
- perfil básico con:
  - nombre
  - username único
  - bio
  - avatar placeholder basado en iniciales

### Tweets
- creación de tweet
- validación de máximo 280 caracteres
- rechazo de tweets vacíos o con solo espacios
- eliminación de tweet propio
- timeline autenticado
- paginación

### Interacciones sociales
- follow / unfollow
- like / unlike
- contador visible de likes
- relación followers / following en el perfil

### Búsqueda
- búsqueda de usuarios por nombre o username

### UX / UI
- diseño responsive
- layout mobile-first
- interfaz usable en mobile y desktop

### Datos de prueba
- seed con 10 usuarios
- tweets, follows y likes cruzados
- usuario demo listo para probar

### Testing
- unit tests
- feature tests
- cobertura backend: **94.5%**

---

## 4. Decisiones técnicas importantes

### Timeline
El timeline se resuelve **on-read** con una consulta que combina:
- tweets del usuario autenticado
- tweets de los usuarios que sigue

Ventajas de esta decisión:
- simple de explicar
- suficiente para el volumen del challenge
- evita complejidad innecesaria como fan-out on write o colas de feed

### Grafo social
La relación de follows se modeló con una tabla dirigida:
- `follower_id`
- `followed_id`

Esto permite representar correctamente la relación “A sigue a B” y facilita consultas de timeline, perfiles y listados sociales.

### Autenticación
Se utilizó **Laravel Fortify** con autenticación propia basada en sesión. No se usaron soluciones de terceros como Firebase Auth o Supabase Auth.

### Validaciones
Las validaciones clave se mantuvieron del lado del backend para garantizar integridad, incluso si el frontend cambia.

### Testing
La estrategia de testing se enfocó en:
- modelos y relaciones
- validaciones
- endpoints críticos
- timeline
- follows / likes / tweets
- perfil y búsqueda
- seed data

---

## 5. Estructura del proyecto

```text
app/
  Actions/
    Fortify/
    Timeline/
  Concerns/
  Http/
    Controllers/
    Middleware/
    Requests/
  Models/
  Providers/

database/
  factories/
  migrations/
  seeders/

resources/js/
  components/
    tweets/
    users/
    shared/
    ui/
  layouts/
  pages/
    auth/
    Profile/
    Search/
    settings/
  routes/
  composables/

routes/
  web.php
  settings.php

tests/
  Unit/
  Feature/
```

### Carpetas principales

#### `app/`
Contiene la lógica de negocio del backend:
- controladores
- modelos
- actions
- middleware
- requests
- providers

#### `database/`
Contiene:
- migraciones
- factories
- seeders
- archivo SQLite local

#### `resources/js/`
Contiene el frontend en Vue:
- páginas
- layouts
- componentes reutilizables
- componentes de tweets y usuarios

#### `routes/`
Define las rutas HTTP principales y las rutas de settings.

#### `tests/`
Contiene la suite de tests backend, separada en Unit y Feature.

---

## 6. Requisitos del entorno

Este proyecto fue preparado para correr localmente en Linux Ubuntu 22.04, pero puede correr en otros entornos equivalentes.

### Versiones recomendadas
- **PHP 8.3+**
- **Composer 2.8+**
- **Node.js 22+**
- **npm 10+**
- **SQLite 3+**

### Dependencias PHP principales
- `laravel/framework ^13.0`
- `laravel/fortify ^1.34`
- `inertiajs/inertia-laravel ^3.0`
- `laravel/wayfinder ^0.1.14`
- `laravel/tinker ^3.0`

### Dependencias de desarrollo principales
- `pestphp/pest ^4.4`
- `pestphp/pest-plugin-laravel ^4.1`
- `laravel/pint ^1.27`
- `laravel/pail ^1.2.5`
- `vite ^8`
- `vue ^3.5`
- `tailwindcss ^4.1`

---

## 7. Variables de entorno

El proyecto incluye `.env.example`.

Variables importantes:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log
```

### Notas
- La conexión por defecto usa **SQLite**.
- Si no defines `DB_DATABASE`, Laravel usará `database/database.sqlite`.
- `MAIL_MAILER=log` evita depender de un proveedor SMTP real para desarrollo.

---

## 8. Instalación paso a paso

### Opción recomendada

```bash
git clone https://github.com/torvictorvic/ftwitter.git
cd ftwitter
cp .env.example .env
mkdir -p database
touch database/database.sqlite
composer install
php artisan key:generate
npm install
php artisan migrate:fresh --seed
```

### Alternativa usando script de Composer

El proyecto incluye un script `setup`:

```bash
composer setup
```

Luego ejecuta el seed manualmente:

```bash
php artisan migrate:fresh --seed
```

> Nota: `composer setup` instala dependencias, genera la key, corre migraciones y compila assets, pero no ejecuta el seed completo del challenge.

---

## 9. Cómo levantar el proyecto en desarrollo

### Opción recomendada

```bash
composer run dev
```

Ese comando levanta en paralelo:
- servidor Laravel
- listener de queue
- tail de logs con Laravel Pail
- Vite dev server

### URLs habituales
- App Laravel: `http://127.0.0.1:8000`
- Vite dev server: `http://localhost:5173`

### Alternativa manual

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
npm run dev
```

---

## 10. Usuario demo

Después de correr el seed puedes usar estas credenciales:

```text
Email: demo@example.com
Password: password
Username: demouser
```

---

## 11. Cómo correr migraciones y seed

### Migrar la base de datos

```bash
php artisan migrate
```

### Resetear todo y poblar con datos demo

```bash
php artisan migrate:fresh --seed
```

Esto crea:
- 10 usuarios
- tweets por usuario
- follows cruzados
- likes cruzados
- un usuario demo claro para pruebas manuales

---

## 12. Cómo correr los tests

### Suite backend completa

```bash
./vendor/bin/pest tests/Unit tests/Feature
```

### También puedes usar el script del proyecto

```bash
composer test
```

Ese comando ejecuta:
1. limpieza de configuración
2. validación de estilo con Pint
3. suite de tests con `php artisan test`

### Formateo de código PHP

```bash
./vendor/bin/pint
```

### Verificación de estilo sin modificar archivos

```bash
./vendor/bin/pint --test
```

---

## 13. Cobertura de tests

La cobertura backend alcanzada actualmente es:

```text
Total backend coverage: 94.5%
```

### Para generar cobertura con PCOV

Primero verifica si tienes PCOV disponible:

```bash
php -m | grep pcov
```

Si no lo tienes instalado en Ubuntu:

```bash
sudo apt install php8.3-pcov
```

Luego ejecuta:

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage
```

### Generar reporte HTML de cobertura

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage-html coverage-report
```

Luego puedes abrir el reporte en `coverage-report/index.html`.

---

## 14. Scripts útiles del proyecto

### Composer

```bash
composer setup
composer run dev
composer test
```

### NPM

```bash
npm install
npm run dev
npm run build
npm run lint
npm run lint:check
npm run format
npm run format:check
npm run types:check
```

---

## 15. Rutas principales

### Aplicación social
- `GET /` → timeline autenticado
- `POST /tweets` → crear tweet
- `DELETE /tweets/{tweet}` → eliminar tweet propio
- `POST /tweets/{tweet}/likes` → like
- `DELETE /tweets/{tweet}/likes` → unlike
- `GET /users/search` → búsqueda de usuarios
- `GET /users/{username}` → perfil de usuario
- `POST /users/{username}/follow` → follow
- `DELETE /users/{username}/follow` → unfollow

### Settings
- `GET /settings/profile`
- `PATCH /settings/profile`
- `DELETE /settings/profile`
- `GET /settings/security`
- `PUT /settings/password`
- `GET /settings/appearance`

---

## 16. Librerías y herramientas principales

### Backend
- **Laravel 13**
- **Fortify** para auth propia
- **Inertia Laravel**
- **Wayfinder** para rutas tipadas en frontend
- **Tinker** para inspección rápida del estado de la app
- **Pail** para logs en desarrollo

### Frontend
- **Vue 3**
- **Inertia.js**
- **Vite**
- **Tailwind CSS 4**
- **Lucide Vue** para iconos
- **VueUse** para utilidades reactivas
- **TypeScript**

### Calidad / Testing
- **Pest**
- **Pest Laravel Plugin**
- **Pint**
- **ESLint**
- **Prettier**
- **vue-tsc**

---

## 17. Cómo inspeccionar la base de datos SQLite

Puedes abrir la base de datos local así:

```bash
sqlite3 database/database.sqlite
```

Ejemplos útiles dentro de SQLite:

```sql
.tables
select id, name, username, email from users;
select id, user_id, body, created_at from tweets limit 20;
select follower_id, followed_id from follows limit 20;
select user_id, tweet_id from likes limit 20;
.quit
```

También puedes usar Tinker:

```bash
php artisan tinker
```

Ejemplos:

```php
App\Models\User::count();
App\Models\Tweet::count();
App\Models\User::select('id', 'name', 'username', 'email')->get();
```

---

## 18. Trade-offs y limitaciones conocidas

- Se priorizó **SQLite** por simplicidad operativa y reproducibilidad local.
- El timeline está diseñado para el volumen del challenge, no para una arquitectura de feed masiva de producción.
- Se eligió **paginación clásica** en lugar de infinite scroll para reducir complejidad y riesgo.
- No se implementaron features bonus como imágenes, websockets o notificaciones para no sacrificar estabilidad del core.
- Algunas capacidades opcionales del starter kit, como flujos más avanzados de seguridad, no son el foco principal de este challenge.

---

## 19. Uso de AI durante el desarrollo

El desarrollo de este proyecto fue realizado con una combinación de:

- **conocimientos propios** del autor
- **ChatGPT / Codex (OpenAI ChatGPT)**
- asistencia desde **Visual Studio Code**

La AI se utilizó como apoyo para:
- acelerar scaffolding
- revisar validaciones
- proponer tests
- mejorar naming y estructura
- depurar errores puntuales
- fortalecer el runbook y la documentación

Todas las decisiones finales, correcciones y validaciones fueron revisadas manualmente antes de consolidarse en el proyecto.

---

## 20. Resumen técnico

Este proyecto entrega un clon funcional de Twitter/X con un stack full-stack moderno, simple de correr y bien testeado.

Puntos destacados:
- Laravel 13 + Vue 3 + Inertia
- SQLite para setup rápido
- autenticación propia
- timeline, follows, likes, perfiles y búsqueda
- seed realista
- testing backend fuerte
- **94.5% de cobertura**
- runbook claro y reproducible

---

## 21. Comandos rápidos

### Setup completo

```bash
git clone https://github.com/torvictorvic/ftwitter.git
cd ftwitter
cp .env.example .env
touch database/database.sqlite
composer install
php artisan key:generate
npm install
php artisan migrate:fresh --seed
composer run dev
```

### Test rápido

```bash
./vendor/bin/pest tests/Unit tests/Feature
```

### Coverage

```bash
php -d pcov.enabled=1 -d pcov.directory=app ./vendor/bin/pest tests/Unit tests/Feature --coverage
```

---

## 22. Autor

Desarrollado por **Victor Manuel Suarez Torres**.

