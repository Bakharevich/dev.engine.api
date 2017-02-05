<?php
namespace App\Repositories;

class BannerRepository {
    /**
     * Get banner for specific place and site
     *
     * @param $placeId
     * @param $siteId
     */
    public static function banner($placeId, $siteId)
    {
        /**
         * 1 = category center
         * 2 = category sidebar
         * 3 = company center
         */
        $categoryCenter = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Yelpster - Category - Center -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-8057150963527781"
                 data-ad-slot="3305366841"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>';

        $categorySidebar = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Yelpster - Category - Sidebar -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-8057150963527781"
                 data-ad-slot="1549432046"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>';

        $companyCenter = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Yelpster - Company - Center -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-8057150963527781"
                 data-ad-slot="4502898440"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>';

        $companySidebar = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Yelpster - Company - Sidebar -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-8057150963527781"
                 data-ad-slot="7456364842"
                 data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>';

        if ($siteId != 2 && $placeId == 1) return $categoryCenter;
        if ($siteId != 2 && $placeId == 2) return $categorySidebar;
        if ($siteId != 2 && $placeId == 3) return $companyCenter;
        if ($siteId != 2 && $placeId == 4) return $companySidebar;
    }
}