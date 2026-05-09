# MUJER BONITA — Sistema de Gestión para Salón de Belleza

> Sistema web profesional para la gestión integral de un salón de belleza, construido con **Laravel 11** y arquitectura de **monolito modular**.

---

## 🚀 Inicio Rápido

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Instalar dependencias JS
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Base de datos
php artisan migrate:fresh --seed

# 5. Compilar assets
npm run build          # Producción
# o
npm run dev            # Desarrollo (hot reload)

# 6. Iniciar servidor
php artisan serve
```

---

## 📋 Credenciales de Prueba

| Correo | Rol | Contraseña |
|---|---|---|
| trabajodt1c0@gmail.com | super-admin | Password123 |
| joetoe250@gmail.com | admin | Password123 |
| ramosvargabrayan@gmail.com | recepcionista | Password123 |
| joelramostrbj@gmail.com | estilista | Password123 |
| fitgo61@gmail.com | estilista | Password123 |
| ramosvargasbrayanjoel66@gmail.com | cliente | Password123 |
| etsech67@gmail.com | cliente | Password123 |
| xdreicarlos@gmail.com | cliente | Password123 |
| si2psicologiaproy@gmail.com | cliente | Password123 |

---

## 🏗 Arquitectura del Proyecto

Este sistema usa una **arquitectura de monolito modular** con Laravel. Los módulos se encuentran organizados dentro de `app/Modules/` y cada uno sigue el patrón MVC con carpetas propias de `Controllers`, `Models`, `Routes` y `Requests`.

### Estructura de Carpetas

```
magy-makeup/                          ← Raíz del proyecto Laravel
├── app/                              ← 🔧 BACKEND (Lógica del servidor)
│   ├── Console/                      │  Comandos artisan personalizados
│   ├── Http/                         │  Controladores globales
│   │   ├── Controllers/
│   │   │   ├── Auth/                 │  Login, Register, Password Reset
│   │   │   ├── HomeController.php    │  Dashboard principal
│   │   │   └── ProfileController.php │  Perfil de usuario
│   │   └── Requests/                 │  Form Requests globales
│   ├── Models/                       │  Modelos globales (User, ActivityLog)
│   ├── Modules/                      │  ⭐ MÓDULOS DEL NEGOCIO
│   │   ├── P1_GestionUsuarioSeguridad/
│   │   │   ├── Controllers/          │  UserController, RoleController, PermissionController
│   │   │   ├── Models/               │  (Usa App\Models\User global)
│   │   │   ├── Policies/             │  Políticas de autorización
│   │   │   ├── Requests/             │  Validaciones específicas
│   │   │   └── Routes/web.php        │  Rutas del módulo P1
│   │   ├── P2_GestionPersonalClientes/
│   │   │   ├── Controllers/          │  ClienteController, EstilistaController, HorarioController
│   │   │   ├── Models/               │  Cliente, Estilista, Horario
│   │   │   ├── Requests/
│   │   │   └── Routes/web.php
│   │   ├── P3_GestionInventarioHerramientas/
│   │   │   ├── Controllers/          │  ProductoController, HerramientaController
│   │   │   ├── Models/               │  Producto, Herramienta
│   │   │   ├── Requests/
│   │   │   └── Routes/web.php
│   │   ├── P4_GestionServiciosCitas/
│   │   │   ├── Controllers/          │  ServicioController
│   │   │   ├── Models/               │  Servicio
│   │   │   ├── Requests/
│   │   │   └── Routes/web.php
│   │   ├── P5_PagosFacturacion/      │  (Pendiente — Ciclo 3)
│   │   └── P6_ReportesComunicaciones/ │  (Pendiente — Ciclo 3)
│   ├── Policies/                     │  Políticas de autorización globales
│   └── Providers/
│       ├── AppServiceProvider.php    │  Provider principal de Laravel
│       └── ModuleServiceProvider.php │  ⭐ Carga automática de rutas modulares
│
├── config/                           ← Configuración de Laravel
│   ├── app.php                       │  Nombre, timezone, locale
│   ├── auth.php                      │  Guards de autenticación
│   ├── database.php                  │  Conexión BD (MySQL)
│   ├── permission.php                │  Configuración Spatie Permission
│   └── ...
│
├── database/                         ← 🗃 BASE DE DATOS
│   ├── migrations/                   │  Esquemas de tablas
│   ├── seeders/
│   │   ├── DatabaseSeeder.php        │  Seeder principal (orquestador)
│   │   ├── UserSeeder.php            │  Roles, permisos y usuarios base
│   │   ├── RolesSeeder.php           │  Roles básicos
│   │   └── TestDatabaseSeeder.php    │  ⭐ Credenciales reales + datos prueba
│   └── factories/
│
├── resources/                        ← 🎨 FRONTEND (Vistas y assets)
│   ├── views/
│   │   ├── auth/                     │  Login, Register, Password Reset, Profile
│   │   ├── layouts/
│   │   │   ├── app.blade.php         │  Layout principal (sidebar + header)
│   │   │   ├── guest.blade.php       │  Layout para páginas públicas
│   │   │   └── navigation.blade.php  │  Menú lateral con módulos P1-P4
│   │   ├── modules/
│   │   │   ├── personal/             │  Vistas de Clientes, Estilistas, Horarios
│   │   │   ├── inventario/           │  Vistas de Productos, Herramientas, Stock
│   │   │   ├── servicios/            │  Vistas de Servicios
│   │   │   ├── pagos/                │  (Pendiente — Ciclo 3)
│   │   │   └── reportes/             │  (Pendiente — Ciclo 3)
│   │   ├── users/                    │  CRUD usuarios, bitácora, roles
│   │   ├── roles/                    │  CRUD roles
│   │   ├── permissions/              │  CRUD permisos
│   │   ├── home.blade.php            │  Dashboard
│   │   └── welcome.blade.php         │  Landing page pública
│   ├── css/                          │  Estilos CSS
│   ├── js/                           │  JavaScript (app.js)
│   └── sass/                         │  SCSS compilado con Vite
│
├── routes/
│   ├── web.php                       │  Rutas globales (welcome, auth, perfil)
│   └── console.php                   │  Comandos artisan
│
├── public/                           ← 📁 Archivos públicos
│   ├── build/                        │  Assets compilados (Vite)
│   ├── icons/                        │  Iconos CoreUI (SVG)
│   ├── images/                       │  Imágenes estáticas
│   ├── js/                           │  JS compilados (coreui.bundle)
│   └── index.php                     │  Entry point
│
├── storage/                          ← Almacenamiento Laravel
├── tests/                            ← Tests automatizados
├── vendor/                           ← Dependencias Composer
├── node_modules/                     ← Dependencias NPM
│
├── .env                              ← Variables de entorno (NO subir a Git)
├── .env.example                      ← Plantilla de variables
├── composer.json                     ← Dependencias PHP
├── package.json                      ← Dependencias JS
├── vite.config.js                    ← Configuración Vite
├── tailwind.config.js                ← Configuración TailwindCSS
│
├── create_ec2.php                    ← Script AWS: crear instancia EC2
├── create_rds.php                    ← Script AWS: crear base de datos RDS
├── check_infra_status.php            ← Script AWS: verificar infraestructura
├── setup_server.sh                   ← Script bash: configurar servidor Linux
└── deploy_ciclo2.tar.gz              ← Paquete de despliegue Ciclo 2
```

---

## 🔐 Seguridad

- **Contraseñas fuertes**: Mínimo 8 caracteres, 1 mayúscula, 1 minúscula, 1 número
- **Bloqueo de cuentas**: Tras 5 intentos fallidos (clientes se bloquean permanentemente)
- **Bitácora de auditoría**: Registro de todas las acciones con IP y navegador
- **Roles y permisos**: Sistema granular con Spatie Laravel Permission
- **Hashing**: Bcrypt con 12 rondas

---

## 📦 Tecnologías

| Tecnología | Versión | Uso |
|---|---|---|
| PHP | ^8.2 | Lenguaje principal |
| Laravel | ^11.9 | Framework backend |
| Laravel UI | ^4.5 | Scaffolding de auth |
| Spatie Permission | ^6.10 | Roles y permisos |
| MySQL | 8.0 | Base de datos |
| Vite | — | Bundler de assets |
| CoreUI | — | Tema del dashboard |
| TailwindCSS | CDN | Landing page |
| AWS SDK PHP | ^3.379 | Infraestructura cloud |

---

## 📄 Licencia

Proyecto académico — Sistema de Información II (SI2)
