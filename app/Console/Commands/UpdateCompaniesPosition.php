<?php

namespace App\Console\Commands;

use App\Category;
use App\City;
use App\Company;
use App\Site;
use Illuminate\Console\Command;

class UpdateCompaniesPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:update-position {site_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating companies positions...');

        $siteId = $this->argument('site_id');

        $categories = Category::where('site_id', $siteId)->get();

        foreach ($categories as $category) {
            $companies = Company::where('category_id', $category->id)->get();

            // get all pos
            $pos = [];
            foreach ($companies as $company) {
                $pos[] = $company->pos;
            }

            // rate companies
            $comps = [];
            foreach ($companies as $company) {
                $points = 0;

                if (!empty($company->main_photo_url)) $points += 8;
                if (!empty($company->description)) $points += 1;
                if (!empty($company->tel) && strlen($company->tel) > 3) $points += 1;
                if (!empty($company->website)) $points += 2;
                if (!empty($company->latitude) && !empty($company->longitude)) $points += 1;
                if (!empty($company->last_review) && !empty($company->last_review)) $points += 2;
                if (!empty($company->rating) && $company->rating > 4) $points += 2;
                if (!empty($company->amount_comment) && $company->amount_comments > 0) $points += 2;

                $comps[$company->id] = $points;
            }
            arsort($comps);

            // set new pos
            $i = 0;
            foreach ($comps as $companyId => $point) {
                $company = Company::where('id', $companyId)->first();
                $company->pos = $pos[$i];
                $company->save();

                $i++;
            }

            $this->info("Category {$category->name} updated");
        }
    }
}
