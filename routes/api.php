<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CandidateAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Job\JobController;
use App\Http\Controllers\Job\CandidateJobController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/company/register', [CompanyAuthController::class, 'register']);
    Route::post('/company/login', [CompanyAuthController::class, 'login']);

    Route::post('/candidate/register', [CandidateAuthController::class, 'register']);
    Route::post('/candidate/login', [CandidateAuthController::class, 'login']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::middleware(['auth:company'])->group(function () {
        Route::get('/jobs', [JobController::class, 'index']);
        Route::get('/jobs/stats', [JobController::class, 'stats']);
        Route::post('/jobs', [JobController::class, 'store']);
        Route::put('/jobs/{job}', [JobController::class, 'update'])->middleware('ensure.company.owns.job');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->middleware('ensure.company.owns.job');
    });

    Route::get('/jobs/list', [CandidateJobController::class, 'index'])->name('jobs.list');
    Route::post('/jobs/{id}/apply', [CandidateJobController::class, 'apply'])
        ->middleware('auth:candidate')
        ->middleware('check.if.job.applied')
        ->name('job.apply');
});
