<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
use App\Mappers\Bpjs\EmrMapper;
use App\Services\Bpjs\BpjsRequestService;
use Illuminate\Http\Request;
use Throwable;

class BpjsEmrController extends Controller
{
    public function __construct(private BpjsRequestService $bpjs) {}

    public function status()
    {
        $config = config('bpjs.emr');

        return BridgeResponse::success('emr', 'status', 'Konfigurasi EMR BPJS terbaca', [
            'base_url_configured' => filled($config['base_url'] ?? null),
            'cons_id_configured' => filled($config['cons_id'] ?? null),
            'secret_key_configured' => filled($config['secret_key'] ?? null),
            'user_key_configured' => filled($config['user_key'] ?? null),
            'endpoints' => $config['endpoints'] ?? [],
        ]);
    }

    public function kunjungan(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('kunjungan', fn () => $mapper->kunjungan($request->all()), $request);
    }

    public function diagnosa(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('diagnosa', fn () => $mapper->diagnosa($request->all()), $request);
    }

    public function tindakan(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('tindakan', fn () => $mapper->tindakan($request->all()), $request);
    }

    public function resep(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('resep', fn () => $mapper->resep($request->all()), $request);
    }

    public function laboratorium(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('laboratorium', fn () => $mapper->laboratorium($request->all()), $request);
    }

    public function radiologi(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('radiologi', fn () => $mapper->radiologi($request->all()), $request);
    }

    public function resume(Request $request, EmrMapper $mapper)
    {
        return $this->mapAndSend('resume', fn () => $mapper->resume($request->all()), $request);
    }

    public function raw(Request $request, string $path)
    {
        $method = strtoupper((string) $request->input('_method', $request->method()));
        $payload = $request->except('_method');

        return $this->format(
            $this->bpjs->request('emr', $method, $path, $payload, 'emr_raw'),
            'emr',
            'raw'
        );
    }


    private function mapAndSend(string $module, callable $mapper, Request $request)
    {
        try {
            return $this->sendMapped($module, $mapper(), $request);
        } catch (Throwable $e) {
            return BridgeResponse::error('emr', $module, 'Payload EMR BPJS tidak valid', [
                'code' => 'VALIDATION_ERROR',
                'detail' => $e->getMessage(),
            ], 422);
        }
    }

    private function sendMapped(string $module, array $payload, Request $request)
    {
        try {
            $endpoint = $request->input('endpoint') ?: config("bpjs.emr.endpoints.$module");
            $method = strtoupper((string) $request->input('_method', 'POST'));

            if (!$endpoint) {
                return BridgeResponse::error('emr', $module, 'Endpoint EMR BPJS belum dikonfigurasi', [
                    'code' => 'ENDPOINT_NOT_CONFIGURED',
                    'detail' => "Isi BPJS_EMR_ENDPOINT_".strtoupper($module)." di .env atau kirim field endpoint pada request.",
                ], 422);
            }

            return $this->format($this->bpjs->request('emr', $method, $endpoint, $payload, 'emr_'.$module), 'emr', $module);
        } catch (Throwable $e) {
            return BridgeResponse::error('emr', $module, 'Payload EMR BPJS tidak valid', [
                'code' => 'VALIDATION_ERROR',
                'detail' => $e->getMessage(),
            ], 422);
        }
    }

    private function format(array $result, string $service, string $module)
    {
        if ($result['ok']) {
            return BridgeResponse::success(
                $service,
                $module,
                'Request EMR BPJS berhasil',
                $result['body'],
                ['duration_ms' => $result['duration_ms'], 'request_id' => $result['request_id']],
                $result['status']
            );
        }

        return BridgeResponse::error(
            $service,
            $module,
            'Request EMR BPJS gagal',
            ['code' => 'EMR_BPJS_ERROR', 'detail' => $result['body']],
            $result['status'],
            ['duration_ms' => $result['duration_ms'], 'request_id' => $result['request_id']]
        );
    }
}
