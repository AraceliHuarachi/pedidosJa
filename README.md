# Documentación del Proyecto

En la rama main se encuentra el codigo del proyecto juntamente con la aplicacion del trait para simplificar mensajes de validaciones.
Para más información sobre la funcionalidad del proyecto, consulta el siguiente documento:

[Documentación del Proyecto PedidosJa]
[https://docs.google.com/document/d/1t1kaMPm8gmT4ZGA1EloEv9h9ocBCXc9H8Hsxpz5QDxI/edit?usp=sharing]

En la rama traductions se encuentra la aplicacion de traducciones y el archivo con todos los mensajes de validaciones estandar de laravel con su traducion a español, en la siguiente dirección:

[resources/lang/es/validation.php](https://github.com/AraceliHuarachi/pedidosJa/blob/traductions/resources/lang/es/validation.php)

# Inicialización del sistema:
Una vez se tenga clonado el proyecto y abierto en un editor de texto:
1. Instalar dependencias, en una terminal navegar a la dirección de la carpeta del proyecto y ejecutar el siguiente comando comando para instalar todas las dependencias de Composer:  composer install
2. Configurar el Archivo .env El siguiente paso es configurar el archivo .env, que contiene las variables de entorno necesarias para la aplicación. Copiar el archivo .env.example y renombrarlo como .env. 
3. Una vez en .env configurar la conexión a la base de datos, por el nombre: DB_DATABASE=pedidosja
4. Generar la Clave de la Aplicación Laravel requiere una clave de aplicación única para funcionar correctamente. Se puede generar esta clave ejecutando el siguiente comando:  php artisan key:generate
5. Como ya se cuenta con la configuración correcta para la DB, ejecutar las migraciones y los seeders con el siguiente comando: php artisan migrate --seed

# Para probar con Swagger:
Para probar los endpoints de la API de PedidosJa, seguir estos pasos después de hacer correr el proyecto:
1.  Inicia el proyecto Laravel en tu entorno local: php artisan serve
2. Accede a Swagger a través de la ruta: /api/documentation.
3. Explora y prueba los diferentes endpoints directamente desde la interfaz de Swagger.

