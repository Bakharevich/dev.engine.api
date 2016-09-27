<?php

namespace App\Helpers;

interface ApiContract
{
    public function getSites();
    public function getCategoriesByDomain();
}