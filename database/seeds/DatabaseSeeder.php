<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // sites
        DB::table('sites')->insert([
            'id' => 1,
            'country_id' => 192,
            'city_id' => 524901,
            'name' => 'Engine Russia',
            'background' => 'http://static.engine.dev/cities/moscow_small.jpg',
            'domain' => 'api.engine.dev'
        ]);
        DB::table('sites')->insert([
            'id' => 2,
            'country_id' => 36,
            'city_id' => 625144,
            'name' => 'Engine Belarus',
            'background' => 'http://static.engine.dev/cities/minsk01.jpg',
            'domain' => 'by.engine2.dev'
        ]);

        // countries
        DB::table('countries')->insert([
            'id' => 192,
            'name' => 'Russia'
        ]);
        DB::table('countries')->insert([
            'id' => 36,
            'name' => 'Belarus'
        ]);
        DB::table('countries')->insert([
            'id' => 231,
            'name' => 'Ukraine'
        ]);

        // cities
        DB::table('cities')->insert([
            'id' => 625144,
            'country_id' => 36,
            'latitude' => 53.9000000,
            'longitude' => 27.5666700,
            'name' => 'Minsk',
            'domain' => 'minsk'
        ]);
        DB::table('cities')->insert([
            'id' => 524901,
            'country_id' => 192,
            'latitude' => 55.7522200,
            'longitude' => 37.6155600,
            'name' => 'Moscow',
            'domain' => 'moscow'
        ]);
        DB::table('cities')->insert([
            'id' => 498817,
            'country_id' => 192,
            'latitude' => 59.9386300,
            'longitude' => 30.3141300,
            'name' => 'Saint Petersburg',
            'domain' => 'spb'
        ]);
        DB::table('cities')->insert([
            'id' => 703448,
            'country_id' => 231,
            'latitude' => 50.4546600,
            'longitude' => 30.5238000,
            'name' => 'Kiev',
            'domain' => 'kiev'
        ]);

        // companies_categories
        DB::table('categories')->insert([
            'id' => 1,
            'site_id' => 1,
            'name' => 'Рестораны',
            'domain' => 'restaurants',
            'description_top' => 'Верхнее описание категории Рестораны',
            'description_bottom' => 'Нижнее описание категории Рестораны',
            'icon' => 'fa-cutlery'
        ]);
        DB::table('categories')->insert([
            'id' => 2,
            'site_id' => 1,
            'name' => 'Кафе',
            'domain' => 'cafe',
            'description_top' => 'Верхнее описание категории Кафе',
            'description_bottom' => 'Нижнее описание категории Кафе',
            'icon' => 'fa-coffee'
        ]);
        DB::table('categories')->insert([
            'id' => 3,
            'site_id' => 2,
            'name' => 'Category 1',
            'domain' => 'category1',
            'description_top' => 'Верхнее описание категории Категория 1',
            'description_bottom' => 'Нижнее описание категории Категоория 1',
            'icon' => 'fa-coffee'
        ]);

        // companies
        DB::table('companies')->insert([
            'id' => 1,
            'site_id' => 1,
            'category_id' => 1,
            'name' => 'ТЖИ Фрайдис',
            'domain' => 'fridays',
            'description' => 'Пятница, столь любимая всеми, теперь может наступить в любой день недели, пусть даже и в понедельник. Все возможно с известным сетевым рестораном «Фрайдис», который предлагает гостям и настоящую американскую кухню, и настоящий дух свободы: давайте почувствуем себя более раскрепощенными!',
            'latitude' => '53.900814',
            'longitude' => '27.560304'
        ]);
        DB::table('companies')->insert([
            'id' => 2,
            'site_id' => 1,
            'category_id' => 2,
            'name' => 'Company 2',
            'domain' => 'company2',
            'description' => 'Default description for company 2',
            'latitude' => '53.947102',
            'longitude' => '27.689104'
        ]);
        DB::table('companies')->insert([
            'id' => 3,
            'site_id' => 1,
            'category_id' => 1,
            'name' => 'Чумацький Шлях',
            'description' => 'Ресторан “Чумацький шлях” - это уютный уголок щедрой Украины в Минске. Здесь вы окунетесь в самобытный колорит украинской культуры, почувствуете уникальный дух чумачества, не имеющего аналогов в мировой культуре. ',
            'domain' => 'chumatski-shlyah',
            'latitude' => '53.897912',
            'longitude' => '27.543317'
        ]);

        // option groups
        DB::table('options_groups')->insert([
            'id' => 1,
            'name' => 'Кухня',
        ]);

        // category_option_group
        DB::table('category_option_group')->insert([
            'category_id' => 1,
            'option_group_id' => 1
        ]);

        // options
        DB::table('options')->insert([
            'id' => 1,
            'option_group_id' => 1,
            'name' => 'Беларуская',
        ]);
        DB::table('options')->insert([
            'id' => 2,
            'option_group_id' => 1,
            'name' => 'Русская',
        ]);
        DB::table('options')->insert([
            'id' => 3,
            'option_group_id' => 1,
            'name' => 'Европейская'
        ]);

        // company_option
        DB::table('company_option')->insert([
            'company_id' => 1,
            'option_id' => 3
        ]);
        DB::table('company_option')->insert([
            'company_id' => 3,
            'option_id' => 1
        ]);
        DB::table('company_option')->insert([
            'company_id' => 3,
            'option_id' => 2
        ]);
    }
}
