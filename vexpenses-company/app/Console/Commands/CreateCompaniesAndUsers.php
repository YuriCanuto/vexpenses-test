<?php

namespace App\Console\Commands;

use App\Jobs\CreateCompanyJob;
use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateCompaniesAndUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:create-companies {companies} {users} {--f|fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command para criar empresas e os usuários dessas empresas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $countCompanies = $this->argument('companies');
        $countUsers     = $this->argument('users');
        $fresh          = $this->option('fresh');

        $this->info('The command was started!');

        $this->info('Migrate fresh!');

        if ($fresh) {
            // Limpa todo o banco de dados para criação de novas
            // empresas e seus respectivos usuários
            Artisan::call('migrate:fresh');
        }

        $this->info('Create companies and users');

        CreateCompanyJob::dispatch($countCompanies, $countUsers)->onQueue('company_create');

        $this->info('The command was successful!');

        return Command::SUCCESS;
    }
}
