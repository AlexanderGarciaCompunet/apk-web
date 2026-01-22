<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'serialcapture-app',
    'name'=>'SerialCapture by Compunet',
    //'timeZone' => 'America/Bogota',
    'sourceLanguage' => 'en-US',
    'language' => 'es-CO',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
            'defaultRoles' => ['guest', 'user'],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            //'identityClass' => 'Edvlerblog\Adldap2\model\UserDbLdap',
            'enableAutoLogin' => false,
            'authTimeout' => 3600, //En segundos
            'enableSession' => true,
            'autoRenewCookie' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'as authLog' => [
                'class' => 'yii2tech\authlog\AuthLogWebUserBehavior'
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-cititor',
            'class' => 'yii\web\DbSession',
            'timeout' => 3600,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@mdm/admin/views' => '@app/views/config',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<path:\w+>/<controller:\w+>/<action:\w+>' => '<path>/<controller>/<action>',
                '<path:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<path>/<controller>/<action>',

            ],
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],

        'formatter' => [
          //  'timeZone' => 'America/Bogota'
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'common\models\User',
                    'idField' => 'id', // id field of model User
                ]
            ],
            'menus' => [
                'assignment' => [
                    'label' => 'Grand Access'
                ],
                //  'route' => null, // deshabilitar ítem
            ],
            'layout' => null, //otros valores 'right-menu', 'top-menu' y 'null'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'comment' => [
            'class' => 'yii2mod\comments\Module',
        ],
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module',
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
        ]

    ],
    // MODO DEMO: RBAC deshabilitado - permitir acceso a todo
    // 'as access' => [
    //     'class' => 'mdm\admin\components\AccessControl',
    //     'allowActions' => [
    //         'site/login',
    //         'site/logout',
    //         'site/request-password-reset',
    //         'site/contact'
    //     ]
    // ],
    'params' => $params,
];
