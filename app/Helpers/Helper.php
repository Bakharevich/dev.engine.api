<?php

namespace App\Helpers;

class Helper {
    public static function companyRating($rating)
    {
        $rating = round($rating * 2) / 2;

        if ($rating >= 5) $color = '00B551';
        elseif ($rating >= 4 && $rating < 5) $color = '73CF42';
        elseif ($rating >= 3 && $rating < 4) $color = 'C5DE35';
        elseif ($rating >= 2 && $rating < 3) $color = 'FFC800';
        elseif ($rating >= 0 && $rating < 2) $color = 'FF9600';

        $str = '';
        for ($i = 1; $i <= $rating; $i++) {
            $str .= "<i class=\"fa fa-star rating-star\" aria-hidden=\"true\" style=\"background-color: #{$color} !important;\"></i> ";
        }

        if (strpos($rating, ".")) {
            $str .= "<i class=\"fa fa-star-half-o rating-star\" aria-hidden=\"true\" style=\"background-color: #{$color} !important;\"></i> ";
        }

        return $str;
    }
}