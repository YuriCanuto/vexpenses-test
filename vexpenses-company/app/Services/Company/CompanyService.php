<?php

namespace App\Services\Company;

use App\Models\Company;
use App\Repositories\Company\Contract\ICompanyRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function delete(Company $company): bool
    {
        if ($this->companyRepository->delete($company)) {
            Storage::delete("{$company->token}.csv");
            return true;
        }

        return false;
    }
}
