# Registro de Verduras por Voz

Práctica de la materia Sistemas de Información 2 (UMSS). Aplicación web en Laravel que permite registrar verduras y sus precios mediante comandos de voz, usando la Web Speech API del navegador.

## Tecnologías

- Laravel (PHP)
- MySQL / phpMyAdmin
- Blade, HTML, CSS
- Web Speech API (reconocimiento de voz del navegador)

## Funcionalidades

- Registrar una verdura por voz: el sistema reconoce el nombre dictado y asigna automáticamente su precio según un catálogo fijo por libra.
- No permite registrar verduras duplicadas.
- Lista las verduras ordenadas de menor a mayor precio, mostrando el puesto de cada una.
- Búsqueda por voz: dictas el nombre de una verdura ya registrada y el sistema indica su puesto, precio y cuántas veces más cara es respecto a la más económica (apio).

## Instalación

```bash
git clone https://github.com/GeraldVargas/Practicasis2verduras.git
cd Practicasis2verduras
composer install
cp .env.example .env
php artisan key:generate
```

Configura tu base de datos en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=verduras_db
DB_USERNAME=root
DB_PASSWORD=
```

Crea la base de datos `verduras_db` en phpMyAdmin, luego ejecuta:

```bash
php artisan migrate
php artisan serve
```

Abre `http://localhost:8000` en Google Chrome (el reconocimiento de voz no funciona en Firefox).

## Autor

Gerald Emanuel Vargas Morales