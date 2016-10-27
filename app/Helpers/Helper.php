<?php

namespace App\Helpers;

class Helper {
    public static function companyRating($rating, $size = '')
    {
        if (empty($size)) $size = 18;

        $rating = round($rating * 2) / 2;

        if ($rating >= 5) $color = '00B551';
        elseif ($rating >= 4 && $rating < 5) $color = '73CF42';
        elseif ($rating >= 3 && $rating < 4) $color = 'C5DE35';
        elseif ($rating >= 2 && $rating < 3) $color = 'FFC800';
        elseif ($rating >= 0 && $rating < 2) $color = 'FF9600';

        $str = '';
        for ($i = 1; $i <= $rating; $i++) {
            $str .= "<i class=\"fa fa-star rating-star\" aria-hidden=\"true\" style=\"background-color: #{$color} !important; font-size: {$size}px;\" title=\"{$rating}\"></i> ";
        }

        if (strpos($rating, ".")) {
            $str .= "<i class=\"fa fa-star-half-o rating-star\" aria-hidden=\"true\" style=\"background-color: #{$color} !important;  font-size: {$size}px;\" title=\"{$rating}\"></i> ";
        }

        return $str;
    }

    public static function isCompanyOpened($day, $open, $close)
    {
        if (date("D") == ucfirst($day)) {
            $current = "Y-m-d H:i";
            $openDate = date("Y-m-d") . " " . $open;
            $closeDate = date("Y-m-d") . " " . $close;

            if (strtotime($current) > strtotime($openDate) && strtotime($current) < strtotime($closeDate)) {
                return 'Opened';
            }
            else return 'Closed';
        }

    }
    public static function removeEmoji($text){
        return preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }
}