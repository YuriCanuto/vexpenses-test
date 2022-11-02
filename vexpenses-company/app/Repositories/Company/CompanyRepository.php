<?php

namespace App\Repositories\Company;

use App\Models\Company;
use App\Models\User;
use App\Repositories\Company\Contract\ICompanyRepository;

class CompanyRepository implements ICompanyRepository {

    protected $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function updateUser(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function getByToken(int $token): ?Company
    {
        return $this->company->where('token', $token)->first();
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }

}
