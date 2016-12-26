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

class InfoisinfoScraper
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
        $reg = "|title_com: \"(.+?)\",|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return trim(htmlspecialchars_decode($matches[1][0], ENT_QUOTES));
        }
        else return "";
    }

    public static function address($page)
    {
        $reg = "|address: \"(.+?)\",|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return trim($matches[1][0]);
        }
        else return "";
    }

    public static function telephone($page)
    {
        $reg = "|phone: \"(.+?)\",|is";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return trim($matches[1][0]);
        }
        else return '';
    }

    public static function website($page)
    {
        $reg = "|\"url\": \"(.+?)\",|is";
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
        $reg = "|\"ratingValue\": (.+?) |is";
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
        $reg = "|\"latitude\": \"(.+?)\",|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return $matches[1][0];
        }
        else return "";
    }

    public static function longitude($page)
    {
        $reg = "|\"longitude\": \"(.+?)\"|";
        preg_match_all($reg, $page, $matches);

        if (!empty($matches[1][0])) {
            return $matches[1][0];
        }
        else return "";
    }

    public static function description($page)
    {
        $reg = "|\"description\": \"(.+?)\",|is";
        preg_match_all($reg, $page, $matches);

        $descrip = "";

        if (!empty($matches[1][0])) {
            $descrip = str_replace('\n\n', '<br/>', $matches[1][0]);
            $descrip = str_replace('\u', ' &dash; ', $descrip);

            $descrip = strip_tags($descrip, '<br>');
        }

        return $descrip;
    }

    public static function hours($page)
    {
        $reg = "|<td>.*?&nbsp;&nbsp;(.+?)</td>.*?<td>(.+?)</td>.*?<td>(.+?)</td>|is";
        preg_match_all($reg, $page, $matches);

        $hours = [];

        // get working days
        if (!empty($matches[1]) && !empty($matches[2])) {
            foreach ($matches[1] as $index => $value) {
                $day = trim($matches[1][$index]);
                $open = date("H:i", strtotime(trim($matches[2][$index])));
                $close = date("H:i", strtotime(trim($matches[3][$index])));

                $hours[] = [
                    'day'   => InfoisinfoScraper::getFormattedDay($day),
                    'open'  => $open,
                    'close' => $close
                ];
            }
        }

        return $hours;
    }

    public static function options($page)
    {
        $options = [];

        return $options;
    }

    public static function photos($page)
    {
        $reg = "<img src=\"(.+fotos.+?)\".*?>";
        preg_match_all($reg, $page, $matches);

        return $matches[1];
    }

    public static function getFormattedDay($day)
    {
        $days = [
            'Monday' => 'Mon',
            'Tuesday' => 'Tue',
            'Wednesday' => 'Wed',
            'Thursday' => 'Thu',
            'Friday' => 'Fri',
            'Saturday' => 'Sat',
            'Sunday' => 'Sun',
        ];

        return $days[$day];
    }
}