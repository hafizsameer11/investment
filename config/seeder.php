<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Seeder Secret Token
    |--------------------------------------------------------------------------
    |
    | This token is used to secure the /run-seeders route. Set this in your
    | .env file as SEEDER_SECRET_TOKEN. Make sure to use a strong, random
    | token in production.
    |
    */

    'secret_token' => env('SEEDER_SECRET_TOKEN', 'change-this-secret-token-in-production'),
];

