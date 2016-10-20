<?php

namespace App\Scrapers;

interface ScraperInterface {
    public function process($url);

    public function getPagePartOfUrl($page);
}