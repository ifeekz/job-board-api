<?php

namespace App\Services\Job;

use App\Jobs\ProcessCoverLetterUpload;
use App\Jobs\ProcessResumeUpload;
use App\Models\JobPost;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CandidateJobService
{
    /**
     * List published jobs with optional filters and pagination.
     */
    public function listPublicJobs(Request $request)
    {
        $cacheKey = build_cache_key([
            'location' => $request->location,
            'keyword' => $request->keyword,
            'page' => $request->page,
        ]);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            $query = JobPost::query()->whereNotNull('published_at');

            if ($request->filled('location')) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            if ($request->filled('is_remote')) {
                $isRemote = filter_var($request->is_remote, FILTER_VALIDATE_BOOLEAN);
                $query->where('is_remote', $isRemote);
            }

            if ($request->filled('keyword')) {
                $searchResults = JobPost::search($request->keyword)->get()->pluck('id')->toArray();

                if (count($searchResults) > 0) {
                    $query->whereIn('id', $searchResults);
                } else {
                    return $this->emptyPaginatedResult($request->input('limit', 10));
                }
            }

            $perPage = $request->input('limit', 10);

            return $query->paginate($perPage)->withQueryString();
        });
    }

    /**
     * Return an empty paginated response for no matches.
     */
    protected function emptyPaginatedResult(int $perPage)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
    }

    public function applyToJob($jobId, Request $request)
    {
        $candidateId = Auth::id();

        $application = JobApplication::create([
            'candidate_id' => $candidateId,
            'job_id' => $jobId,
        ]);

        $resumePath = $request->file('resume')->store('resumes');
        $coverLetterPath = $request->file('cover_letter')->store('cover_letters');

        ProcessResumeUpload::dispatch($application, $resumePath);
        ProcessCoverLetterUpload::dispatch($application, $coverLetterPath);

        return $application;
    }

    public function getCandidateStats(): array
    {
        $candidate = Auth::user();

        $totalAppliedJobs = $candidate->jobApplications()->count();

        return [
            'total_applied_jobs' => $totalAppliedJobs,
        ];
    }
}
