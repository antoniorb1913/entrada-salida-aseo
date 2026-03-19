# Sistema de Gestión de Salidas al Baño - IES Antonio Hellin Costa

Este proyecto es una aplicación web desarrollada con **Laravel 12** diseñada para que el profesorado pueda llevar un control y en tiempo real de las salidas y entradas de los alumnos al aseo.

---

## Stack Tecnológico
* **Backend:** PHP 8.4 + Laravel 12.
* **Base de Datos:** MySQL (Gestionada con MySQL).
* **Servidor Local:** Laravel Herd.
* **Frontend:** Blade Templates + Bootstrap 5.

---
## Arquitectura de Modelos (Eloquent)
En el directorio `app/Models`, se han definido los siguientes modelos con sus respectivas relaciones y **constraints**:

* **User (Profesor):** Gestiona la autenticación. 
    * *Relación:* `hasMany(Alumno)` (Un profesor tutoriza a muchos alumnos).
    * *Relación:* `hasMany(Registro)` (Un profesor autoriza muchos movimientos).
* **Alumno:** Datos del estudiante.
    * *Relación:* `belongsTo(Aula)` (Cada alumno pertenece a una clase única).
    * *Constraint:* El campo `nre` es único.
* **Aula:** Define los grupos (1º ESO, 2º Bach, etc.).
    * *Relación:* `hasMany(Alumno)`.
* **Registro:** La entidad principal de control.
    * *Atributos:* `fecha_salida` y `fecha_entrada` (admite NULL hasta el regreso).

---

## Comandos Artisan Utilizados
Durante el desarrollo se han empleado los siguientes comandos de consola:

### Generación de componentes:
```bash
# Crear controladores y modelos
php artisan make:controller LoginController
php artisan make:model Alumno
php artisan make:model Aula
php artisan make:model Registro

```

## Modelo de Datos (Base de Datos)
La base de datos `aseos_DB` utiliza un diseño relacional con **Foreign Keys** para asegurar la integridad de los datos.

| Tabla | Descripción |
| :--- | :--- |
| **profesor** | Usuarios del sistema (profesores) con roles y credenciales. |
| **alumno** | Información de los estudiantes vinculados a un aula. |
| **registro** | Historial de tiempos (ID alumno, ID profesor, salida y entrada). |

---

## Funcionalidades Implementadas
### 1. Sistema de Autenticación (Login)
* Acceso restringido mediante el campo `nombre` y `password`.
* Uso de **Middleware Auth** para proteger la zona privada.
* Cierre de sesión seguro con invalidación de tokens.

### 2. Estructura de Base de Datos
* Migraciones configuradas manualmente con soporte para `timestamps` (`created_at`, `updated_at`).
* Relaciones **1:N** entre Aulas/Alumnos y Alumnos/Registros.

---

## Instalación y Pruebas
1.  **Base de Datos:** Ejecutar el script SQL de creación de tablas y constraints.
2.  **Configuración:** Asegurar que el archivo `.env` apunta a `aseos_DB`.
       - #### Ejecutar el seeder del profesor (crea al usuario 'cipri')
            ```bash
            php artisan db:seed --class=ProfesorSeeder
            ```
3.  **Datos de Prueba:** Ejecutar el seeder para crear el usuario administrador:
    ```bash
    php artisan db:seed --class=ProfesorSeeder
    ```
4.  **Acceso:** * **Usuario:** `cipri`
    * **Password:** `1234`

---

## Controllers separados.
* **AlumnoController:** Encargado de la gestión de la tabla `alumno` (Listados y modificaciones).
* **RegistroController:** Controla el flujo de eventos de "Acceso al baño" (Inserción de tiempos de salida/entrada).
* **ConsultaController:** Gestiona la visualización del historial y reportes para el usuario con rol de consulta.

### Comandos Artisan Utilizados
#### Generación de Componentes de Lógica:
```bash
php artisan make:controller AlumnoController
php artisan make:controller RegistroController
php artisan make:controller ConsultaController
```
### De momento esta vacios tendremos que configurar.

---

## Estructura de Rutas (Navegación Nombrada)
El sistema utiliza **Rutas Nombradas** para facilitar el mantenimiento y evitar URLs "hardcodeadas" en la vista:
* `/admin` -> `route('admin')`
* `/profesor` -> `route('profesor')`
* `/acceso-baño` -> `route('acceso')`
* `/alumnos/modificar` -> `route('modificar')`

Fecha --> 26/12/2025 - 06/01/2026

---


## "Acceso al Baño", Implementacion de la tabla Cursos.

* Atributos:
  * - Etapa (enum) -> Aquí se guardan las etapas (ESO, BACHILLERATO, FP).
  * - Nivel (string) -> Aquí se guardaran los niveles (1º, 2º, 3º, 4º )
  * - Letra (string) -> Aquí se guardaran la letra (A, B, C, D)

Para luego poder hacer consutas y poder filtrar dependiendo de lo que queramos que se vea.

En la documentación/Manual usuario esta un Manual de usuario del prototipo que llevamos.

Fecha --> 06/02/206 - 14/02/2026


## Registro entrada y salida al baño

- añadir un enum de estado -> fuera, en_clase.
- hacer ser controller y el service.
- añadir enpoint en web.php de entrada y salda.



## Implementacion de tiempo de espera

 - modificaciones en la navegación entre páginas
 - se ha implementado en "RegistroService" la logica de limitación de veces que se
 - puede ir al baño y tiempo de espera mientras alguien esté fuera en el baño.
  
Fecha --> 15/03/2026




### Implementaciones nuevas visuales en el registro de entrada y salida.

- Cuando has alcanzado el limite de salidas que salga algo como "Limite alcanzado". OK
- Contador de tiempo para volver a salir en el cuadrado de "salida". OK
- Cambiar el color de boton de salida en los otros alumnos cuando un alumno esta fuera (color: gris). OK

Fecha --> 19/03/2026