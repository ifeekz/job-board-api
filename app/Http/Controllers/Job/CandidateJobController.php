<?php


namespace App\Http\Controllers\Job;

use App\Services\Job\CandidateJobService;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class CandidateJobController extends Controller
{
    public function __construct(protected CandidateJobService $jobService) {}

    public function index(Request $request)
    {
        $jobs = $this->jobService->listPublicJobs($request);
        return ApiResponse::success($jobs, 'Published jobs fetched successfully');
    }
}
