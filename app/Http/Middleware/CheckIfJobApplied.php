<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfJobApplied
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $candidate = Auth::guard('candidate')->user();
        $jobId = $request->route('id');

        if (
            $candidate &&
            JobApplication::where('candidate_id', $candidate->id)
            ->where('job_id', $jobId)
            ->exists()
        ) {
            return ApiResponse::error(
                'You have already applied to this job.',
                null,
                400
            );
        }

        return $next($request);
    }
}
