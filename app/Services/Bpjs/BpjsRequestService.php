<?php
namespace App\Services\Bpjs;

use App\Services\ApiLogger;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Throwable;

class BpjsRequestService
{
    public function __construct(private BpjsSignatureService $signature, private ApiLogger $logger) {}

    public function request(string $profile, string $method, string $path, array $payload = [], ?string $module = null): array
    {
        $config = config("bpjs.$profile");
        $requestId = 'REQ-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(6));
        request()->attributes->set('request_id', $requestId);
        $start = microtime(true);
        $url = rtrim((string) ($config['base_url'] ?? ''), '/') . '/' . ltrim($path, '/');
        $headers = $this->signature->headers($config);
        $options = ['headers' => array_merge($headers, ['Accept' => 'application/json','Content-Type' => 'application/json']), 'timeout' => config('bridge.timeout')];
        if (in_array(strtoupper($method), ['POST','PUT','PATCH','DELETE'], true)) $options['json'] = $payload;

        try {
            $client = new Client(['http_errors' => false]);
            $response = $client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            $bodyString = (string) $response->getBody();
            $body = json_decode($bodyString, true) ?: ['raw' => $bodyString];
            if (isset($body['response']) && is_string($body['response'])) {
                $body['response_decrypted'] = $this->signature->decryptResponse($body['response'], $config, $headers['X-timestamp']);
                $decoded = json_decode($body['response_decrypted'], true);
                if (json_last_error() === JSON_ERROR_NONE) $body['response_decrypted'] = $decoded;
            }
            $duration = (int) round((microtime(true) - $start) * 1000);
            $this->logger->log(['request_id'=>$requestId,'service'=>$profile,'module'=>$module,'endpoint'=>$url,'method'=>strtoupper($method),'request_payload'=>json_encode($payload),'response_payload'=>json_encode($body),'http_code'=>$statusCode,'status'=>$statusCode >= 200 && $statusCode < 300 ? 'success':'failed','duration_ms'=>$duration,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent()]);
            return ['ok'=>$statusCode >= 200 && $statusCode < 300, 'status'=>$statusCode, 'body'=>$body, 'duration_ms'=>$duration, 'request_id'=>$requestId];
        } catch (Throwable $e) {
            $duration = (int) round((microtime(true) - $start) * 1000);
            $this->logger->log(['request_id'=>$requestId,'service'=>$profile,'module'=>$module,'endpoint'=>$url,'method'=>strtoupper($method),'request_payload'=>json_encode($payload),'response_payload'=>$e->getMessage(),'http_code'=>500,'status'=>'failed','duration_ms'=>$duration,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent()]);
            return ['ok'=>false,'status'=>500,'body'=>['message'=>$e->getMessage()],'duration_ms'=>$duration,'request_id'=>$requestId];
        }
    }
}
