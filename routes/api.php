<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use App\Jobs\RunJob;

Route::middleware('auth:sanctum')->group(function () {
    Route::group([
        'prefix' => 'jobs',
    ], function ($router) {
        Route::post('/send', function () {
            $batch = Bus::batch([
                RunJob::dispatch()->delay(now()->addSeconds(15))
            ])->dispatch();
            return response()->json([
                'job_id' => $batch->id
            ]);
        })->name('api.jobs.send');
        Route::get('/{id}/status', function ($id) {
            $batch = Bus::findBatch($id);
            if ($batch->finished()) return response()->json(['status' => 2]);
            if ($batch->canceled()) return response()->json(['status' => 0]);
            return response()->json(['status' => 1]);
        })->name('api.jobs.status');
    });
});
