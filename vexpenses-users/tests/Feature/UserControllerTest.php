<?php

namespace Tests\Feature;

use App\Jobs\ImportUsersByFtpJob;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @group UserController
     */
    public function test_import_callback_status_500()
    {
        $response = $this->post('/api/users/import', []);

        $response->assertStatus(500);
    }

    /**
     * @group UserController
     */
    public function test_import_callback_status_200()
    {
        Bus::fake();

        $response = $this->post('/api/users/import', ['token' => '1']);

        $response->assertStatus(200);

        Bus::assertDispatched(ImportUsersByFtpJob::class);
    }

    /**
     * @group UserController
     */
    public function test_import_users_by_ftp_job()
    {
        $this->artisan('migrate:refresh');

        $users = User::all();
        $this->assertEmpty($users);

        $file = file_get_contents(__DIR__.'/../mock/file.csv');

        $mock = $this->partialMock(FilesystemAdapter::class, function (MockInterface $mock) use($file) {
            $mock->shouldReceive('get')->once()->andReturn($file);
        });

        Storage::shouldReceive('disk')->once()->andReturn($mock);

        $job = new ImportUsersByFtpJob(1);
        $job->handle();

        $users = User::all();
        $this->assertNotEmpty($users);
    }

    /**
     * @group UserController
     */
    public function test_import_status_200()
    {
        $response = $this->get('/api/users/import/1');

        $response->assertStatus(200);
    }

    /**
     * @group UserController
     */
    public function test_update_user_status_400()
    {
        $this->artisan('migrate:refresh');

        $response = $this->put('/api/users/1/1',[
            'name' => 'Laravel',
            'email' => 'laravel@mail.com'
        ]);

        $response->assertStatus(400);
    }

    /**
     * @group UserController
     */
    public function test_update_user_status_200()
    {
        $this->artisan('migrate:refresh');

        $user = User::factory()->count(1)->create([
            'integration_id' => 1
        ])->first();

        $response = $this->put("/api/users/{$user->id}/1",[
            'name' => 'Laravel',
            'email' => 'laravel@mail.com'
        ]);

        $response->assertStatus(200);
    }
}
