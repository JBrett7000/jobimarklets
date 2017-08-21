<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: services.php
 * Date: 19/08/2017
 * Time: 20:21
 */

return [
    'mailgun' => [
        'domain' => getenv('MAILGUN_DOMAIN'),
        'secret' => getenv('MAILGUN_SECRET'),
    ],
];