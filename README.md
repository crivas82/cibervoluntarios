# Cibervoluntarios
![Logo](https://frontend.cibervoluntarios.org/vite/assets/LOGO-CIBERVOLUNTARIOS-WEB-COLOR-JzKYze7h.webp)

Cibervoluntarios prueba técnica febrero 2024

## Built With

* [symfony-framework](https://symfony.com) - Symfony 6.4 PHP framework
* [PHP](https://php.net/) - PHP 8.2

## Authors

* **Carlos de las Rivas Ramírez** - *Senior PHP developer & IT manager* - [carlosdelasrivasramirez@gmail.com](mailto:carlosdelasrivasramirez@gmail.com)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Tarea

La prueba consiste en una API Rest de Pizzas, una simple CRUD de una única entidad, los métodos de la API son completamente públicos, nada de permisos.

Requisitos Técnicos:
PHP 8.2
Symfony 6.4
API Platform
Base de datos relacional con ORM (el motor en verdad me da igual, mientras esté soportado por Doctrine)

API Platform es un framework que se acopla encima de symfony para facilitar la creación de APIs ofreciendo un set muy completo de utilidades y documentación, es nuestra base para muchos proyectos de la fundación.

Campos mínimos de la entidad, formato y validaciones en la entidad de datos:

name: Requerido, máximo 48 caracteres

ingredients: Requerido, un array de string, máximo 20 elementos.

ovenTimeInSeconds: No requerido, un integer.

createdAt: Se tiene que insertar automáticamente.

updatedAt: Se tiene que actualizar automáticamente.

special: Requerido pero solo al insertar y este campo no se puede modificar después, solo insertar y leer, booleano.

## Instalación

En la carpeta del proyecto para levantar los contenedores docker ejecutar los siguientes comandos por terminal:

```bash
   make init-project
```
En el Makefile hay muchas más operaciones para facilitar las laborales 
y no tener que estar entrando en los contenedores como:

Para iniciar los contenderos de docker:
```bash
   make start
```

Para parar los contenederos de docker:
```bash
   make stop
```

Para eliminar los contenederos de docker:
```bash
   make destroy
```

Para instalar las dependencias de composer:
```bash
   make composer-install
```
Tras crear los contenedores e instalar las dependencias, si abrimos el navegador y ponemos la siguiente url

http://localhost:8081/api

## Documentación
ApiPlatform genera la documentación de la API creada que está en la siguiente URL.

[Documentación](http://localhost:8081/api/docs?ui=re_doc)

## Tests
Hay un test unitario para cada método del CRUD de la API con phpUnit.
Para ejecutar los tests sólo hay que ejecutar mediante console con el makefile el siguiente comando:

```bash
   make run-tests
```

