<?php

namespace App\Jobs;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCoverLetterUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $application;
    protected $coverLetterPath;

    /**
     * Create a new job instance.
     */
    public function __construct(JobApplication $application, string $coverLetterPath)
    {
        $this->application = $application;
        $this->coverLetterPath = $coverLetterPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->application->update(['cover_letter' => $this->coverLetterPath]);
    }
}
