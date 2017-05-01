<?php
namespace App\Repositories;

use App\UserCompany;

class UserRepository {
    public function getCompanies($userId)
    {
        $query = UserCompany::query();
        $query->join('companies', 'user_company.company_id', '=', 'companies.id');
        $query->where('user_company.user_id', $userId);
        $companies = $query->paginate(20);

        return $companies;
    }
}