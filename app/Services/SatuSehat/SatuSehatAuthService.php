<?php
namespace App\Services\SatuSehat;

use App\Models\TokenCache;
use GuzzleHttp\Client;
use RuntimeException;

class SatuSehatAuthService
{
    public function getToken(bool $forceRefresh = false): string
    {
        $cached = TokenCache::where('service', 'satusehat')->first();
        if (! $forceRefresh && $cached && $cached->expired_at->gt(now()->addMinutes(2))) return $cached->token;

        $clientId = config('satusehat.client_id');
        $clientSecret = config('satusehat.client_secret');
        if (! $clientId || ! $clientSecret) throw new RuntimeException('SATUSEHAT_CLIENT_ID / SATUSEHAT_CLIENT_SECRET belum diisi.');

        $client = new Client(['http_errors' => false, 'timeout' => config('bridge.timeout')]);
        $res = $client->post(config('satusehat.auth_url'), [
            'form_params' => ['client_id' => $clientId, 'client_secret' => $clientSecret],
            'headers' => ['Accept' => 'application/json'],
        ]);
        $body = json_decode((string) $res->getBody(), true);
        if ($res->getStatusCode() >= 300 || ! isset($body['access_token'])) throw new RuntimeException($body['error_description'] ?? $body['message'] ?? 'Gagal mendapatkan token SATUSEHAT.');

        TokenCache::updateOrCreate(['service' => 'satusehat'], [
            'token' => $body['access_token'],
            'expired_at' => now()->addSeconds((int) ($body['expires_in'] ?? 3600)),
        ]);
        return $body['access_token'];
    }
}
