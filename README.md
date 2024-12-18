# Gemini-Lite

Gemini-Lite es un SDK de PHP para interactuar con los endpoints de Gemini. Esta repositorio esta enfocado unicamente al desarrollo del paquete, si tu quieres provar el paquete en un proyecto puede ir al siguinte repositiorio: //TODO: Añadir repositiorio

## Instalación

### En la ruta del proyeto

- Ejecutar: composer install
- Ejecutar: composer dump-autoload

### En la ruta del paquete

La ruta del paquete se localiza en /packages/liteopensource/gemini-lite-laravel
Este paso es importante para que se genere el vendor interno del paquete

- composer install
- composer dump-autoload

### Cada vez que efectues una modificación en el paquete debes de

1. Eliminar el paquete de la carpeta vendor (liteopensource/gemini-lite-laravel)
2. Ejecutar: composer update
3. Ejecutar: composer dump-autoload

## Uso

### Para publicar todos los archivos (Migraciones, seeder de limites y archivo de configuración)

php artisan vendor:publish --provider=LiteOpenSource\GeminiLiteLaravel\Src\Providers\GeminiLiteServiceProvider

### Para publicar archivos por separado

#### Migraciones

php artisan vendor:publish --tag="geminilite-config"

#### Seeder para asignar roles y limites

php artisan vendor:publish --tag="geminilite-limit-tokes"

#### Archivo de configuración

php artisan vendor:publish --tag="migrations"

## Características

- [Lista de características principales]

## Requisitos

[Requisitos del sistema o dependencias]

## Configuración

[Pasos de configuración, si los hay]

## Documentación

[Añade aquí enlaces o información sobre la documentación detallada]

## Contribución

[Instrucciones para contribuir al proyecto]

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - vea el archivo [LICENSE](LICENSE) para más detalles.

## Bugs

| Bug | Estado | Descripción |
|-----|--------|-------------|
| [ID del Bug] | [🔴🟡🟢] | [Descripción breve] |

Leyenda:

- 🔴 No resueltos
- 🟡 En progreso
- 🟢 Resuelto

## Contacto

[Información de contacto o enlaces a redes sociales]
