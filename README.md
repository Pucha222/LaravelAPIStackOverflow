# Integración API de Stack Overflow

Este proyecto es una API de Laravel que se conecta con la API pública de Stack Overflow para obtener preguntas basadas en etiquetas y fechas. Los datos obtenidos a través de las peticiones contra el endpoint se almacenan en una base de datos local que permite realizar búsquedas con filtros para evitar nuevas solicitudes a la API cuando ya se han realizado previamente.

Añadido un "sencillo" front-end diseñado en bootstrap para visualizar un poco los resultados de las peticiones a Stackoverflow.
Para acceder a este puedes ir directamente a la url "/" de tu apache local desde tu navegador despues de instal·lar y ejecutar el "php artisan serve".

## Requisitos

- PHP >= 8.1
- Composer
- XAMPP (o cualquier servidor local compatible con PHP)
- MySQL (viene con XAMPP)
- Laravel 11

## 1. Instalación

#### Clona el repositorio desde GitHub:
https://github.com/Pucha222/LaravelAPIStackOverflow

#### Ejecuta comandos
Ejecuta los siguientes comandos en el directorio de tu repositorio clonado en /htdocs/
```bash
composer install
```
Crea tu .env
```bash
cp .env.example .env
```

#### Configuración de base de datos
Configurar los parametros de la base de datos en tu fichero. La crearemos en el próximo paso. Recuerda el nombre que le has puesto. 
No pongas ninguna contraseña ni username. Solamente el nombre.
```env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aqui_va_el_nombre_de_tu_base_de_datos
DB_USERNAME=root
DB_PASSWORD=

```

#### Configuración de XAMPP
Si estás usando XAMPP, asegúrate de tener MySQL corriendo. Puedes verificarlo desde el panel de control de XAMPP.

Inicia Apache y MySQL.
Abre http://localhost/phpmyadmin/ en tu navegador y crea una base de datos con el nombre que configuraste en el archivo .env.

#### Ejecuta comandos artisan de Laravel
Genera la key en tu .env
```bash
php artisan key:generate
```

Genera las tablas
```bash
php artisan migrate
```
Esto creará las tablas questions y searches en tu base de datos, las cuales almacenan las preguntas obtenidas de la API y las búsquedas realizadas.

Despliega el servicio
```bash
php artisan serve
```

#### Información extra
Por defecto, el servidor estará disponible en http://127.0.0.1:8000.

Puedes probar el endpoint de forma cómoda desde POSTMAN con este ejemplo:

http://127.0.0.1:8000/api/questions?tagged=php&fromdate=2024-01-01&todate=2024-12-31

Cada parámetro de la búsqueda permite filtrar según se desee. Puedes modificar estos parámetros para realizar diferentes búsquedas y visualizar en el front-end los datos recuperados.<br> 
Si quieres ver que búsquedas son más comunes, puedes mirar el campo "contador" de la tabla searches.<br> Este campo te dice cuantas búsquedas se han realizado de esa en concreto. Pensado para hacer una estadística de que se busca más.

## 2. Detalles de la estructura de la base de datos
### Tabla questions: Almacena las preguntas obtenidas de la API de Stack Overflow.
    question_id: ID único de la pregunta en Stack Overflow.
    title: Título de la pregunta.
    link: Enlace directo a la pregunta.
    tags: Etiquetas asociadas a la pregunta.
    creation_date: Fecha de creación de la pregunta.

### Tabla searches: Almacena las búsquedas realizadas para evitar solicitudes repetidas a la API.
    busqueda: Filtros de búsqueda concatenados (por ejemplo, etiqueta, fechas).
    contador: Número de veces que se ha realizado esta búsqueda.

## 3. Notas
-Si no encuentras preguntas en la base de datos, el sistema hará una llamada a la API de Stack Overflow y almacenará los resultados.<br>
-Las búsquedas realizadas se guardan en la tabla searches para optimizar el proceso y evitar hacer peticiones innecesarias a la API.<br>
-Código de la lógica de la petición a Stackoverflow en StackOverflowController.php


<br><br><br>
Pol Pujadó
<br>
2024-11-08
