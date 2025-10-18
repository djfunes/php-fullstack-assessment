# PHP Fullstack Assessment – MisCV

Aplicación desarrollada en **PHP puro (8.4)** con conexión a **MongoDB**, siguiendo el patrón **MVC**.  
Implementa autenticación de usuarios, búsquedas optimizadas mediante el **Aggregation Framework** de MongoDB  
y una interfaz web limpia con **Bootstrap 5**.

---

## 1. Requisitos Previos

Antes de iniciar, verifica de tener instalado:

- **PHP 8.2 o superior** (CLI y extensiones `mongodb`, `mbstring`, `curl`)
- **Composer 2.x**
- **Docker y Docker Compose**
- **Navegador web** (para ver la interfaz Bootstrap)

## 2. Estructructura del Proyecto
```
PHP-FULLSTACK-ASSESSMENT/
├─ app/
│  ├─ controllers/        # Controladores (Auth, Candidate)
│  ├─ core/               # Clases base: Router, Database, Auth, etc.
│  ├─ routes/             # Definición de rutas
│  └─ views/              # Vistas con Bootstrap (login y búsqueda)
├─ public/
│  └─ index.php           # Punto de entrada principal
├─ scripts/               # Scripts de seed (usuarios y CVs)
├─ docker-compose.yml     # Configuración de MongoDB + mongo-express
├─ .env.example           # Variables de entorno
└─ composer.json          # Dependencias PHP
```

## 3. Levantar el entorno de MongoDB

```bash
docker compose up -d
```

## 4. Instalar dependencias PHP

Ingresa a la carpeta raiz el proyecto y ejecuta:

```bash
composer install
```

Genera el archivo de ambiente:

```bash
cp .env.example .env
```

## 5. Insertar datos de prueba

Ejecutar los scripts seed

```bash
php scripts/seed_users.php
php scripts/seed_cv.php
```

## 6. Ejecutar el proyecto
```bash
php -S localhost:8000 -t public
```

## 7. Realizar pruebas

En tu navegador, dirigete a:

<http://localhost:8000>

Credenciales:
```
| Usuario     | Contraseña    | Rol       |
| ----------- | ------------- | --------- |
| `admin`     | `Admin#123`   | admin     |
| `recruiter` | `Recruit#123` | recruiter |
```

## 8. Autor

Desarrollado por David Funes <dajofu04@gmail.com>