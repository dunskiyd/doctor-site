<?php

$params = require(__DIR__ . '/params.php');
$modules = require(__DIR__ . '/modules.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1234567',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],*/
        'user' => [
            'identityClass' => 'lowbase\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'],
            'on afterLogin' => function($event) {
                lowbase\user\models\User::afterLogin($event->identity->id);
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //Взаимодействия с пользователем на сайте
                '<action:(login|logout|signup|confirm|reset|profile|remove|online)>' => 'lowbase-user/user/<action>',
                //Взаимодействия с пользователем в панели админстрирования
                'admin/user/<action:(index|update|delete|view|rmv|multidelete|multiactive|multiblock)>' => 'lowbase-user/user/<action>',
                //Авторизация через социальные сети
                'auth/<authclient:[\w\-]+>' => 'lowbase-user/auth/index',
                //Просмотр пользователя
                'user/<id:\d+>' => 'lowbase-user/user/show',
                //Взаимодействия со странами в панели админстрирования
                'admin/country/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/country/<action>',
                //Поиск населенного пункта (города)
                'city/find' => 'lowbase-user/city/find',
                //Взаимодействия с городами в панели администрирования
                'admin/city/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/city/<action>',
                //Работа с ролями и разделением прав доступа
                'admin/role/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-item/<action>',
                //Работа с правилами контроля доступа
                'admin/rule/<action:(index|create|update|delete|view|multidelete)>' => 'lowbase-user/auth-rule/<action>',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    // https://vk.com/editapp?act=create
                    'class' => 'lowbase\user\components\oauth\VKontakte',
                    'clientId' => '?',
                    'clientSecret' => '?',
                    'scope' => 'email'
                ],
                'google' => [
                    // https://console.developers.google.com/project
                    'class' => 'lowbase\user\components\oauth\Google',
                    'clientId' => '?',
                    'clientSecret' => '?',
                ],
                'twitter' => [
                    // https://dev.twitter.com/apps/new
                    'class' => 'lowbase\user\components\oauth\Twitter',
                    'consumerKey' => '?',
                    'consumerSecret' => '?',
                ],
                'facebook' => [
                    // https://developers.facebook.com/apps
                    'class' => 'lowbase\user\components\oauth\Facebook',
                    'clientId' => '?',
                    'clientSecret' => '?',
                ],
                'github' => [
                    // https://github.com/settings/applications/new
                    'class' => 'lowbase\user\components\oauth\GitHub',
                    'clientId' => '?',
                    'clientSecret' => '?',
                    'scope' => 'user:email, user'
                ],
                'yandex' => [
                    // https://oauth.yandex.ru/client/new
                    'class' => 'lowbase\user\components\oauth\Yandex',
                    'clientId' => '?',
                    'clientSecret' => '?',
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => $modules
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
