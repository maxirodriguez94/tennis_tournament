# Tennis Tournament

Este es un proyecto Laravel diseñado para simular torneos de tenis. Incluye jugadores predefinidos y permite realizar simulaciones de partidos.

## Requisitos

Asegúrate de tener instalados los siguientes requisitos previos antes de comenzar:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   Git

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

### 3. Instalar dependencias con Composer

Accede al contenedor principal de la aplicación y ejecuta el siguiente comando para instalar las dependencias:

1. Accede al contenedor:

    ```bash
    docker exec -it tennis_app bash
    ```

2. Instala las dependencias con Composer:

    ```bash
    composer install
    ```

---

### 4. Construir y levantar los contenedores Docker

Ejecuta los siguientes comandos para construir y levantar los contenedores de Docker:

```bash
sudo docker-compose build
sudo docker-compose up -d --build
```

Esto iniciará los servicios necesarios, incluyendo el servidor PHP.

---

### 5. Configurar la base de datos

1. Crea el archivo `database.sqlite` en la carpeta `database`:

    ```bash
    touch database/database.sqlite
    ```

2. Verifica los permisos del archivo:

    ```bash
    chmod 664 database/database.sqlite
    ```

---

### 6. Ejecutar migraciones y seeders

1. Ejecuta las migraciones:

    ```bash
    php artisan migrate
    ```

2. Ejecuta los seeders para poblar la base de datos con los jugadores iniciales:

    ```bash
    php artisan db:seed --class=PlayerSeeder
    ```

---

### 7. Levantar el servidor de desarrollo

Desde el contenedor, inicia el servidor de desarrollo Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Tu aplicación estará disponible en [http://localhost:8000/api/](http://localhost:8000/api/).

---

### 8. Documentación de la API

El proyecto incluye una documentación de la API generada con Swagger. Para acceder a ella, sigue estos pasos:

1. Instala la biblioteca de Swagger:

    ```bash
    composer require "darkaonline/l5-swagger"
    ```

2. Publica los archivos de configuración:

    ```bash
    php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
    ```

3. Genera la documentación:

    ```bash
    php artisan l5-swagger:generate
    ```

4. Accede a la documentación en:

    [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

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

-   Apagar los contenedores:

    ```bash
    docker-compose down
    ```

-   Reconstruir el entorno:

    ```bash
    docker-compose up --build -d
    ```

-   Acceder al contenedor principal:
    ```bash
    docker exec -it tennis_app bash
    ```

---

## Contribución

Si deseas contribuir al proyecto o tienes alguna duda, contacta al equipo de desarrollo. La aplicación está disponible en producción en: [http://54.193.214.161:8000/api](http://54.193.214.161:8000/api).

