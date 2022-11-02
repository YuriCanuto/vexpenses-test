<?php

namespace App\Repositories\Company\Contract;

use App\Models\Company;
use App\Models\User;

interface ICompanyRepository {

    public function getByToken(int $token): ?Company;
    public function updateUser(User $user, array $data): bool;
    public function delete(Company $company): bool;
}
