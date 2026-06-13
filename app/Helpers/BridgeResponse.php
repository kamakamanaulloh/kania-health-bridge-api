<?php
namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BridgeResponse
{
    public static function success(string $service, ?string $module, string $message, mixed $data = null, array $meta = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'service' => $service,
            'module' => $module,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge(['request_id' => request()->attributes->get('request_id', self::requestId())], $meta),
        ], $code);
    }

    public static function error(string $service, ?string $module, string $message, array $error = [], int $code = 400, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'service' => $service,
            'module' => $module,
            'message' => $message,
            'error' => $error,
            'meta' => array_merge(['request_id' => request()->attributes->get('request_id', self::requestId())], $meta),
        ], $code);
    }

    public static function requestId(): string
    {
        return 'REQ-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(6));
    }
}
