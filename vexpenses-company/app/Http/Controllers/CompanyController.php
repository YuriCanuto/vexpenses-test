<?php

namespace App\Http\Controllers;

use App\Services\Company\CompanyService;
use Illuminate\Http\Response;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    use ApiResponseHelpers;

    public function __construct(protected CompanyService $companyService) { }

    /**
     * @param Request $request
     * @param int $token
     * @param int $id
     * @return JsonResponse|void
     */
    public function updateUser(Request $request, int $token, int $id): JsonResponse {

        try {

            $data = $request->all();

            $this->companyService->updateUser($token, $id, $data);

            return $this->respondWithSuccess();

        } catch (\Exception $e) {

            return $this->respondNotFound('Empresa não encontrada');
        }

    }

    /**
     * @return Response
     * @throws Exception
     */
    public function delete(int $token): JsonResponse
    {
        try {

            $company = $this->companyService->getByToken($token);

            $this->companyService->delete($company);

            return $this->respondWithSuccess();

        } catch (\Exception $e) {

            return $this->respondNotFound('Empresa não encontrada');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function export(Request $request): JsonResponse
    {
        try {
            $company = $this->companyService->getByToken($request->token);

            $url = Storage::disk('local')->temporaryUrl(
                "{$company->token}.csv", now()->addMinutes(30)
            );

            $data = [
                'url' => $url
            ];

            return response()->json(($data), 200);

        } catch (\Exception $e) {
            return $this->respondNotFound('Empresa não encontrada'.$e->getMessage());
        }
    }
}
