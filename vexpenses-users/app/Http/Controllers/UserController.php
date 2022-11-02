<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUsersJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function import(int $token)
    {
        try {

            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->acceptJson()->post('http://172.26.80.1/api/company/export', [
                'token' => $token
            ]);

            if ($response->status() === 200) {

                $data = json_decode($response->body());

                ImportUsersJob::dispatch($data->url, $token)->onQueue('import_user');

                return response()->json(['message' => 'Importação iniciada']);
            }

            return response()->json(json_decode($response->body()), $response->status());

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'error'], 400);
        }
    }
}
