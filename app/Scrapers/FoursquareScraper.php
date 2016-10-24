<?php
namespace App\Scrapers;

class FoursquareScraper {
    public static function photos($page)
    {
        $reg = "|<img src=\"(.+?200x200.+?)\".*?>|is";
        preg_match_all($reg, $page, $matches);

        $photos = [];

        if (!empty($matches)) {
            foreach ($matches[1] as $photo) {
                $photo = preg_replace("|200x200|", "width960", $photo);

                $photos[] = $photo;
            }
        }

        return $photos;
    }

    public static function reviews($page)
    {
        $reg= "|<ul id=\"tipsList\">(.+?)</ul>|is";
        preg_match_all($reg, $page, $matches);

        $reviews = [];

        if (!empty($matches[1][0])) {
            $reg = "|<span class=\"userName\">(.+?)</span>|is";
            preg_match_all($reg, $matches[1][0], $name);

            $reg = "|<span class=\"tipDate\">(.+?)</span>|is";
            preg_match_all($reg, $matches[1][0], $date);

            $reg = "|<div class=\"tipText\">(.+?)</div>|is";
            preg_match_all($reg, $matches[1][0], $tips);

            if (!empty($name[1]) && !empty($tips[1]) && (count($name[1]) == count($tips[1]))) {
                foreach ($name[1] as $index => $value) {
                    $reviews[strtotime($date[1][$index])] = [
                        'name'   => strip_tags($name[1][$index]),
                        'review' => strip_tags($tips[1][$index]),
                        'date'   => date("Y-m-d", strtotime($date[1][$index])),
                        'rating' => 0
                    ];
                }

                ksort($reviews);
            }

            print_r($reviews);

            //print_r($name);
            //print_r($reviews);
        }

        return $reviews;
    }
}