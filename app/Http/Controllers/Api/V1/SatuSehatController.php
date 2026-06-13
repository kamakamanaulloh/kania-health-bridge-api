<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
use App\Mappers\SatuSehat\ConditionMapper;
use App\Mappers\SatuSehat\EncounterMapper;
use App\Mappers\SatuSehat\ObservationMapper;
use App\Services\SatuSehat\SatuSehatAuthService;
use App\Services\SatuSehat\SatuSehatRequestService;
use Illuminate\Http\Request;
use Throwable;
class SatuSehatController extends Controller
{
    public function __construct(private SatuSehatRequestService $service, private SatuSehatAuthService $auth) {}
    public function token(Request $request) { try { return BridgeResponse::success('satusehat','token','Token SATUSEHAT berhasil dibuat', ['access_token'=>$this->auth->getToken($request->boolean('force'))]); } catch (Throwable $e) { return BridgeResponse::error('satusehat','token','Gagal membuat token', ['code'=>'TOKEN_ERROR','detail'=>$e->getMessage()], 500); } }
    public function raw(Request $request, string $resource) { $result = $this->service->request('POST', $resource, $request->all(), $resource); return $this->format($result, $resource); }
    public function encounter(Request $request, EncounterMapper $mapper) { return $this->mapped($request, $mapper, 'Encounter'); }
    public function condition(Request $request, ConditionMapper $mapper) { return $this->mapped($request, $mapper, 'Condition'); }
    public function observation(Request $request, ObservationMapper $mapper) { return $this->mapped($request, $mapper, 'Observation'); }
    private function mapped(Request $request, object $mapper, string $resource) { try { $payload = $mapper->map($request->all()); $result = $this->service->request('POST', $resource, $payload, strtolower($resource)); return $this->format($result, strtolower($resource)); } catch (Throwable $e) { return BridgeResponse::error('satusehat', strtolower($resource), 'Payload tidak valid', ['code'=>'VALIDATION_ERROR','detail'=>$e->getMessage()], 422); } }
    private function format(array $result, string $module) { if ($result['ok']) return BridgeResponse::success('satusehat',$module,'Request SATUSEHAT berhasil', $result['body'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']], $result['status']); return BridgeResponse::error('satusehat',$module,'Request SATUSEHAT gagal', ['code'=>'SATUSEHAT_ERROR','detail'=>$result['body']], $result['status'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']]); }
}
