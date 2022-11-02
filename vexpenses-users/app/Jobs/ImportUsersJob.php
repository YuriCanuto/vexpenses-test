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

class ImportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $url, private $token) { }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = file_get_contents($this->url, true);

        Storage::put("{$this->token}.csv", $file);

        $data = Storage::get("{$this->token}.csv");

        array_map(function ($row) {
            $data = str_getcsv($row);
            if (!empty($data[0])) {
                $user = new User();
                $user->name = $data[0];
                $user->email = $data[1];
                $user->save();
            }
        }, explode("\n", $data));
    }
}
