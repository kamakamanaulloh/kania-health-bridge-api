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
    'emr' => [
        'base_url' => env('BPJS_EMR_BASE_URL', env('BPJS_BASE_URL')),
        'cons_id' => env('BPJS_EMR_CONS_ID', env('BPJS_CONS_ID')),
        'secret_key' => env('BPJS_EMR_SECRET_KEY', env('BPJS_SECRET_KEY')),
        'user_key' => env('BPJS_EMR_USER_KEY', env('BPJS_USER_KEY')),
        'endpoints' => [
            'kunjungan' => env('BPJS_EMR_ENDPOINT_KUNJUNGAN', 'emr/kunjungan'),
            'diagnosa' => env('BPJS_EMR_ENDPOINT_DIAGNOSA', 'emr/diagnosa'),
            'tindakan' => env('BPJS_EMR_ENDPOINT_TINDAKAN', 'emr/tindakan'),
            'resep' => env('BPJS_EMR_ENDPOINT_RESEP', 'emr/resep'),
            'laboratorium' => env('BPJS_EMR_ENDPOINT_LABORATORIUM', 'emr/laboratorium'),
            'radiologi' => env('BPJS_EMR_ENDPOINT_RADIOLOGI', 'emr/radiologi'),
            'resume' => env('BPJS_EMR_ENDPOINT_RESUME', 'emr/resume'),
        ],
    ],
];
