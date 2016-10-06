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
            'logo' => 'http://static.engine.dev/sites/chatoff/',
            'background' => 'http://static.engine.dev/cities/moscow_small.jpg',
            'domain' => 'api.engine.dev',
            'menu_type' => 2
        ]);
        DB::table('sites')->insert([
            'id' => 2,
            'country_id' => 36,
            'city_id' => 625144,
            'name' => 'Engine Belarus',
            'logo' => 'http://static.engine.dev/sites/engine/',
            'background' => 'http://static.engine.dev/cities/minsk01.jpg',
            'domain' => 'by.engine2.dev',
            'menu_type' => 1
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

        // categories_groups
        DB::table('categories_groups')->insert([
            'id' => 1,
            'site_id' => 1,
            'name1' => 'Развлечения',
            'name2' => 'и досуг'
        ]);
        DB::table('categories_groups')->insert([
            'id' => 2,
            'site_id' => 1,
            'name1' => 'Красота',
            'name2' => ''
        ]);
        DB::table('categories_groups')->insert([
            'id' => 3,
            'site_id' => 2,
            'name1' => 'English',
            'name2' => ''
        ]);

        // categories
        DB::table('categories')->insert([
            'id' => 1,
            'site_id' => 1,
            'category_group_id' => 1,
            'name' => 'Рестораны',
            'domain' => 'restaurants',
            'description_top' => 'Все рестораны Минска собраны здесь не случайно: на заре третьего тысячелетия каждый житель столицы обладает полным правом выбрать ресторан по своему вкусу: рестораны с живой музыкой, стейк-хаусы, суши-бары, рестораны национальных кухонь, пабы, фуд-корты - и все остальные. ',
            'description_bottom' => 'Рестораны давно превратились из заведений общественного питания в места, где всегда можно отдохнуть, приятно и с пользой провести время. Рестораны Минска предлагают широкие возможности: проведение свадьбы, банкета, бизнес-ланчи и деловые встречи. Интернет-портал Чатофф поможет вам сориентироваться и выбрать рестораны в соответствии с вашими потребностями. Собраться большой дружеской компанией или отметить праздничное событие в узком кругу, провести романтический вечер при свечах, насладиться кухней высокого класса, попробовать экзотические блюда, послушать живую музыку, посетить клубную вечеринку – рестораны Минска удовлетворят потребности самых взыскательных клиентов.',
            'icon' => 'fa-cutlery',
            'url' => 'http://api.engine.dev/moscow/restaurants/'
        ]);
        DB::table('categories')->insert([
            'id' => 2,
            'site_id' => 1,
            'category_group_id' => 1,
            'name' => 'Кафе',
            'domain' => 'cafe',
            'description_top' => 'Верхнее описание категории Кафе',
            'description_bottom' => 'Нижнее описание категории Кафе',
            'icon' => 'fa-coffee',
            'url' => 'http://api.engine.dev/moscow/cafe/'
        ]);
        DB::table('categories')->insert([
            'id' => 3,
            'site_id' => 2,
            'category_group_id' => 3,
            'name' => 'Category 1',
            'domain' => 'category1',
            'description_top' => 'Верхнее описание категории Категория 1',
            'description_bottom' => 'Нижнее описание категории Категоория 1',
            'icon' => 'fa-coffee'
        ]);

        // category_group
        DB::table('category_category_group')->insert([
            'category_id' => 1,
            'category_group_id' => 1,
        ]);
        DB::table('category_category_group')->insert([
            'category_id' => 2,
            'category_group_id' => 1,
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
            'longitude' => '27.560304',
            'url' => 'http://fridays.api.engine.dev/',
            'main_photo_url' => 'http://static.engine.dev/companies/fridays.jpg',
            'address' => 'г. Минск, пр. Независимости, 22',
            'tel' => '+375447313128'
        ]);
        DB::table('companies')->insert([
            'id' => 2,
            'site_id' => 1,
            'category_id' => 2,
            'name' => 'Company 2',
            'domain' => 'company2',
            'description' => 'Default description for company 2',
            'latitude' => '53.947102',
            'longitude' => '27.689104',
            'url' => 'http://company2.api.engine.dev/',
            'main_photo_url' => '',
            'address' => 'Minsk',
            'tel' => '+375291234567'
        ]);
        DB::table('companies')->insert([
            'id' => 3,
            'site_id' => 1,
            'category_id' => 1,
            'name' => 'Чумацький Шлях',
            'description' => 'Ресторан “Чумацький шлях” - это уютный уголок щедрой Украины в Минске. Здесь вы окунетесь в самобытный колорит украинской культуры, почувствуете уникальный дух чумачества, не имеющего аналогов в мировой культуре. ',
            'domain' => 'chumatski-shlyah',
            'latitude' => '53.897912',
            'longitude' => '27.543317',
            'url' => 'http://chumatski-shlyah.api.engine.dev',
            'main_photo_url' => '',
            'address' => 'г. Минск, пр. Мясникова, 34',
            'tel' => '+375172009091,+375291907777'
        ]);
        DB::table('companies')->insert([
            'id' => 4,
            'site_id' => 1,
            'category_id' => 1,
            'name' => 'Пан Хмелю',
            'description' => 'Просто хороший ресторан',
            'domain' => 'pan-hmelju',
            'latitude' => '53.901737',
            'longitude' => '27.553953',
            'url' => 'http://pan-hmelju.api.engine.dev',
            'main_photo_url' => 'http://static.engine.dev/companies/pan.jpg',
            'address' => '. Минск, ул. Интернациональная, 11',
            'tel' => '+375172297602,+375291981690 '
        ]);

        // option groups
        DB::table('options_groups')->insert([
            'id' => 1,
            'name' => 'Кухня',
            'icon' => 'fa fa-cutlery'
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
