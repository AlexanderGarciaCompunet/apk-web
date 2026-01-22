<?php

/**
 * List of allowed domains.
 * Note: Restriction works only for AJAX (using CORS, is not secure).
 *
 * @return array List of domains, that can access to this API
 */
function allowedDomains(): array
{
    return [
        'http://localhost:8088',
        'http://localhost:8100',
        'http://localhost',
    ];
}

return [
    'adminEmail' => 'admin@example.com',
    'cors' => [
        'Origin' => allowedDomains(),
        'Access-Control-Request-Methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        'Access-Control-Allow-Methods' => ['GET','POST','OPTIONS','DELETE','PUT'],
        'Access-Control-Request-Headers' => ['*'],
        'Access-Control-Allow-Credentials' => true,
        'Access-Control-Allow-Origin' => allowedDomains(),
        'Access-Control-Allow-Headers' =>  ['X-Requested-With', 'Content-Type', 'X-Token-Auth', 'Authorization'],
        'Access-Control-Expose-Headers' => ['*'],
    ],
];
