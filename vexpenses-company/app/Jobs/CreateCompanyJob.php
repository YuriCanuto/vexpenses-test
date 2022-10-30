<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCompanyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $companies;

    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $companies, int $users)
    {
        $this->companies = $companies;
        $this->users     = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Cria várias empresas a partir da quantidade passada
        $companies = Company::factory()->count($this->companies)->create();

        // Cria vários usuários para cada empresa
        foreach ($companies as $company) {
            CreateUsersJob::dispatch($company->id, $this->users);
        }
    }
}
