<?php
$url = 'http://localhost:8000/api/v1/satusehat/encounter';
$payload = [
  'no_rawat' => '2026/06/11/000001',
  'patient_ihs' => 'P020123456',
  'practitioner_ihs' => '100009880728',
  'organization_id' => 'ORG_ID',
  'period_start' => '2026-06-11 08:00:00',
  'period_end' => '2026-06-11 08:30:00',
];
$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_HTTPHEADER => ['X-BRIDGE-KEY: change-this-secret-key', 'Accept: application/json', 'Content-Type: application/json'],
  CURLOPT_POSTFIELDS => json_encode($payload),
]);
echo curl_exec($ch);
