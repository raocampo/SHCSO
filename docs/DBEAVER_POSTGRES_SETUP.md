# Configurar PostgreSQL con DBeaver para SHCSO

## 1. Crear base de datos

En DBeaver, conectado a tu servidor PostgreSQL, ejecutar:

```sql
CREATE DATABASE shcso;
```

## 2. Configurar Laravel

En `backend/.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=shcso
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

## 3. Crear tablas y datos base

Desde terminal:

```bash
cd backend
php artisan migrate --seed
php artisan storage:link
```

## 4. Verificar en DBeaver

Debes ver tablas como:

- `users`
- `roles`
- `user_roles`
- `workers`
- `occupational_evaluations`
- `evaluation_attachments`
- `medical_certificates`
- `audit_logs`
