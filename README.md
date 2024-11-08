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

### 1. Clonación del repositorio

Clona el repositorio desde GitHub:

```bash
git clone https://github.com/tu_usuario/tu_repositorio.git
cd tu_repositorio
