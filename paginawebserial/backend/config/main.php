<?php
$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

return [
  'id' => 'cititor-backend',
  'basePath' => dirname(__DIR__),
  'controllerNamespace' => 'backend\controllers',
  'bootstrap' => ['log'],
  'modules' => [],
  'components' => [
    'request' => [
      'csrfParam' => '_csrf-backend',
    ],
    'user' => [
      'identityClass' => 'backend\models\User',
      //'identityClass' => 'Edvlerblog\Adldap2\rmodel\UserDbLdap',
      'enableAutoLogin' => true,
      'enableSession' => false,
      //'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
    ],
    //No necesario para restful
    /*
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],*/
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
    'urlManager' => [
      'enablePrettyUrl' => true,
      'enableStrictParsing' => true,
      'showScriptName' => false,
      'rules' => [

        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'auth', 'pluralize' => false,
          'patterns' => [
            'POST,HEAD login' => 'login',
            'GET logout' => 'logout',
            'GET user' => 'user',
            'POST,HEAD test' => 'test',
            'OPTIONS login' => 'options',
            'OPTIONS test' => 'options',
          ],
        ],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'center'],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'customer'],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'inventory'],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'store'],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'item', 'extraPatterns' => [
            'GET search' => 'search',
          ],
        ],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'warehouse'],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'lpn-master', 'extraPatterns' => [
            'POST search' => 'search',
          ]
        ],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'lpn-position', 'extraPatterns' => [
            'POST search' => 'search',
          ]
        ],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'serial-master', 'extraPatterns' => [
            'GET finish' => 'finish',
          ]
        ],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'document-header', 'extraPatterns' => [
            'GET search' => 'search',
          ]
        ],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'document-position', 'extraPatterns' => [
            'GET search' => 'search',
          ]
        ],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'serial-type'],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'serial-rules', 'extraPatterns' => [
            'GET search' => 'search',
          ]
        ],
        [
          'class' => 'yii\rest\UrlRule', 'controller' => 'serial-master', 'extraPatterns' => [
            'GET finish' => 'finish',
          ]
        ],
        ['class' => 'yii\rest\UrlRule', 'controller' => 'serial-list'],

      ]
    ],
    'request' => [
      'parsers' => [
        'application/json' => 'yii\web\JsonParser',
      ],
    ]
  ],
  'params' => $params,
];
