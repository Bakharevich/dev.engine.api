<?php

namespace App\Scrapers;

use File;
use DB;
use Str;

class YelpScraper {
    public static function companiesList($page)
    {
        $reg = '|href=\"/biz/(.+?)\" data-hovercard-id=\".*?\"|';

        preg_match_all($reg, $page, $companiesScraped);

        $companies = [];
        if (!empty($companiesScraped)) {
            foreach ($companiesScraped[1] as $index => $value) {
                // remove unnecessary symbols
                $value = preg_replace("|(\?.*)|", "", $value);

                $companies[] = [
                    'domain' => $value,
                    'page' => 'https://yelp.com/biz/' . $value . '?sort_by=date_desc',
                    'photos' => 'https://yelp.com/biz_photos/' . $value,
                ];
            }
        }

        return $companies;
    }

    public static function name($data)
    {
        /*$reg = '|<h1.*?>(.+?)</h1>|is';*/
        $reg = "|Start your review of <strong>(.+?)</strong>|";
        preg_match_all($reg, $data, $matches);

        if (empty($matches[1][0])) {
            $reg = "|With so few reviews, your opinion of <strong>(.+?)</strong> could be huge|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Recommended Reviews <b>(.+?)</b></h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Recommended Reviews <b>(.+?)</b></h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Photos for (.+?)</h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Photo for (.+?)</h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|Hey there trendsetter! You could be the first review for.*?<strong>(.+?)</strong>|is";
            preg_match_all($reg, $data, $matches);
        }

        if (!empty($matches[1][0])) {
            return trim(htmlspecialchars_decode($matches[1][0], ENT_QUOTES));
        }
    }

    public static function address($data)
    {
        $reg = '|<span itemprop="streetAddress">(.+?)</span>|';
        preg_match_all($reg, $data, $street);
        $street = !empty($street[1][0]) ? trim($street[1][0]) : '';

        $reg = '|<span itemprop="postalCode">(.+?)</span>|';
        preg_match_all($reg, $data, $postalCode);
        $postalCode = !empty($postalCode[1][0]) ? trim($postalCode[1][0]) : '';

        $reg = '|<span itemprop="addressLocality">(.+?)</span>|';
        preg_match_all($reg, $data, $locality);
        $locality = !empty($locality[1][0]) ? trim($locality[1][0]) : '';

        $address = $street . ", " . $postalCode . ", " . $locality;

        $address = str_replace("<br>", ", ", $address);

        return $address;
    }

    public static function tel($data)
    {
        $reg = '|<span class="biz-phone.*?>(.+?)</span>|is';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    public static function website($data)
    {
        $reg = '|<a href="\/biz_redir\?url=(.+?)&.*?">|is';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return urldecode(trim($matches[1][0]));
    }

    public static function rating($data)
    {
        $reg = '|<meta itemprop=\"ratingValue\" content="(.+?)">|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return urldecode(trim($matches[1][0]));
    }

    public static function pricerange($data)
    {
        $reg = '|<span class="business-attribute price-range".*?>(.+?)</span>|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return count(trim($matches[1][0]));
    }

    public static function latitude($data)
    {
        $reg = '|latitude&#34;: (.+?),|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    public static function longitude($data)
    {
        $reg = '|longitude&#34;: (.+?)}|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    public static function photos($page)
    {
        $reg = "|<img.*?class=\"photo-box-img\".*?height=\"226\".*?src=\"(.+?)/258s.jpg\".*?width=\"226\">|";
        preg_match_all($reg, $page, $matches);

        $photos = [];

        if (!empty($matches[1])) {
            foreach ($matches[1] as $photo) {
                $photos[] = $photo . "/o.jpg";
            }

            return $photos;
        }

        return $photos;
    }

    public static function reviews($data)
    {
        $reg = "|<script type=\"application/ld\+json\">(.+?)</script>|s";
        preg_match_all($reg, $data, $matches);

        $reviews = [];

        if (!empty($matches[1][0])) {
            $arr = json_decode(trim($matches[1][0]));

            if (!empty($arr->review)) {
                foreach ($arr->review as $index => $value) {
                    $name = !empty($value->author) ? $value->author : '';
                    $review = !empty($value->description) ? $value->description : '';
                    $date = !empty($value->datePublished) ? $value->datePublished : '';
                    $rating = !empty($value->reviewRating->ratingValue) ? $value->reviewRating->ratingValue : '';

                    $review = str_replace("\n\n", "<br/><br/>", $review);

                    $reviews[] = [
                        'name' => $name,
                        'review' => $review,
                        'date' => $date,
                        'rating' => $rating
                    ];
                }

                // sort array, so early reviews will be in top
                krsort($reviews);
            }

        }

        //echo "<pre>";print_r($reviews);echo "Decoded JSON:"; print_r($arr);exit();

        return $reviews;
    }

    public static function hours($data)
    {
        $reg = "#<th scope=\"row\">(Mon|Tue|Wed|Thu|Fri|Sat|Sun)</th>.*?<td>.*?<span class=\"nowrap\">(.+?)</span> - <span class=\"nowrap\">(.+?)</span>.*?</td>#is";
        preg_match_all($reg, $data, $matches);

        $hours = [];
        if (!empty($matches)) {
            foreach ($matches[1] as $index => $value) {
                $hours[] = [
                    'day' => strtolower($matches[1][$index]),
                    'open' => date("H:i", strtotime($matches[2][$index])),
                    'close' => date("H:i", strtotime($matches[3][$index]))
                ];
            }
        }

        return $hours;
    }

    public static function options($data)
    {
        $reg = "#<dl>.*?<dt class=\"attribute-key\">(.+?)</dt>.*?<dd>(.+?)</dd>.*?</dl>#is";
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches); echo "</pre>";

        $options = [];
        if (!empty($matches)) {
            foreach ($matches[1] as $index => $value) {
                $groupName = trim($matches[1][$index]);
                $options[$groupName][] = trim($matches[2][$index]);
            }
        }
        //print_r($options); exit();

        return $options;
    }

    public static function types($data)
    {
        $reg = "#<span class=\"category-str-list\">(.+?)</span>#is";
        preg_match_all($reg, $data, $preMatches);

        if (!empty($preMatches)) {
            $reg = "|<a.*?>(.+?)</a>|";
            preg_match_all($reg, $preMatches[1][0], $matches);

            if (!empty($matches[1])) return $matches[1];
        }
    }

    public static function processtypes($types, $company)
    {
        /*
        // get category name
        $category = Category::where('id', $this->getParam('category_id'))->first();

        // check if "options_groups" exist for such category_id (using help column "comment")
        $ifOptionGroupExists = OptionGroup::where('comment', 'Category ' . $this->getParam('category_id'))->first();

        if (!$ifOptionGroupExists) {
            // if not, create with "name" = type, "icon" = of that category, "comment" = category_id
            $optionGroup = OptionGroup::create([
                'site_id' => $this->getParam('site_id'),
                'name' => 'Type',
                'icon' => $category->icon,
                'comment' => 'Category ' . $this->getParam('category_id')
            ]);

            $optionGroupId = $optionGroup->id;

            // link option_group to category_id
            Category::find($this->getParam('category_id'))->options_groups()->attach([
                'option_group_id' => $optionGroupId
            ]);
        }
        else {
            $optionGroupId = $ifOptionGroupExists->id;
        }

        // using last_insert_id add option to "options" with "type" name and last_insert_id
        foreach ($types as $type) {
            // check if such option already exists
            $ifOptionExists = Option::where('option_group_id', $optionGroupId)->where('name', $type)->first();

            if (!$ifOptionExists) {
                $optionData = Option::create([
                    'option_group_id' => $optionGroupId,
                    'name' => $type
                ]);

                $optionId = $optionData->id;
            }
            else {
                $optionId = $ifOptionExists->id;
            }

            // add new type options to company
            Company::find($company->id)->options()->attach([
                'option_id' => $optionId
            ]);
        }
        */
    }
}