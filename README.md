# Module flashka

Module for PIXELION CMS

[API documentation](http://apidoc.fleshka.od.ua/doc/php/)
[API Changes](http://apidoc.fleshka.od.ua/changes/)

[![Latest Stable Version](https://poser.pugx.org/panix/mod-flashka/v/stable)](https://packagist.org/packages/panix/mod-flashka)
[![Latest Unstable Version](https://poser.pugx.org/panix/mod-flashka/v/unstable)](https://packagist.org/packages/panix/mod-flashka)
[![Total Downloads](https://poser.pugx.org/panix/mod-flashka/downloads)](https://packagist.org/packages/panix/mod-flashka)
[![Monthly Downloads](https://poser.pugx.org/panix/mod-flashka/d/monthly)](https://packagist.org/packages/panix/mod-flashka)
[![Daily Downloads](https://poser.pugx.org/panix/mod-flashka/d/daily)](https://packagist.org/packages/panix/mod-flashka)
[![License](https://poser.pugx.org/panix/mod-flashka/license)](https://packagist.org/packages/panix/mod-flashka)


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

#### Either run

```
php composer require --prefer-dist panix/mod-flashka "*"
```

or add

```
"panix/mod-flashka": "*"
```

to the require section of your `composer.json` file.
#### Add to common config.
```
'bootstrap' => [
    ..
    'queue',
],
'components' => [
    ...
    'queue' => [
        'class' => 'yii\queue\db\Queue',
        'channel' => 'default', // Выбранный для очереди канал
        'mutexTimeout' => 5,
        'ttr' => 2 * 60, // Максимальное время выполнения задания
        'attempts' => 3, // Максимальное кол-во попыток
        'deleteReleased' => true,
        'mutex' => \yii\mutex\MysqlMutex::class, // Мьютекс для синхронизации запросов
        'as log' => \yii\queue\LogBehavior::class,
        // Other driver options
    ],
]
```
#### Add to console config.
```
'controllerMap' => [
    'migrate' => [
        ...
        'migrationNamespaces' => [
            'yii\queue\db\migrations',
        ],
    ]
],
```
#### Add to web config.
```
'modules' => [
    'flashka' => [
        'class' => 'panix\mod\flashka\Module',
    ],
],
'components' => [
    'log' => [
        ..
        'targets'=>[
            [
                'class' => 'panix\engine\log\FileTarget',
                'logFile' => '@runtime/logs/flashka.log',
                'categories' => ['flashka'],
                'logVars' => []
            ]
        ]
    ],
]
```

#### Migrate
```
php cmd migrate --migrationPath=vendor/panix/mod-flashka/migrations
```


> [![PIXELION CMS!](https://pixelion.com.ua/uploads/logo.svg "PIXELION CMS")](https://pixelion.com.ua)  
<i>Content Management System "PIXELION CMS"</i>  
[www.pixelion.com.ua](https://pixelion.com.ua)

> The module is under development, any moment can change everything.



