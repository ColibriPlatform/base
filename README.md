# Colibri base

This is the base extension to build quickly a modular application platform on Yii2 framework.

## Features

* Environment based configuration
* Ready to use Application component
* Web installer to initialize configuration file, database and admin user.
* User management using [dektium/user](https://github.com/dektrium/yii2-user).
* Rbac management using [dektrium/rbac](https://github.com/dektrium/yii2-rbac).
* Settings management using [pheme/settings](https://github.com/phemellc/yii2-settings).

## Install

Run the following command to install :
```bash
composer require colibri-platform/base
```

## Use

This exemple start a Colibri application in your index.php

```php
use colibri\base\components\Env;
use colibri\base\components\WebApplication;

require(__DIR__ . '/vendor/autoload.php');

if (!is_dir(__DIR__ . '/assets')) {
	mkdir(__DIR__ . '/assets');
}

Env::Load(__DIR__ . '/.env');

defined('YII_DEBUG') or define('YII_DEBUG', (boolean) getenv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV')? getenv('YII_ENV') : 'prod');

require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = [
    'id' => 'colibri-test',
    'name' => 'Colibri test',
    'basePath' => __DIR__
];

(new WebApplication($config))->run();

```

Then start the local php web server to run the application :

```bash
php -S localhost:8080
```

Finally go to http://127.0.0.1:8080/ in your browser and follow instruction to finish installation.
