<?php
namespace App\Repositories;

use App\CompanyPhoto;
use File;

class CompanyContactRepository {
    public static function create($data)
    {
        $contact = \App\CompanyContact::create([
            'company_id'    => $data['company_id'],
            'name'          => $data['name'],
            'surname'       => $data['surname'],
            'position'      => $data['position'],
            'tel'           => $data['position'],
            'email'         => $data['email']
        ]);

        return $contact;
    }
}