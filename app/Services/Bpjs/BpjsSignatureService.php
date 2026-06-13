<?php
namespace App\Services\Bpjs;

class BpjsSignatureService
{
    public function timestamp(): string
    {
        return (string) time();
    }

    public function signature(string $consId, string $secretKey, string $timestamp): string
    {
        return base64_encode(hash_hmac('sha256', $consId.'&'.$timestamp, $secretKey, true));
    }

    public function headers(array $credential): array
    {
        $timestamp = $this->timestamp();
        return [
            'X-cons-id' => $credential['cons_id'],
            'X-timestamp' => $timestamp,
            'X-signature' => $this->signature((string) $credential['cons_id'], (string) $credential['secret_key'], $timestamp),
            'user_key' => $credential['user_key'],
        ];
    }

    public function decryptResponse(?string $encrypted, array $credential, string $timestamp): mixed
    {
        if (! $encrypted) return null;
        $key = (string) $credential['cons_id'] . (string) $credential['secret_key'] . $timestamp;
        $keyHash = hex2bin(hash('sha256', $key));
        $iv = substr($keyHash, 0, 16);
        $decoded = base64_decode($encrypted);
        $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $keyHash, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) return $encrypted;
        $lz = \App\Support\LZString::decompressFromEncodedURIComponent($decrypted);
        return $lz ?: $decrypted;
    }
}
