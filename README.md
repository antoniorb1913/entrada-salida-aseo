# Control de Salidas.

Esta aplicación es una plataforma web desarrollada para gestionar, monitorizar y registrar en tiempo real las ausencias temporales de los alumnos cuando piden permiso para ir al baño, optimizando el control de la convivencia dentro del centro.


## Tecnologías Utilizadas

  - Lenguajes: PHP (Backend Principal), JavaScript (Interactividad), HTML5 (Estructura web) y Bootstrap 5 (Estilos).

  - Framework: Laravel 12, aprovechando el sistema de cifrado nativo, motor de plantillas Blade, gestión de sesiones (Auth) y middlewares de seguridad.

  - Base de Datos: PostgreSQL (Versión 16), configurada con restricciones de integridad estrictas para garantizar la consistencia de los datos.

  - Entorno de Desarrollo Local: Docker, utilizado para aislar y levantar de forma automatizada el contenedor de la base de datos Postgres.

  - Despliegue y Producción: Dokploy, sirviendo la aplicación bajo el dominio seguro https://aseos.cgarcher.dev.


## Levantar proyecto (Local)

  ### Levantar la base de datos:

  ```bash
    docker compose up --build -d.
  ```

  ### Crear migraciones y seeder:

  ```bash
    php artisan migrate --seed
  ```
  ### Visualizar proyecto:

  Tener proyecto en Laravel Herd y acceder a --> http://entrada-salida-aseo.test/.

  Sin Laravel Herd:
  
  ```bash
    php artisan serve
  ```

## Levantar proyecto (Dokploy).

  El proyecto ya esta desplegado solo hace falta acceder a https://aseos.cgarcher.dev.

