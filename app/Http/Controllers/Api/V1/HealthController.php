<?php
namespace App\Http\Controllers\Api\V1;
use App\Helpers\BridgeResponse;
use App\Http\Controllers\Controller;
class HealthController extends Controller
{
    public function __invoke() { return BridgeResponse::success('bridge','health','Kania Health Bridge API is running', ['version'=>'1.0.0','environment'=>config('bridge.env'),'timezone'=>config('app.timezone')]); }
}
