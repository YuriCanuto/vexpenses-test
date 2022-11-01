<?php

namespace App\Http\Controllers;

use App\Services\Company\CompanyService;
use Illuminate\Http\Response;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Exception;

class CompanyController extends Controller
{
    use ApiResponseHelpers;

    public function __construct(protected CompanyService $companyService) { }

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
}
