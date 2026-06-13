<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
use App\Mappers\Bpjs\SepMapper;
use App\Services\Bpjs\BpjsRequestService;
use Illuminate\Http\Request;
use Throwable;
class BpjsController extends Controller
{
    public function __construct(private BpjsRequestService $bpjs) {}
    public function pesertaNik(string $nik) { return $this->format($this->bpjs->request('vclaim','GET','Peserta/nik/'.$nik.'/tglSEP/'.date('Y-m-d'), [], 'peserta'), 'vclaim', 'peserta'); }
    public function pesertaNoka(string $noKartu) { return $this->format($this->bpjs->request('vclaim','GET','Peserta/nokartu/'.$noKartu.'/tglSEP/'.date('Y-m-d'), [], 'peserta'), 'vclaim', 'peserta'); }
    public function referensiPoli(string $keyword) { return $this->format($this->bpjs->request('vclaim','GET','referensi/poli/'.$keyword, [], 'referensi_poli'), 'vclaim', 'referensi_poli'); }
    public function referensiDiagnosa(string $keyword) { return $this->format($this->bpjs->request('vclaim','GET','referensi/diagnosa/'.$keyword, [], 'referensi_diagnosa'), 'vclaim', 'referensi_diagnosa'); }
    public function sep(Request $request, SepMapper $mapper) { try { $payload = $mapper->map($request->all()); return $this->format($this->bpjs->request('vclaim','POST','SEP/2.0/insert', $payload, 'sep'), 'vclaim', 'sep'); } catch (Throwable $e) { return BridgeResponse::error('vclaim','sep','Payload SEP tidak valid', ['code'=>'VALIDATION_ERROR','detail'=>$e->getMessage()], 422); } }
    public function raw(Request $request, string $profile, string $path) { $method = $request->input('_method', $request->method()); $payload = $request->except('_method'); return $this->format($this->bpjs->request($profile, $method, $path, $payload, 'raw'), $profile, 'raw'); }
    private function format(array $result, string $service, string $module) { if ($result['ok']) return BridgeResponse::success($service,$module,'Request BPJS berhasil', $result['body'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']], $result['status']); return BridgeResponse::error($service,$module,'Request BPJS gagal', ['code'=>'BPJS_ERROR','detail'=>$result['body']], $result['status'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']]); }
}
