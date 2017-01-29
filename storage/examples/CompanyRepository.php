<?php
/**
 * File made for not dev purposes.
 */
class CompanyRepository {
    public function getByCategory($categoryId, $selectedOptions = [])
    {

        $query = Company::query();

        // get companies for specific categories
        $query->whereHas('categories', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        });

        // get according options
        if (!empty($selectedOptions)) {
            $query->whereHas('options', function ($query) use ($selectedOptions) {
                if (!empty($selectedOptions)) $query->whereIn('option_id', $selectedOptions);
            });
        }

        $query->orderBy('is_premium', 'desc')->orderBy('pos', 'asc');

        $companies = $query->paginate(20);

        return $companies;
    }

    public function getByCategory2($categoryId, $selectedOptions = [])
    {
        /* Case without models */
        $categoryId = 1;
        $selectedOptions = [432];

        $query = DB::table('companies');

        $query->join('category_company', 'category_company.company_id', '=', 'companies.id');
        $query->where('category_company.category_id', $categoryId);

        /* Selected options case */
        //$query->join('company_option', 'company_option.company_id', '=', 'companies.id');
        //$query->whereIn('company_option.option_id', $selectedOptions);

        /* For pagination */
        $query->skip(90)->take(20);

        $companies = $query->get();

        dd($companies);

        //$companies = $query->take(25);

        //return $companies;
    }
}


/*
 * GET YELP STATS OF CATEGORY
 */
/*
 * Route::get('/categoriesstats/', function() {
    $category = 'https://www.yelp.com/search?find_loc=Kuala+Lumpur&cflt=shopping';

    $categoryPage = file_get_contents($category);

    $reg = "#<a class='category-browse-anchor js-search-header-link' href=\"(.+?)\">(.+?)</a>#";
    preg_match_all($reg, $categoryPage, $matches);

    //echo "<pre>"; print_r($matches);

    $result = [];

    if (!empty($matches)) {
        $i = 1;
        foreach ($matches[1] as $index => $value) {
            if ($i < 49) {
                $i++;
                continue;
            }
            $url = 'https://www.yelp.com' . $matches[1][$index];
            $name = $matches[2][$index];

            $page = file_get_contents($url);

            $reg = "|<span class=\"pagination-results-window\">.*?Showing.*?of ([0-9]{1,}).*?</span>|is";
            preg_match_all($reg, $page, $match);
            //echo "<pre>"; print_r($match);

            if (!empty($match[1][0])) $result[$match[1][0]] = ['url' => $url, 'name' => $name];

            if ($i == 70) break;
            $i++;
        }
    }

    krsort($result);

    echo "<pre>"; print_r($result);
});
 */
