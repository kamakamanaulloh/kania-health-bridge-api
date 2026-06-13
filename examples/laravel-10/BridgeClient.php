<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
class BridgeClient
{
    public function post(string $path, array $payload): array
    {
        return Http::withHeaders(['X-BRIDGE-KEY' => config('services.bridge.key'), 'Accept' => 'application/json'])->post(config('services.bridge.url').$path, $payload)->json();
    }
}
