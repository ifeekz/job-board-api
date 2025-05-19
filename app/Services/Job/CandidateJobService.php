<?php

namespace App\Services\Job;

use App\Models\Job;
use Illuminate\Http\Request;

class CandidateJobService
{
    /**
     * List published jobs with optional filters and pagination.
     */
    public function listPublicJobs(Request $request)
    {
        $query = Job::query()->whereNotNull('published_at');

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('is_remote')) {
            $isRemote = filter_var($request->is_remote, FILTER_VALIDATE_BOOLEAN);
            $query->where('is_remote', $isRemote);
        }

        if ($request->filled('keyword')) {
            $searchResults = Job::search($request->keyword)->get()->pluck('id')->toArray();

            if (count($searchResults) > 0) {
                $query->whereIn('id', $searchResults);
            } else {
                return $this->emptyPaginatedResult($request->input('limit', 10));
            }
        }

        $perPage = $request->input('limit', 10);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Return an empty paginated response for no matches.
     */
    protected function emptyPaginatedResult(int $perPage)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
    }
}
