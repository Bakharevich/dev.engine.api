<?php
namespace App\Scrapers;

use App\Category;
use App\Company;
use App\CompanyPhoto;
use App\CompanyReview;
use App\CompanyHour;
use App\Option;
use App\OptionGroup;
use App\Scrapers\Scraper;
use App\Scrapers\ScraperInterface;
use App\Repositories\CompanyRepository;

use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class TamScraper
{
    public static function getCompaniesList($page, $limit = 0)
    {
        $reg = '|<a itemprop="url" href="(.+?)">|';

        preg_match_all($reg, $page, $companiesScraped);

        $companies = [];
        if (!empty($companiesScraped)) {
            foreach ($companiesScraped[1] as $index => $value) {
                $companies[] = [
                    'domain' => $value,
                    'page' => $value,
                    'photos' => $value . 'foto/',
                ];
            }
        }

        // slice to 1 company for testing purposes
        if (!empty($limit)) $companies = array_slice($companies, 0, $limit);

//        $companies = [];
//        $companies[] = [
//            'page' => 'https://yelp.com/biz/museum-dental-suites-london?sort_by=date_desc',
//            'photos' => 'https://yelp.com/biz_photos/yarok-berlin',
//            'domain' => 'museum-dental-suites-london'
//        ];

        return $companies;
    }

    public static function name($page)
    {
        $reg = "|<h1 itemprop=\"name\">(.+?)</h1>|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return trim(htmlspecialchars_decode($matches[1][0], ENT_QUOTES));
        }
        else return "";
    }

    public static function address($page)
    {
        $reg = "|<p class=\"address\">.*?<a onclick=\".*?\" href=\".*?/karta/\">(.+?)</a>.*?</p>|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return trim($matches[1][0]);
        }
        else return "";
    }

    public static function telephone($page)
    {
        $reg = "|<div id=\"tab-phones\" class=\"tab-pane active\">(.+?)</div>|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[0][0])) {
            $reg = "|<li>(.+?)</li>|is";
            preg_match_all($reg, $matches[0][0], $tel);

            $res = "";
            foreach ($tel[1] as $number) {
                $number = preg_replace("/[^0-9,.+\s]/", "", $number);

                $res .= trim($number) . ";";
            }


            return trim($res);
        }
        else return '';
    }

    public static function website($page)
    {
        $reg = "|<div class=\"company_card-block web-info-block\">.*?<a href=\"#\".*?>(.+?)</a>|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            if (!preg_match("/http:\/\/|https:\/\//", $matches[1][0])) {
                $matches[1][0] = "http://" . $matches[1][0];
            }

            return $matches[1][0];
        }
        else return "";

    }

    public static function rating($page)
    {
        $reg = "|<span class=\"current_rate rating-(.+?)star\">|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            $matches[1][0] = preg_replace("|_|", ".", $matches[1][0]);

            return $matches[1][0];
        }
        else return "";

    }

    public static function priceRange($page)
    {
        // for now, website doesn't have such option

        return '';
    }

    public static function latitude($page)
    {
        $reg = "|<img src=\"https://static-maps.yandex.ru/1.x/.*?map&pt=.*?,(.+?),pm2rdm\" alt=\"\">|";

        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return $matches[1][0];
        }
        else return "";
    }

    public static function longitude($page)
    {
        $reg = "|<img src=\"https://static-maps.yandex.ru/1.x/.*?map&pt=(.+?),.*?,pm2rdm\" alt=\"\">|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return $matches[1][0];
        }
        else return "";
    }

    public static function description($page)
    {
        $reg = "|<div class=\"b-article js-cut_wrapper\">(.+?)</div>|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            $reg = "|<p.*?>(.+?)</p>|is";
            preg_match_all($reg, $matches[1][0], $descrip);

            if (!empty($descrip[1])) {
                $res = "";

                foreach ($descrip[1] as $par) {
                    $par = strip_tags($par, '<br>');

                    if (!empty($par)) $res .= "<p>" . $par . "</p>";
                }

                return $res;
            }
        }
        else return "";
    }


}