<?php

namespace App\Jobs;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessResumeUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $application;
    protected $resumePath;

    /**
     * Create a new job instance.
     */
    public function __construct(JobApplication $application, string $resumePath)
    {
        $this->application = $application;
        $this->resumePath = $resumePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->application->update(['resume' => $this->resumePath]);
    }
}
