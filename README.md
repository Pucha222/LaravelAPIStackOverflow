# Proyecto de Integración con la API de Stack Overflow

Este proyecto es una API de Laravel que se conecta con la API pública de Stack Overflow para obtener preguntas basadas en etiquetas y fechas. Los datos obtenidos a través de las peticiones contra el endpoint se almacenan en una base de datos local que permite realizar búsquedas con filtros para evitar nuevas solicitudes a la API cuando ya se han realizado previamente.

Añadido un "pequeño" Front diseñado en bootstrap para visualizar un poco los resultados de las peticiones a Stackoverflow.
Para acceder a este puedes ir directamente a la url "/" de tu apache local desde tu navegador despues de instal·lar y ejecutar el "php artisan serve".

## Requisitos

- PHP >= 8.1
- Composer
- XAMPP (o cualquier servidor local compatible con PHP)
- MySQL (viene con XAMPP)
- Laravel 11

## Instalación

### 1. Clonación del repositorio (Instal·lación con XAMPP)

Clona el repositorio desde GitHub:
https://github.com/Pucha222/LaravelAPIStackOverflow

Ejecuta los siguientes comandos en el directorio de tu repositorio clonado en /htdocs/
```bash
composer install
```
```bash
cp .env.example .env
```

Una vez tengas tu .env deberàs crear tu base de datos en PhpMyAdmin local y configurar los parametros de esta en tu fichero. 
No pongas ninguna contraseña ni username. Solamente el nombre.

```bash

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aqui_va_el_nombre_de_tu_base_de_datos
DB_USERNAME=root
DB_PASSWORD=

```

