<?php

namespace App\Repositories\Company\Contract;

use App\Models\Company;

interface ICompanyRepository {

    public function getByToken(int $token): ?Company;
    public function delete(Company $company): bool;
}
