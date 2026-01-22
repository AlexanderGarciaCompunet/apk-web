<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
        'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
        ],
        'ldapcmd' => [
            'class' => 'Edvlerblog\Adldap2\commands\LdapController',
         ],
    ],
    'components' => [
        'ad' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false,
                    'config' => [
                    // Your account suffix, for example: matthias.maderer@example.lan
                        'account_suffix'        => '@demo.local',

                        // You can use the host name or the IP address of your controllers.
                        'hosts'    => ['192.168.76.73'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'=> 'DC=Demo,DC=local',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'ADMINMASER',
                        'password'        => 'DemoPassword123!',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,                                
                    ]
                ],
            ], // close providers array
        ], //close ad

        

        'ad1' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false,
                    'config' => [
                    // Your account suffix, for example: matthias.maderer@example.lan
                        'account_suffix'        => '@demo.local',

                        // You can use the host name or the IP address of your controllers.
                        'hosts'    => ['192.168.76.73'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'=> 'CN=LS_Seriales_Masivos,OU=Toma_Masiva_Seriales_Produccion_User_And_Groups,OU=Usuarios_Genericos,DC=Demo,DC=local',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'ADMINMASER',
                        'password'        => 'DemoPassword123!',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,                                
                    ]
                ],
            ], // close providers array
        ], //close ad
        'ad2' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false,
                    'config' => [
                    // Your account suffix, for example: matthias.maderer@example.lan
                        'account_suffix'        => '@demo.local',

                        // You can use the host name or the IP address of your controllers.
                        'hosts'    => ['192.168.76.73'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'               => 'OU=Direccion de Almacenamiento,OU=Vicepresidencia De Operaciones,OU=601_Calle-34,OU=DEMO,DC=Demo,DC=local',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'ADMINMASER',
                        'password'        => 'DemoPassword123!',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,                                
                    ]
                ],
            ], // close providers array
        ], //close ad
        'ad3' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false,
                    'config' => [
                    // Your account suffix, for example: matthias.maderer@example.lan
                        'account_suffix'        => '@demo.local',

                        // You can use the host nameq
                        'hosts'    => ['192.168.76.73'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'               => 'OU=718_La_Estancia,OU=DEMO,DC=Demo,DC=local',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'ADMINMASER',
                        'password'        => 'DemoPassword123!',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,                                
                    ]
                ],
            ], // close providers array
        ], //close ad

        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\ConsoleModule'
        ]
    ],
    'params' => $params,
];
