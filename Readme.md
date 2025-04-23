# 📘 Mesa 24/7 – Documentación del Proyecto Fullstack

Este proyecto consiste en una aplicación de reservas de restaurantes compuesta por:

- `mesa_24_7_backend`: Backend construido con Laravel 10
- `mesa_24_7_frontend`: Frontend construido con Vue 3 + Quasar Framework

Todo está orquestado mediante Docker y configurado para funcionar en entorno local con NGINX como servidor para frontend y apache para Backend.

---

## 📁 Estructura del Proyecto

```
MESA_24_7/
├── mesa_24_7_backend/        # API en Laravel
│   ├── app/
│   ├── config/
│   ├── routes/
│   ├── public/
│   ├── Dockerfile
│   └── .env.example        # Archivo de ejemplo de variables de entorno
│
├── mesa_24_7_frontend/       # Interfaz en Vue 3 + Quasar
    ├── src/
    ├── public/
    ├── Dockerfile
    └── .env               # Archivo de variables de entorno para Quasar
```

---

## ✅ Variables de Entorno

Es **obligatorio** configurar los archivos `.env` en ambos entornos:

### Backend (`mesa_24_7_backend/.env`)
1. Copiar el archivo `.env.example` y renombrarlo como `.env`
2. Completar los valores según tu entorno:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=mesa247
DB_USERNAME=psalazar
DB_PASSWORD=gottopassthistest
```

### Frontend (`mesa_24_7_frontend/.env`)

```env
VITE_CLIENT_CRED_ENCRYPT_KEY=HdCQJbkyixu7y9_7wgMHJt6VWzM76izY
VITE_NAME_TOKEN=dJ12ksx_wqh821kjs
VITE_NAME_USUARIO=HJ2Fk1xkqY6_y5vjs
VITE_CLIENT_API_URL=http://localhost:8000/api
```

---
## 👤 Credenciales por defecto (Login)

Puedes usar las siguientes credenciales para acceder al sistema desde el frontend:

```json
{
  "email": "admin_mesa247@dev.com",
  "password": "password123"
}
```

## 🐳 Ejecución con Docker

Desde la raíz del proyecto (donde está el `docker-compose.yml`):

```bash
docker-compose up --build
```

Esto creará los servicios para:
- Backend Laravel (Apache)
- Frontend Vue (NGINX)
- Base de datos MySQL 8

La app estará disponible en:
- **Frontend**: [http://localhost:8080](http://localhost:8080)
- **API Backend**: [http://localhost:8000/api](http://localhost:8000/api)
- **API Backend Documentation**: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## 🌐 Estructura del archivo docker-compose.yml

El sistema está compuesto por tres servicios principales definidos en `docker-compose.yml`:

### 1. **frontend-prod** (Vue + Quasar + Nginx)

```yaml
services:
  frontend-prod:
    build:
      context: ./mesa_24_7_frontend
      dockerfile: Dockerfile
    container_name: vue_frontend_prod
    restart: unless-stopped
    ports:
      - '8080:80'
    networks:
      - laravel
```

- Construye la aplicación frontend desde la carpeta `mesa_24_7_frontend`.
- Se publica en el puerto `8080` del host.
- Usa NGINX como servidor estático para servir el build de Quasar.

### 2. **app** (Laravel + Apache)

```yaml
  app:
    build:
      context: ./mesa_24_7_backend
      dockerfile: Dockerfile
    container_name: mesa247_back
    restart: unless-stopped
    working_dir: /var/www/html
    ports:
      - '8000:80'
    depends_on:
      - db
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    command: >
      sh -c "php artisan migrate:fresh --seed --force &&
           apache2-foreground"
    networks:
      - laravel
```

- Levanta el backend de Laravel desde la carpeta `mesa_24_7_backend`.
- Expone el puerto `8000` del contenedor en el host.
- Ejecuta migraciones y seeders automáticamente al iniciar.

### 3. **db** (MySQL 8.0)

```yaml
  db:
    image: mysql:8.0
    container_name: mesa247_db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: mesa247
      MYSQL_USER: psalazar
      MYSQL_PASSWORD: gottopassthistest
    ports:
      - 3306:3306
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - laravel
```

- Contenedor de base de datos MySQL.
- Usa credenciales definidas por variables de entorno.
- Persiste datos en un volumen Docker llamado `mysql_data`.

### Redes y Volúmenes

```yaml
volumes:
  mysql_data:

networks:
  laravel:
    driver: bridge
```

- Se define una red interna `laravel` para que los contenedores se comuniquen por nombre.
- El volumen `mysql_data` asegura persistencia de los datos de la base de datos.

---

Gracias a esta arquitectura, los contenedores pueden interactuar entre ellos mediante sus nombres (`app`, `db`, `frontend-prod`) y funcionan en conjunto desde una sola orquestación centralizada con Docker Compose.



Desarrollado con 💛 por Mr. Pieromental

