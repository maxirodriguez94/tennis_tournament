# Tennis Tournament

Este es un proyecto Laravel diseñado para simular torneos de tenis. Incluye jugadores predefinidos y permite realizar simulaciones de partidos.

## Requisitos

Asegúrate de tener instalados los siguientes requisitos previos antes de comenzar:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- Git

---

## Instalación y configuración

Sigue los pasos a continuación para configurar y ejecutar el proyecto:

### 1. Clonar el repositorio

Clona el repositorio del proyecto desde GitHub:

```bash
git clone git@github.com:maxirodriguez94/tennis_tournament.git
cd tennis_tournament
```

---

### 2. Configurar el entorno

Copia el archivo de ejemplo `.env.example` y renómbralo como `.env`:

```bash
cp .env.example .env
```

Asegúrate de ajustar los valores si es necesario. Para este proyecto, SQLite ya está configurado por defecto.

---

### 3. Construir y levantar los contenedores Docker

Ejecuta los siguientes comandos para construir y levantar los contenedores de Docker:

```bash
sudo docker-compose build
sudo docker-compose up -d --build
```

Esto iniciará los servicios necesarios, incluyendo el servidor PHP.

---

### 4. Ejecutar migraciones y seeders

Accede al contenedor principal y ejecuta las migraciones y seeders para configurar la base de datos:

1. Accede al contenedor:
   ```bash
   docker exec -it tennis_app bash
   ```

2. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

3. Ejecuta los seeders:
   ```bash
   php artisan db:seed --class=PlayerSeeder
   ```

---

### 5. Levantar el servidor de desarrollo

Desde el contenedor, inicia el servidor de desarrollo Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Tu aplicación estará disponible en [http://localhost:8000](http://localhost:8000).

---

## Uso

### API Endpoints

1. **Simular torneo**:
   - **Ruta:** `POST /api/tournaments/simulate`
   - **Cuerpo (JSON):**
     ```json
     {
       "gender": "Femenino",
       "type": "doubles",
       "players": 8
     }
     ```

2. **Obtener torneos con partidos**:
   - **Ruta:** `GET /api/tournaments/with-matches`
   - **Parámetros opcionales:**
     - `gender`
     - `tournament_id`
     - `startDate`
     - `endDate`

---

## Comandos útiles

- Apagar los contenedores:
  ```bash
  docker-compose down
  ```

- Reconstruir el entorno:
  ```bash
  docker-compose up --build -d
  ```

- Acceder al contenedor principal:
  ```bash
  docker exec -it tennis_app bash
  ```

---

Contribución

Si deseas contribuir al proyecto o tienes alguna duda, contacta al equipo de desarrollo.

