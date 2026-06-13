<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use Illuminate\Http\Request;
class LogController extends Controller
{
    public function index(Request $request) {
        $logs = ApiLog::query()
            ->when($request->service, fn($q,$v)=>$q->where('service',$v))
            ->when($request->status, fn($q,$v)=>$q->where('status',$v))
            ->latest()->paginate((int) $request->get('per_page', 20));
        return BridgeResponse::success('bridge','logs','Log request-response', $logs);
    }
}
