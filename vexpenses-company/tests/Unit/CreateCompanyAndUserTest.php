<?php

namespace Tests\Unit;

use App\Exports\UsersExport;
use App\Jobs\CreateCompanyJob;
use App\Jobs\CreateUsersJob;
use App\Jobs\ExportUsersJob;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use Excel;

class CreateCompanyAndUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_company_and_user()
    {
        Bus::fake();

        $this->artisan('model:create-companies', [
            'companies' => 1,
            'users'     => 1,
        ]);

        Bus::assertDispatched(CreateCompanyJob::class);
    }

    /**
     * @group job
     */
    public function test_create_company_job()
    {
        $company = Company::all();

        $this->assertEmpty($company);

        $job = new CreateCompanyJob(1, 1);
        $job->handle();

        $company = Company::all();
        $this->assertNotEmpty($company);
    }

    public function test_job_create_user()
    {
        $company = Company::factory()->count(1)->create(['id' => 1])->first();
        $user = User::all();

        $this->assertEmpty($user);

        $job = new CreateUsersJob($company->id, 1);
        $job->handle();

        $user = User::all();
        $this->assertNotEmpty($user);
    }

    public function test_export_users()
    {
        Bus::fake();

        $this->artisan('export:users');

        Bus::assertDispatched(ExportUsersJob::class);
    }

    /**
     * @group job
     */
    public function test_export_users_job()
    {
        Company::factory()->count(2)->create();

        Excel::shouldReceive('store')->times(2)->andReturn(true);

        $job = new ExportUsersJob();
        $job->handle();
    }
}
