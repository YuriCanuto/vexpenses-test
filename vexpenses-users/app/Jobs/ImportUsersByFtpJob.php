<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportUsersByFtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $token) { }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = Storage::disk('ftp')->get("{$this->token}.csv");

        array_map(function ($row) {
            $data = str_getcsv($row);
            if (!empty($data[0])) {
                User::updateOrCreate(
                    ['integration_id' => $data[0]],
                    ['name' => $data[1], 'email' => $data[2]]
                );
            }
        }, explode("\n", $file));
    }
}
