<?php
return [
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\mongodb\rbac\MongoDbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => [
                        'html' => ['class' => '\yii\helpers\Html'],
                    ],
                    'uses' => ['yii\bootstrap'],
                    'functions' => array(
                        't' => 'Yii::t',
                    ),
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'gearman' => [
            'class' => 'ext.Gearman',
            'servers' => array(
                array('host' => '127.0.0.1', 'port' => 4730),
            ),
        ],
    ],
];
