<?php
namespace App\Scrapers;

class ScraperManager {
    public function process(ScraperInterface $scraper, $url)
    {
        echo "Processing " . $url . "...\n";

        echo $scraper->process($url);

        echo "End of processing.\n\n";

    }
}