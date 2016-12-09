<?php
namespace App\Repositories;
use App\Page;

class PageRepository extends Repository {
    public function model()
    {
        return 'App\Company';
    }

    public static function getContent($siteId, $section)
    {
        $res = Page::where('site_id', $siteId)->where('section', $section)->first();

        if ($res) return $res->content;
    }
}