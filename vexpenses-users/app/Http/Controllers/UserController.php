<?php

namespace App\Http\Controllers;

use App\Jobs\ImportUsersByFtpJob;
use App\Jobs\ImportUsersJob;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return void
     */
    public function importCallback(Request $request)
    {
        try {
            ImportUsersByFtpJob::dispatch($request->token)->onQueue('import_user');
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 400);
        }
    }


    /**
     * @param int $token
     * @return JsonResponse
     */
    public function import(int $token): JsonResponse
    {
        try {
            ImportUsersByFtpJob::dispatch($token)->onQueue('import_user');
        } catch (\Exception $e) {
            return response()->json(['message' => 'error'], 400);
        }

        return response()->json(['message' => 'Importação iniciada']);
    }


    /**
     * @param Request $request
     * @param int $id
     * @param int $token
     * @return JsonResponse
     */
    public function update(Request $request, int $id, int $token): JsonResponse
    {
        try {
            $data = $request->all();

            $user = User::find($id);

            if (empty($user)) {
                return response()->json(['message' => 'error'], 400);
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->acceptJson()->put("http://172.26.80.1/api/company/{$token}/{$user->integration_id}", [
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            return response()->json(json_decode($response->body()));

        } catch (\Exception $e) {

            return response()->json(['message' => 'error'], 400);
        }
    }

    /**
     * @param int $token
     * @return JsonResponse|void
     */
    public function importLocal(int $token): JsonResponse
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
