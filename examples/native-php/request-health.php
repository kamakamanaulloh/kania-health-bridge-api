<?php
$ch = curl_init('http://localhost:8000/api/v1/health');
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true]);
echo curl_exec($ch);
