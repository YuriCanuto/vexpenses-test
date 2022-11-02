<?php

namespace App\Services\Company;

use App\Jobs\ExportUsersByCompanyJob;
use App\Models\Company;
use App\Repositories\Company\Contract\ICompanyRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CompanyService {

    public function __construct(private ICompanyRepository $companyRepository) { }

    public function getByToken(int $int): ?Company
    {
        $company = $this->companyRepository->getByToken($int);

        if (!empty($company)) {
            return $company;
        }

        throw new ModelNotFoundException;
    }

    public function updateUser(int $token, int $id, array $data): bool {

        $company = $this->companyRepository->getByToken($token);

        if (!$company) {
            throw new ModelNotFoundException;
        }

        $user = $company->users()->findOrFail($id);

        \Log::info(json_encode($data));

        if ($this->companyRepository->updateUser($user, $data)) {
            ExportUsersByCompanyJob::dispatch($company);
        }

        return $this->companyRepository->updateUser($user, $data);
    }

    public function delete(Company $company): bool
    {
        if ($this->companyRepository->delete($company)) {
            Storage::delete("{$company->token}.csv");
            return true;
        }

        return false;
    }
}
