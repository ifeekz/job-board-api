<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Job;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyOwnsJob
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $job = $request->route('job');
        if (!$job || $job->company_id !== Auth::id()) {
            throw new HttpException(403, 'Unauthorized');
        }

        return $next($request);
    }
}
