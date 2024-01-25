🚀 Aplicación Slim Framework 4 PHP con despliegue automático. - TP Comanda.
==============================

## 📝 Introducción
El principal objetivo de este repositorio es poder desplegar de forma automática nuestra aplicación PHP Slim Framework 4 en un servidor en la nube. En esta ocación vamos a utilizar la versión gratuita de Railway, que nos permite vincular nuestro repositorio de github con la plataforma, poder desplegar automáticamente nuesto código y quedar disponible en la web. La aplicación es para el manejo de un sistema de gestión de pedidos de un restaurante.


## 📁 Correr localmente via PHP

- Acceder por linea de comandos a la carpeta del proyecto y luego instalar Slim framework via Compose
```sh
cd C:\<ruta-del-repo-clonado>
composer update
```
- Para levantar el servidor, se debe posicionar en la carpeta `public` y ejecutar el siguiente comando:
```shell
php -S localhost:666
```
Dentro de la carpeta `resources`, hay un archivo llamado `comanda_tp.sql` que contiene la estructura de la base de datos.
Debemos ejecutarlo en nuestro gestor de base de datos para crear la base de datos y sus tablas, y así poder realizar las consultas.

### 2023 - UTN FRA Programación III