<?php
namespace App\Services;
use App\Models\ApiLog;
use Throwable;
class ApiLogger
{
    public function log(array $data): void
    {
        try {
            if (! config('bridge.log_payload')) {
                $data['request_payload'] = null;
                $data['response_payload'] = null;
            }
            ApiLog::create($data);
        } catch (Throwable $e) {
            logger()->warning('Failed saving api log: '.$e->getMessage());
        }
    }
}
