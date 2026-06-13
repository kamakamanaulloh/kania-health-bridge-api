<?php
namespace App\Http\Middleware;

use App\Helpers\BridgeResponse;
use Closure;
use Illuminate\Http\Request;

class BridgeKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $configuredKey = (string) config('bridge.key');
        $requestKey = (string) $request->header('X-BRIDGE-KEY');

        if ($configuredKey === '' || $configuredKey === 'change-this-secret-key') {
            return BridgeResponse::error('security', 'bridge_key', 'BRIDGE_KEY belum dikonfigurasi di .env', [
                'code' => 'BRIDGE_KEY_NOT_CONFIGURED',
                'detail' => 'Ubah BRIDGE_KEY pada file .env sebelum digunakan.',
            ], 500);
        }

        if (! hash_equals($configuredKey, $requestKey)) {
            return BridgeResponse::error('security', 'bridge_key', 'X-BRIDGE-KEY tidak valid', [
                'code' => 'UNAUTHORIZED_BRIDGE_KEY',
                'detail' => 'Kirim header X-BRIDGE-KEY sesuai BRIDGE_KEY di server.',
            ], 401);
        }

        $allowedIps = array_filter(array_map('trim', explode(',', (string) config('bridge.allowed_ips'))));
        if (count($allowedIps) > 0 && ! in_array($request->ip(), $allowedIps, true)) {
            return BridgeResponse::error('security', 'allowed_ip', 'IP tidak diizinkan', [
                'code' => 'IP_NOT_ALLOWED',
                'detail' => 'Tambahkan IP SIMRS ke BRIDGE_ALLOWED_IPS.',
                'ip' => $request->ip(),
            ], 403);
        }

        return $next($request);
    }
}
