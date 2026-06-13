<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
use App\Mappers\Bpjs\AntrolMapper;
use App\Services\Bpjs\BpjsRequestService;
use Illuminate\Http\Request;
use Throwable;
class AntrolController extends Controller
{
    public function __construct(private BpjsRequestService $bpjs) {}
    public function antrean(Request $request, AntrolMapper $mapper) { try { return $this->format($this->bpjs->request('antrol','POST','antrean/add', $mapper->map($request->all()), 'antrean'), 'antrean'); } catch (Throwable $e) { return BridgeResponse::error('antrol','antrean','Payload antrean tidak valid', ['code'=>'VALIDATION_ERROR','detail'=>$e->getMessage()], 422); } }
    public function taskid(Request $request) { $request->validate(['kodebooking'=>'required','taskid'=>'required','waktu'=>'required']); return $this->format($this->bpjs->request('antrol','POST','antrean/updatewaktu', $request->all(), 'taskid'), 'taskid'); }
    public function batal(Request $request) { $request->validate(['kodebooking'=>'required','keterangan'=>'required']); return $this->format($this->bpjs->request('antrol','POST','antrean/batal', $request->all(), 'batal'), 'batal'); }
    private function format(array $result, string $module) { if ($result['ok']) return BridgeResponse::success('antrol',$module,'Request Antrol berhasil', $result['body'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']], $result['status']); return BridgeResponse::error('antrol',$module,'Request Antrol gagal', ['code'=>'ANTROL_ERROR','detail'=>$result['body']], $result['status'], ['duration_ms'=>$result['duration_ms'],'request_id'=>$result['request_id']]); }
}
