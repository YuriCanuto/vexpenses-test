<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\User;
use Generator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, ShouldQueue
{
    protected $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function collection() {
        return collect($this->array());
    }

    public function array(): array {
        return iterator_to_array($this->getAllUsersByCompany());
    }



    public function getAllUsersByCompany(): Generator
    {
        foreach ($this->company->users as $user) {
            yield [
                'name'  => $user->name,
                'email' => $user->email,
            ];
        }
    }
}
