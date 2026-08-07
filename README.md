# Bingo SaaS — Base Profesional del Sistema (Etapa 1)

Sistema profesional de Bingo Online tipo SaaS. Esta primera etapa entrega
la **base arquitectonica completa**: estructura MVC, conexion a base de
datos, sistema de rutas, autenticacion, control de sesiones, middleware
de seguridad y la plantilla base del panel administrativo.

---

## 🚀 Despliegue automatico en Railway (recomendado — sin cPanel)

Esta es la forma **mas simple** de poner el sistema en internet: conectas
tu cuenta de GitHub, Railway detecta el proyecto solo, crea el servidor
PHP y la base de datos MySQL automaticamente, y te entrega un dominio
con HTTPS ya activo. No se toca ningun panel tipo cPanel.

El proyecto ya trae todo lo necesario para esto (`Dockerfile`,
`docker-entrypoint.sh`, `railway.json`), asi que **no tienes que tocar
nada tecnico**, solo seguir estos pasos:

### Paso 1 — Sube el proyecto a GitHub
1. Crea una cuenta gratis en [github.com](https://github.com) si no tienes una.
2. Crea un repositorio nuevo (por ejemplo `bingo-saas`).
3. Sube ahi TODA la carpeta `bingo-saas` que te entregue (puedes arrastrar
   los archivos directamente desde la pagina de GitHub, boton
   "uploading an existing file", sin necesidad de usar la terminal).

### Paso 2 — Crea el proyecto en Railway
1. Entra a [railway.app](https://railway.app) y crea una cuenta (puedes
   registrarte directamente con tu cuenta de GitHub).
2. Clic en **"New Project"** → **"Deploy from GitHub repo"**.
3. Selecciona el repositorio `bingo-saas` que acabas de subir.
4. Railway detecta el `Dockerfile` automaticamente y empieza a construir
   el sistema solo. No necesitas configurar nada en este paso.

### Paso 3 — Agrega la base de datos MySQL
1. Dentro de tu proyecto en Railway, clic en **"New"** → **"Database"** → **"Add MySQL"**.
2. Listo. Railway crea la base de datos y **conecta las credenciales
   automaticamente** con tu aplicacion (el sistema ya esta preparado
   para detectarlas solo, no hay que copiar usuario/contrasena a mano).

### Paso 4 — Importa las tablas iniciales
1. Dentro del servicio MySQL que creaste, ve a la pestana **"Data"** (o
   conectate con la cadena de conexion que te muestra Railway usando
   una app como TablePlus, DBeaver o MySQL Workbench).
2. Ejecuta el contenido del archivo `database/bingo_saas.sql` que viene
   en el proyecto (crea las tablas y el usuario Super Administrador).

### Paso 5 — Genera el dominio publico
1. Entra al servicio de tu aplicacion (no el de MySQL) dentro de Railway.
2. Ve a la pestana **"Settings"** → **"Networking"** → **"Generate Domain"**.
3. Railway te entrega una direccion como `bingo-saas-production.up.railway.app`,
   ya con HTTPS activo (necesario para poder instalar la app en el celular).

### Paso 6 — Entra y listo
Abre la direccion que Railway te dio en el navegador de tu celular,
tablet o PC → inicia sesion con `admin@bingosaas.com` / `Admin123!` →
aparecera el boton para **instalar como app**.

> Cuando quieras usar tu propio dominio (por ejemplo `www.mibingo.com`),
> en la misma pestana "Networking" hay una opcion **"Custom Domain"**
> donde lo agregas sin salir de Railway.

---

---

## Instalacion en hosting tradicional (cPanel)

Esta guia es para cuando prefieras un hosting compartido clasico en vez
de Railway (Hostinger, HostGator, cPanel, etc.).

### 1. Requisitos

- PHP 8.0 o superior (extensiones `pdo_mysql`, `mbstring`, `openssl`)
- MySQL 8+ o MariaDB 10.4+
- Apache con `mod_rewrite` habilitado (o Nginx equivalente)
- Servidor configurado con **Document Root apuntando a `/public`**

---

## 2. Estructura de carpetas

```
bingo-saas/
├── app/
│   ├── config/
│   │   ├── config.php        Configuracion general (constantes, rutas, roles)
│   │   ├── database.php      Conexion PDO (Singleton)
│   │   └── session.php       Configuracion segura de sesiones
│   ├── core/
│   │   ├── Env.php           Cargador de variables de entorno (.env)
│   │   ├── Router.php        Sistema de rutas (MVC)
│   │   ├── Controller.php    Controlador base
│   │   └── Model.php         Modelo base (acceso PDO seguro)
│   ├── controllers/
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   ├── models/
│   │   ├── UsuarioModel.php
│   │   └── ClienteModel.php
│   ├── middlewares/
│   │   └── AuthMiddleware.php    Control de sesion y de roles
│   ├── helpers/
│   │   ├── SecurityHelper.php    CSRF, saneo de datos, anti fuerza-bruta
│   │   └── AuthHelper.php        Manejo de la sesion del usuario
│   └── views/
│       ├── layouts/              header, sidebar, topbar, footer, scripts
│       ├── auth/login.php
│       ├── dashboard/index.php
│       └── errors/ (403, 404)
├── public/                       DOCUMENT ROOT del servidor
│   ├── index.php                 Front Controller (punto de entrada unico)
│   ├── .htaccess                 Reescritura de URLs
│   └── assets/
│       ├── css/style.css         Tema visual (azul, dorado, blanco, negro)
│       ├── js/main.js
│       └── img/bg-pattern.svg    Fondo decorativo (cartones y bolas de bingo)
├── database/
│   └── bingo_saas.sql            Script de base de datos inicial
├── storage/
│   └── logs/                     Logs de errores de PHP
├── .env.example                  Plantilla de variables de entorno
├── .htaccess                     Bloquea acceso fuera de /public
└── README.md
```

---

## 3. Instalacion paso a paso

### 3.1. Base de datos
1. Crear la base de datos ejecutando el script:
   ```bash
   mysql -u root -p < database/bingo_saas.sql
   ```
   Esto crea la base `bingo_saas`, las tablas `clientes`, `usuarios`,
   `logs_actividad`, y un usuario **Super Administrador** inicial.

### 3.2. Variables de entorno
2. Copiar `.env.example` como `.env` en la raiz del proyecto y completar
   los datos reales de conexion:
   ```bash
   cp .env.example .env
   ```
   Editar `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` y `APP_URL` segun tu entorno.

### 3.3. Servidor web
3. Configurar el **Document Root apuntando a la carpeta `public/`** (no a la
   raiz del proyecto). Ejemplo de VirtualHost en Apache:
   ```apache
   <VirtualHost *:80>
       ServerName bingosaas.local
       DocumentRoot "/ruta/al/proyecto/bingo-saas/public"
       <Directory "/ruta/al/proyecto/bingo-saas/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   Si no se puede cambiar el Document Root (hosting compartido), el
   `.htaccess` de la raiz redirige automaticamente todo el trafico
   hacia `/public`.

### 3.4. Acceso inicial
4. Ingresar a `http://tu-dominio/auth/login` con las credenciales:
   - **Correo:** `admin@bingosaas.com`
   - **Contrasena:** `Admin123!`

   > **IMPORTANTE:** Cambiar esta contrasena inmediatamente en un entorno
   > de produccion. El modulo de gestion de usuarios/perfil se entregara
   > en una etapa posterior.

   Si al validar el login el hash del script SQL no es reconocido por tu
   version de PHP (poco probable, ya que usa bcrypt estandar `$2b$`),
   puedes regenerarlo ejecutando en PHP:
   ```php
   <?php echo password_hash('Admin123!', PASSWORD_DEFAULT);
   ```
   y reemplazando el valor de la columna `password` del usuario admin.

---

## 4. Seguridad implementada en esta etapa

- Contrasenas con **hash bcrypt** (`password_hash` / `password_verify`).
- Sentencias preparadas (**PDO** con `ATTR_EMULATE_PREPARES = false`) en
  todos los modelos, previniendo inyeccion SQL.
- **Proteccion CSRF** en el formulario de login mediante token de un solo
  uso por sesion.
- **Control de fuerza bruta**: bloqueo temporal tras 5 intentos fallidos
  de inicio de sesion (5 minutos).
- **Cookies de sesion seguras**: `HttpOnly`, `SameSite=Lax` y `Secure`
  automatico si el sitio corre bajo HTTPS.
- Regeneracion periodica del ID de sesion (mitiga *session fixation*).
- Cierre de sesion automatico por inactividad prolongada.
- **Middleware de autenticacion y de roles** (`AuthMiddleware`) reutilizable
  en cualquier controlador.
- Cabeceras HTTP de seguridad (`X-Frame-Options`, `X-Content-Type-Options`,
  `Referrer-Policy`).
- Archivos sensibles (`.env`, `.sql`, `.log`) bloqueados via `.htaccess`.
- El directorio `app/` completo queda fuera del alcance publico del
  servidor (solo `public/` es accesible).

---

## 5. Instalable como App (PWA)

El sistema es una **Progressive Web App (PWA)**. Esto significa que, ademas
de funcionar como sitio web normal, los usuarios pueden "instalarla" en su
celular o PC directamente desde el navegador, **sin pasar por Play Store ni
App Store**:

- **Android (Chrome):** aparece automaticamente un boton **"Instalar App"**
  en el panel y en el login (o el navegador ofrece "Anadir a pantalla de
  inicio" en el menu ⋮). Queda como icono propio, abre en pantalla completa
  sin la barra del navegador.
- **iPhone (Safari):** tocar el boton compartir → **"Anadir a pantalla de
  inicio"**. iOS no dispara el evento automatico de instalacion, por eso
  ahi se hace manualmente, pero el resultado es el mismo icono en el
  dispositivo.
- **PC (Chrome/Edge):** aparece un icono de instalacion en la barra de
  direcciones.

### Archivos que habilitan esto
- `public/manifest.json` — nombre, colores, iconos y modo de apertura (`standalone`)
- `public/sw.js` — Service Worker (requisito tecnico obligatorio para instalar)
- `public/assets/img/icons/` — iconos en varios tamanos, incluidos los
  *maskable* que Android recorta automaticamente segun el estilo del telefono
- `public/favicon.ico`

### Requisito importante: HTTPS
Los navegadores **solo permiten instalar la PWA si el sitio corre bajo
HTTPS** (excepto en `localhost`, donde funciona sin HTTPS para pruebas).
Casi todos los hostings actuales (incluidos los gratuitos como
Hostinger, cPanel con Let's Encrypt, Railway, Render, etc.) ofrecen
certificado SSL gratuito — solo debes activarlo.

Si el sitio se sirve sin HTTPS en produccion, el sistema seguira
funcionando perfectamente como pagina web normal, simplemente el
navegador no ofrecera el boton de instalacion.

---

## 6. Roles del sistema

| Rol            | Constante          | Descripcion                                             |
|-----------------|---------------------|----------------------------------------------------------|
| Super Administrador | `ROLE_SUPERADMIN` (`super_admin`) | Administra todos los clientes/licencias del SaaS |
| Cliente         | `ROLE_CLIENTE` (`cliente`)        | Usuario final vinculado a una empresa/licencia   |

El Dashboard ya distingue el contenido mostrado segun el rol autenticado.

---

## 7. Convenciones de rutas (Router MVC)

El sistema soporta rutas explicitas y por convencion:

```
/controlador/metodo/parametro1/parametro2
```

Ejemplos ya registrados:
- `GET  /`                 → DashboardController@index
- `GET  /dashboard`        → DashboardController@index
- `GET  /auth/login`       → AuthController@login
- `POST /auth/authenticate`→ AuthController@authenticate
- `GET  /auth/logout`      → AuthController@logout

Nuevas rutas se agregan en `public/index.php` o se resuelven automaticamente
por convencion si el controlador y metodo existen.

---

## 8. Proximos modulos (siguientes etapas)

Esta entrega es unicamente la **base del sistema**. Los siguientes modulos
se desarrollaran en prompts posteriores:

- Gestion de Clientes y Licencias (CRUD, planes, vencimientos, renovaciones)
- Gestion de Usuarios y permisos
- Modulo de Cartones de Bingo
- Modulo de Sorteos (generacion de balotas, control en vivo)
- Panel de reportes y estadisticas
- Pasarela de pagos / facturacion de licencias
- Panel del Cliente (bingo en vivo, salas, jugadores)

---

**Version de esta entrega:** 1.0.0 — Base MVC + Autenticacion + Panel base.
