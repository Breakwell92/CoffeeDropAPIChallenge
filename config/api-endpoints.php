<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | This is a list of API endpoints, pulled from the ENV file so that we can continue
    | call these values using config(). Since Laravel 5.2, you cannot call the env from 
    | anywhere other than the config files if you are using `config:cache` during deployment
    */

    'postcode' => env('POSTCODES_API_ENDPOINT'),

];