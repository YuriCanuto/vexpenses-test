<?php

namespace App\Console\Commands;

use App\Jobs\ExportUsersJob;
use Illuminate\Console\Command;

class ExportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta todos os usuários relacionados a uma determinada empresa';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('The command was started!');

        ExportUsersJob::dispatch();

        $this->info('The command was successful!');

        return Command::SUCCESS;
    }
}
