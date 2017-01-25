# Classified engine

Classified engine is built using Laravel 5.3 framework.

It's using all parts of Laravel framework:

- DB migration
- seeding 
- console commands
- power of Eloquent
- and much more

## Installation

- git clone URL_TO_REPO
- chmod -R 777 storage/
- chmod -R 777 bootstrap/cache
- composer install
- bower install
- cp .env.example .env
- setup .env file with your credentials
- php artisan migrate

## Customer Artisan commands

Prepare site - get cities, categories, generate jobs:

`
php artisan scrapers:prepare-site target our-domain
`

Prepare tam:

`
php artisan scrapers:prepare-tam ibelarus.dev
`

Start scraping:

`
php artisan scrape
`

Update companies positions to move nice companies to the top:

`
php artisan companies:update-position SITE_ID
`

Generate sitemap for all sites:

`
php artisan sitemap-generate
`

To be continued...