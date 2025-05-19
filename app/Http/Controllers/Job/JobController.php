<?php

namespace App\Http\Controllers\Job;

use App\Models\JobPost;
use App\Services\Job\JobService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Job\JobRequest;
use App\Helpers\ApiResponse;

class JobController extends Controller
{
    public function __construct(protected JobService $jobService) {}

    public function index(Request $request)
    {
        $jobs = $this->jobService->listCompanyJobs($request);
        return ApiResponse::success($jobs, 'Jobs fetched successfully');
    }

    public function store(JobRequest $request)
    {
        $job = $this->jobService->createJob($request->validated());
        return ApiResponse::success($job, 'Job created successfully', 201);
    }

    public function update(JobRequest $request, JobPost $job)
    {
        $job = $this->jobService->updateJob($job, $request->validated());
        return ApiResponse::success($job, 'Job updated successfully');
    }

    public function destroy(JobPost $jobPost)
    {
        $this->jobService->deleteJob($jobPost);
        return ApiResponse::success(null, 'Job deleted successfully');
    }
}
