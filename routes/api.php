<?php
use App\Http\Controllers\Api\V1\AntrolController;
use App\Http\Controllers\Api\V1\BpjsController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\LogController;
use App\Http\Controllers\Api\V1\SatuSehatController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('health', HealthController::class);

    Route::middleware('bridge.key')->group(function () {
        Route::get('logs', [LogController::class, 'index']);

        Route::prefix('bpjs')->group(function () {
            Route::get('peserta/nik/{nik}', [BpjsController::class, 'pesertaNik']);
            Route::get('peserta/noka/{noKartu}', [BpjsController::class, 'pesertaNoka']);
            Route::get('referensi/poli/{keyword}', [BpjsController::class, 'referensiPoli']);
            Route::get('referensi/diagnosa/{keyword}', [BpjsController::class, 'referensiDiagnosa']);
            Route::post('sep', [BpjsController::class, 'sep']);
        });

        Route::prefix('antrol')->group(function () {
            Route::post('antrean', [AntrolController::class, 'antrean']);
            Route::post('taskid', [AntrolController::class, 'taskid']);
            Route::post('batal', [AntrolController::class, 'batal']);
        });

        Route::prefix('satusehat')->group(function () {
            Route::get('token', [SatuSehatController::class, 'token']);
            Route::post('encounter', [SatuSehatController::class, 'encounter']);
            Route::post('condition', [SatuSehatController::class, 'condition']);
            Route::post('observation', [SatuSehatController::class, 'observation']);
        });

        Route::post('raw/bpjs/{profile}/{path}', [BpjsController::class, 'raw'])->where('path', '.*');
        Route::post('raw/satusehat/{resource}', [SatuSehatController::class, 'raw'])->where('resource', '.*');
    });
});
