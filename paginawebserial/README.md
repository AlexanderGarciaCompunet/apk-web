# Cititor Server
***

Cititor Server  es una aplicación basada en el esqueleto de desarrollo [YAPTA](https://github.com/seisvalt/yapta)



[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-advanced.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-advanced)


## Instalación

##### Requerimientos
- PHP 7.2
- Habilitadas las extensiones iconv y gd en php.ini
- MariaDB 10 ó Mysql 5.7

Clone el repositorio.

```
git clone "ruta_proyecto"
cd cititor-server
composer install
./init
```

Realizar la configuración de la base de datos en common y en console para realizar corectamente las migraciones editando common/config/main-local.php y console/config/main-local.php

```php
'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=dasededatos',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        ...
]
```

```
./yii migrate --migrationPath=@mdm/admin/migrations
./yii migrate --migrationPath=@yii/rbac/migrations
./yii migrate/up --migrationPath=@vendor/ogheo/yii2-comments/src/migrations
./yii migrate --migrationPath=@vendor/seisvalt/change-log-behavior/src/migrations
./yii migrate

```

Ejecuta la aplicación con el servidor web incorporado con el comando:

```
./yii serve --docroot='frontend/web'/
```

Credenciales
```
usuario: admin
contraseña: password_0
```




DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
## License

YAPTA open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
