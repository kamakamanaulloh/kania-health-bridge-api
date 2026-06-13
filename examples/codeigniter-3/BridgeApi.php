<?php
class BridgeApi
{
    private $baseUrl = 'http://localhost:8000/api/v1';
    private $bridgeKey = 'change-this-secret-key';

    public function post($path, $payload)
    {
        $ch = curl_init($this->baseUrl.$path);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_HTTPHEADER => ['X-BRIDGE-KEY: '.$this->bridgeKey, 'Accept: application/json', 'Content-Type: application/json'], CURLOPT_POSTFIELDS => json_encode($payload)]);
        return json_decode(curl_exec($ch), true);
    }
}
