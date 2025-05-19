<?php


namespace App\Http\Controllers\Job;

use App\Services\Job\CandidateJobService;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Job\JobApplicationRequest;

class CandidateJobController extends Controller
{
    public function __construct(protected CandidateJobService $candidateJobService) {}

    public function index(Request $request)
    {
        $jobs = $this->candidateJobService->listPublicJobs($request);
        return ApiResponse::success($jobs, 'Published jobs fetched successfully');
    }

    public function apply(JobApplicationRequest $request, $jobId)
    {
        $application = $this->candidateJobService->applyToJob($jobId, $request);

        return ApiResponse::success($application, 'Job application submitted successfully', 201);
    }

    public function stats()
    {
        $stats = $this->candidateJobService->getCandidateStats();

        return ApiResponse::success($stats, 'Candidate stats retrieved successfully');
    }
}
