<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @group controller
     */
    public function test_delete_status_404()
    {
        $response = $this->delete('/api/company/1');

        $response->assertStatus(404);
    }

    /**
     * @group controller
     */
    public function test_delete_status_200()
    {
        $company = Company::factory()->count(1)->create()->first();

        $this->assertNotEmpty($company);

        $response = $this->delete("/api/company/{$company->token}");

        $response->assertStatus(200);

        $company = Company::first();

        $this->assertEmpty($company);
    }

    /**
     * @group controller
     */
    public function test_export_status_404()
    {
        $response = $this->post("/api/company/export", ['token' => 1]);

        $response->assertStatus(404);
    }

    /**
     * @group controller
     */
    public function test_export_status_200()
    {
        $company = Company::factory()->count(1)->create()->first();

        $this->assertNotEmpty($company);

        $response = $this->post("/api/company/export", ['token' => $company->token]);

        $response->assertStatus(200);
    }

    /**
     * @group controller
     */
    public function test_update_status_404()
    {
        $response = $this->put("/api/company/1/1", []);

        $response->assertStatus(404);
    }

    /**
     * @group controller
     */
    public function test_update_status_200()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;

        $company = Company::factory()->count(1)->create()->first();
        $user = User::factory()->count(1)->create([
            'company_id' => $company->id,
            'name' => $name,
            'email' => $email
        ])->first();

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);

        $response = $this->put("/api/company/{$company->token}/{$user->id}", [
            'name'  => 'Laravel',
            'email' => 'laravel@mail.com'
        ]);

        $user = User::get()->first();

        $this->assertEquals('Laravel', $user->name);
        $this->assertEquals('laravel@mail.com', $user->email);

        $response->assertStatus(200);
    }
}
