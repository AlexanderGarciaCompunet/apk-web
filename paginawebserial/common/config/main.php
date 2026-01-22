<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
        ],
        // Active Directory configuration - Configure per client
        'ad' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false, // Disabled for generic version
                    'config' => [
                    // Your account suffix, for example: user@example.com
                        'account_suffix'        => '@example.com',

                        // You can use the host name or the IP address of your controllers.
                        'hosts'    => ['localhost'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'               => 'DC=example,DC=com',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'admin',
                        'password'        => 'password',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,
                    ]
                ],
            ], // close providers array
        ], //close ad
        // Secondary AD configuration - Configure per client if needed
        'ad1' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',
            'providers' => [
                'default' => [ //Providername default
                    // Connect this provider on initialisation of the LdapWrapper Class automatically
                    'autoconnect' => false, // Disabled for generic version
                    'config' => [
                    // Your account suffix, for example: user@example.com
                        'account_suffix'        => '@example.com',

                        // You can use the host name or the IP address of your controllers.
                        'hosts'    => ['localhost'],

                        // Your base DN. This is usually your account suffix.
                        'base_dn'=> 'DC=example,DC=com',

                        // The account to use for querying / modifying users. This
                        // does not need to be an actual admin account.
                        'username'        => 'admin',
                        'password'        => 'password',

                                    // To enable SSL/TLS read the docs/SSL_TLS_AD.md and uncomment
                                    // the variables below
                                    //'port' => 636,
                                    //'use_ssl' => true,
                                    //'use_tls' => true,
                    ]
                ],
            ], // close providers array
        ], //close ad
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [

                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'frontend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'mail*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'tooltip*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'rbac-admin*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                'comments*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],

            ]
        ],
        /*'formatter' => [
            'timeZone' => 'America/Bogota'
        ],*/
    ],
    'modules'=>[
        'gii' =>  [
            'class' => 'yii\gii\Module',
            'generators' => [
                'crud' => [
                    'class' => 'yii\gii\generators\crud\Generator',
                    'templates' => [
                        'adminlte' => '@vendor/dmstr/yii2-adminlte-asset/gii/templates/crud/simple',
                    ]
                ],
            ]
        ]
    ]
];
