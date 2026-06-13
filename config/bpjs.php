<?php
return [
    'vclaim' => [
        'base_url' => env('BPJS_BASE_URL'),
        'cons_id' => env('BPJS_CONS_ID'),
        'secret_key' => env('BPJS_SECRET_KEY'),
        'user_key' => env('BPJS_USER_KEY'),
    ],
    'antrol' => [
        'base_url' => env('ANTROL_BASE_URL'),
        'cons_id' => env('ANTROL_CONS_ID', env('BPJS_CONS_ID')),
        'secret_key' => env('ANTROL_SECRET_KEY', env('BPJS_SECRET_KEY')),
        'user_key' => env('ANTROL_USER_KEY', env('BPJS_USER_KEY')),
    ],
    'icare' => [
        'base_url' => env('ICARE_BASE_URL'),
        'cons_id' => env('ICARE_CONS_ID', env('BPJS_CONS_ID')),
        'secret_key' => env('ICARE_SECRET_KEY', env('BPJS_SECRET_KEY')),
        'user_key' => env('ICARE_USER_KEY', env('BPJS_USER_KEY')),
    ],
    'apotek' => [
        'base_url' => env('APOTEK_BASE_URL'),
        'cons_id' => env('APOTEK_CONS_ID', env('BPJS_CONS_ID')),
        'secret_key' => env('APOTEK_SECRET_KEY', env('BPJS_SECRET_KEY')),
        'user_key' => env('APOTEK_USER_KEY', env('BPJS_USER_KEY')),
    ],
];
