<?php

namespace App\Services\Job;

use App\Models\Job;
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

    public function createJob(array $data): Job
    {
        return Auth::user()->jobs()->create([...$data, 'published_at' => now()]);
    }

    public function updateJob(Job $job, array $data): Job
    {
        $job->update($data);

        return $job;
    }

    public function deleteJob(Job $job): void
    {
        $job->delete();
    }
}
