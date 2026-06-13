<?php
namespace App\Services\SatuSehat;

use App\Services\ApiLogger;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Throwable;

class SatuSehatRequestService
{
    public function __construct(private SatuSehatAuthService $auth, private ApiLogger $logger) {}

    public function request(string $method, string $resourcePath, array $payload = [], ?string $module = null): array
    {
        $requestId = 'REQ-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(6));
        request()->attributes->set('request_id', $requestId);
        $start = microtime(true);
        $url = rtrim((string) config('satusehat.base_url'), '/') . '/' . ltrim($resourcePath, '/');
        try {
            $token = $this->auth->getToken();
            $options = ['headers'=>['Authorization'=>'Bearer '.$token,'Accept'=>'application/fhir+json','Content-Type'=>'application/fhir+json'], 'timeout'=>config('bridge.timeout')];
            if (in_array(strtoupper($method), ['POST','PUT','PATCH'], true)) $options['json'] = $payload;
            $client = new Client(['http_errors'=>false]);
            $response = $client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            $bodyString = (string) $response->getBody();
            $body = json_decode($bodyString, true) ?: ['raw'=>$bodyString];
            $duration = (int) round((microtime(true)-$start)*1000);
            $this->logger->log(['request_id'=>$requestId,'service'=>'satusehat','module'=>$module,'endpoint'=>$url,'method'=>strtoupper($method),'request_payload'=>json_encode($payload),'response_payload'=>json_encode($body),'http_code'=>$statusCode,'status'=>$statusCode >= 200 && $statusCode < 300 ? 'success':'failed','duration_ms'=>$duration,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent()]);
            return ['ok'=>$statusCode >= 200 && $statusCode < 300,'status'=>$statusCode,'body'=>$body,'duration_ms'=>$duration,'request_id'=>$requestId];
        } catch (Throwable $e) {
            $duration = (int) round((microtime(true)-$start)*1000);
            $this->logger->log(['request_id'=>$requestId,'service'=>'satusehat','module'=>$module,'endpoint'=>$url,'method'=>strtoupper($method),'request_payload'=>json_encode($payload),'response_payload'=>$e->getMessage(),'http_code'=>500,'status'=>'failed','duration_ms'=>$duration,'ip_address'=>request()->ip(),'user_agent'=>request()->userAgent()]);
            return ['ok'=>false,'status'=>500,'body'=>['message'=>$e->getMessage()],'duration_ms'=>$duration,'request_id'=>$requestId];
        }
    }
}
