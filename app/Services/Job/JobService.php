<?php

namespace App\Services\Job;

use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;

class JobService
{
    public function listCompanyJobs(Request $request)
    {
        $limit = $request->query('limit', 10);
        $jobs = Auth::user()
            ->jobs()
            ->latest()
            ->paginate($limit);

        return JobResource::collection($jobs)->response()->getData(true);
    }

    public function createJob(array $data): JobPost
    {
        return Auth::user()->jobs()->create([...$data, 'published_at' => now()]);
    }

    public function updateJob(JobPost $job, array $data): JobPost
    {
        $data['published_at'] = $data['published_at'] ?? $job->published_at;
        $job->update($data);

        return $job;
    }

    public function deleteJob(JobPost $job): void
    {
        $job->delete();
    }
}
