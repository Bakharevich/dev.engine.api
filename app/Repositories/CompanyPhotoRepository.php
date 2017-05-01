<?php
namespace App\Repositories;

use App\CompanyPhoto;

class CompanyPhotoRepository {
    public static function create($data)
    {
        // get company info
        $company = \App\Repositories\CompanyRepository::find($data['company_id']);

        // get site
        $site = \App\Site::find($company['site_id']);

        // setup url
        $url = $site->media_url . 'companies/500/' . $data['filename'];

        $photo = \App\CompanyPhoto::create([
            'company_id' => $data['company_id'],
            'filename' => $data['filename'],
            'url' => $url
        ]);

        // update pos
        $photo->pos = $photo->id;
        $photo->save();
    }

    public static function getByCompanyId($companyId)
    {
        $photos = \App\CompanyPhoto::where('company_id', $companyId)->get();


        return $photos;
    }
}