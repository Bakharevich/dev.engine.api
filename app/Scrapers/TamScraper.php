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
                $parse = parse_url($value);

                if (!empty($parse['host'])) {
                    $domain = $parse['scheme'] . "://" . $parse['host'] . $parse['path'];
                }
                else {
                    $domain = "";
                }

                $companies[] = [
                    'domain' => $domain,
                    'page' => $domain,
                    'photos' => $domain . 'foto/',
                ];
            }
        }

        // slice array of companies
        if (!empty($limit)) $companies = array_slice($companies, 0, $limit);

        return $companies;
    }

    public static function name($page)
    {
        $reg = "|<h1 itemprop=\"name\".*?>(.+?)</h1>|";
        preg_match_all($reg, $page, $matches);

        //print_r($matches); exit();

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
        else return 0;

    }

    public static function priceRange($page)
    {
        // for now, website doesn't have such option

        return 0;
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
        $reg = "|<div.*?class=\"b-article\".*?>(.+?)</div>|is";
        preg_match_all($reg, $page, $matches);

        //print_r($matches);

        $res = "";

        if (!empty($matches[1][0])) {
            $reg = "|<p.*?>(.+?)</p>|is";
            preg_match_all($reg, $matches[1][0], $descrip);

            // get all text paragraphs
            if (!empty($descrip[1])) {
                foreach ($descrip[1] as $par) {
                    $par = strip_tags($par, '<br>');

                    if (!empty($par)) $res .= "<p>" . trim($par) . "</p>";
                }
            }
        }

        // get license
        $reg = "|<div style=\"clear:both;\">(.+?)</div><div>(.+?)</div>|";
        preg_match_all($reg, $page, $license);

        if (!empty($license[1][0])) $res .= "<p class=\"license\">" . strip_tags($license[1][0]) . "</p>";
        if (!empty($license[2][0])) $res .= "<p class=\"license\">" . strip_tags($license[2][0]) . "</p>";

        return $res;
    }

    public static function hours($page)
    {
        $reg = "|<div class=\"day\">(.+?)</div>.*?<div class=\"time\">.*?<span>(.+?)</span>|is";
        preg_match_all($reg, $page, $matches);

        $hours = [];

        //print_r($matches); exit();

        // get working days
        if (!empty($matches[1]) && !empty($matches[2])) {
            foreach ($matches[1] as $index => $value) {
                if ($matches[2][$index] == "Выходной") {
                    $open = "00:00";
                    $close = "00:00";
                }
                else {
                    $arr = explode("-", $matches[2][$index]);
                    $open = $arr[0];
                    $close = $arr[1];
                }

                $hours[] = [
                    'day'   => TamScraper::getFormattedDay($matches[1][$index]),
                    'open'  => $open,
                    'close' => $close
                ];
            }
        }

        //print_r($hours); exit();

        return $hours;
    }

    public static function options($page)
    {
        $reg = "|<div class=\"catalog-company-section m-features js-cut_wrapper\" id=\"featureslist\">(.+?)</div>|";
        preg_match_all($reg, $page, $matches);

        $options = [];

        if (!empty($matches[1][0])) {
            $reg = "|<p class=\"title\">(.+?)</p><p>(.+?)</p>|";
            preg_match_all($reg, $matches[1][0], $opt);

            if (!empty($opt) && (count($opt[1]) == count($opt[2]))) {
                foreach ($opt[1] as $index => $value) {
                    // check if such word blocked
                    if (TamScraper::getOptionGroupBlockedName($opt[1][$index])) continue;

                    $valArr = explode(",", $opt[2][$index]);

                    foreach ($valArr as $val) {
                        $options[$opt[1][$index]][] = trim(mb_convert_case($val, MB_CASE_TITLE, "UTF-8"));
                    }
                }
            }
        }

        return $options;
    }

    public static function photos($page)
    {
        $reg = "|<a href=\"(.*?4sqi.net.*?)\".*?>|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1])) {
            return $matches[1];
        }
    }

    public static function foursquareUrl($page)
    {
        $reg = "|<a href=\"(.*?4sqi.net.*?)\".*?>|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return $matches[1][0];
        }
    }

    public static function getOptionGroupBlockedName($name)
    {

        $words = [
            'TAM.BY рекомендует',
            'Стоимость заказа на 1 человека'
        ];

        if (in_array($name, $words)) return true;
    }

    public static function getFormattedDay($day)
    {
        $days = [
            'Понедельник' => 'Mon',
            'Вторник' => 'Tue',
            'Среда' => 'Wed',
            'Четверг' => 'Thu',
            'Пятница' => 'Fri',
            'Суббота' => 'Sat',
            'Воскресенье' => 'Sun',
        ];

        return $days[$day];
    }

    public static function getSlug($url)
    {
        $parse = parse_url($url);

        $slug = '';

        if (!empty($parse['host'])) {
            $domain = $parse['scheme'] . "://" . $parse['host'] . $parse['path'];

            preg_match_all("|:\/\/(.+?)\.tam\.by|", $domain, $matches);

            if (!empty($matches[1][0])) {
                $slug = $matches[1][0];
            }
        }

        return $slug;
    }
}