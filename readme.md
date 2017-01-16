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

Start scraping:

`
php artisan scrape
`

To be continued...