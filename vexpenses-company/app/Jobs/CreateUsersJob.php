<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;

    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $company, int $users)
    {
        $this->company = $company;
        $this->users   = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::factory()->count($this->users)->create(['company_id' => $this->company]);
    }
}
