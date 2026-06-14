<?php

$baseUrl = 'http://localhost:8000';
$bridgeKey = 'change-this-secret-key';

$payload = [
    'no_rawat' => '2026/06/11/000001',
    'no_sep' => '0301R0010626V000001',
    'no_kartu' => '0001234567890',
    'nik' => '357xxxxxxxxxxxxx',
    'nama_pasien' => 'Budi Santoso',
    'tgl_kunjungan' => '2026-06-11',
    'kode_poli' => 'INT',
    'nama_poli' => 'Penyakit Dalam',
    'kode_dokter' => '12345',
    'nama_dokter' => 'dr. Ahmad',
    'keluhan_utama' => 'Demam dan batuk',
    'sistole' => 120,
    'diastole' => 80,
    'nadi' => 88,
    'respirasi' => 20,
    'suhu' => 37.5,
    'spo2' => 98,
];

$ch = curl_init($baseUrl.'/api/v1/bpjs/emr/kunjungan');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'X-BRIDGE-KEY: '.$bridgeKey,
        'Accept: application/json',
        'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo $error;
    exit;
}

echo $response;
