<?php

use Illuminate\Database\Seeder;

class Ibelarus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')->insert([
            'id' => 2,
            'country_id' => 36,
            'city_id' => 625144,
            'name' => 'iBelarus',
            'media_url' => 'http://static.engine.dev/ibelarus/',
            'media_path' => '/home/vagrant/sites/personal/static.engine.dev/public/ibelarus/',
            'domain' => 'ibelarus.dev',
            'headline' => 'Найди лучшие компании в Беларуси',
            'menu_type' => 2,
            'locale' => 'ru'
        ]);

        // categories
        DB::table('categories')->insert([
            'id' => 1,
            'site_id' => 2,
            'category_group_id' => 6,
            'name' => 'Рестораны',
            'domain' => 'restaurants',
            'description_top' => 'Все рестораны Минска собраны здесь не случайно: на заре третьего тысячелетия каждый житель столицы обладает полным правом выбрать ресторан по своему вкусу: рестораны с живой музыкой, стейк-хаусы, суши-бары, рестораны национальных кухонь, пабы, фуд-корты - и все остальные. ',
            'description_bottom' => 'Рестораны давно превратились из заведений общественного питания в места, где всегда можно отдохнуть, приятно и с пользой провести время. Рестораны Минска предлагают широкие возможности: проведение свадьбы, банкета, бизнес-ланчи и деловые встречи. Интернет-портал Чатофф поможет вам сориентироваться и выбрать рестораны в соответствии с вашими потребностями. Собраться большой дружеской компанией или отметить праздничное событие в узком кругу, провести романтический вечер при свечах, насладиться кухней высокого класса, попробовать экзотические блюда, послушать живую музыку, посетить клубную вечеринку – рестораны Минска удовлетворят потребности самых взыскательных клиентов.',
            'icon' => 'fa fa-cutlery',
            'url' => 'http://api.engine.dev/moscow/restaurants/',
            'meta_title' => 'Рестораны в Минске',
            'meta_keywords' => '',
            'meta_description' => '',
            'meta_image' => ''
        ]);
        DB::table('categories')->insert([
            'id' => 2,
            'site_id' => 2,
            'category_group_id' => 6,
            'name' => 'Кафе и бары',
            'domain' => 'cafe',
            'description_top' => 'Верхнее описание категории Кафе',
            'description_bottom' => 'Нижнее описание категории Кафе',
            'icon' => 'fa fa-coffee',
            'url' => 'http://api.engine.dev/moscow/cafe/',
            'meta_title' => 'Кафе и бары в Минске',
            'meta_keywords' => '',
            'meta_description' => '',
            'meta_image' => ''
        ]);
        DB::table('categories')->insert([
            'id' => 3,
            'site_id' => 2,
            'category_group_id' => 6,
            'name' => 'Category 1',
            'domain' => 'category1',
            'description_top' => 'Верхнее описание категории Категория 1',
            'description_bottom' => 'Нижнее описание категории Категоория 1',
            'icon' => 'fa fa-coffee',
            'meta_title' => 'Test Category 1',
            'meta_keywords' => '',
            'meta_description' => '',
            'meta_image' => ''
        ]);

        // categories_groups
        DB::table('categories_groups')->insert([
            'id' => 6,
            'site_id' => 2,
            'name1' => 'Развлечения',
            'name2' => 'и отдых'
        ]);

        // category_group
        DB::table('category_category_group')->insert([
            'category_id' => 1,
            'category_group_id' => 6,
        ]);
        DB::table('category_category_group')->insert([
            'category_id' => 2,
            'category_group_id' => 6,
        ]);
        DB::table('category_category_group')->insert([
            'category_id' => 3,
            'category_group_id' => 6,
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

        // companies
        DB::table('companies')->insert([
            'id' => 1,
            'site_id' => 2,
            'category_id' => 1,
            'name' => 'ТЖИ Фрайдис',
            'domain' => 'fridays',
            'description' => 'Пятница, столь любимая всеми, теперь может наступить в любой день недели, пусть даже и в понедельник. Все возможно с известным сетевым рестораном «Фрайдис», который предлагает гостям и настоящую американскую кухню, и настоящий дух свободы: давайте почувствуем себя более раскрепощенными!',
            'latitude' => '53.900814',
            'longitude' => '27.560304',
            'url' => 'http://fridays.api.engine.dev/',
            'main_photo_url' => 'http://static.engine.dev/companies/fridays.jpg',
            'address' => 'пр. Независимости, 22',
            'tel' => '+375447313128',
            'last_review' => 'You only live once, EAT WELL. Pulpo alone was life-changing, unspeakably delicious grilled morsels of perfection - impossibly tender, incredibly flavorful. Pane con tomate was mouthwatering - a must. Beetroot salad was beyond fresh and gladdens the palate with a thoughtful touch of dill. Grilled quail was quite oily and unfortunately bland - not a standout. ',
            'meta_title' => 'ТЖИ Фрайдис'
        ]);
        DB::table('companies')->insert([
            'id' => 2,
            'site_id' => 2,
            'category_id' => 2,
            'name' => 'Company 2',
            'domain' => 'company2',
            'description' => 'Default description for company 2',
            'latitude' => '53.947102',
            'longitude' => '27.689104',
            'url' => 'http://company2.api.engine.dev/',
            'main_photo_url' => '',
            'address' => 'Street',
            'tel' => '+375291234567',
            'last_review' => '',
            'meta_title' => 'Company 2'
        ]);
        DB::table('companies')->insert([
            'id' => 3,
            'site_id' => 2,
            'category_id' => 1,
            'name' => 'Чумацький Шлях',
            'description' => 'Ресторан “Чумацький шлях” - это уютный уголок щедрой Украины в Минске. Здесь вы окунетесь в самобытный колорит украинской культуры, почувствуете уникальный дух чумачества, не имеющего аналогов в мировой культуре. ',
            'domain' => 'chumatski-shlyah',
            'latitude' => '53.897912',
            'longitude' => '27.543317',
            'url' => 'http://chumatski-shlyah.api.engine.dev',
            'main_photo_url' => '',
            'address' => 'пр. Мясникова, 34',
            'tel' => '+375172009091,+375291907777',
            'last_review' => '',
            'meta_title' => 'Чумацкий'
        ]);
        DB::table('companies')->insert([
            'id' => 4,
            'site_id' => 2,
            'category_id' => 1,
            'name' => 'Пан Хмелю',
            'description' => 'Просто хороший ресторан',
            'domain' => 'pan-hmelju',
            'latitude' => '53.901737',
            'longitude' => '27.553953',
            'url' => 'http://pan-hmelju.api.engine.dev',
            'main_photo_url' => 'http://static.engine.dev/companies/pan.jpg',
            'address' => 'ул. Интернациональная, 11',
            'tel' => '+375172297602,+375291981690 ',
            'last_review' => '',
            'meta_title' => 'Пан Хмелю'
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

        // company_reviews
        DB::table('companies_reviews')->insert([
            'user_id' => 0,
            'company_id' => 1,
            'name' => 'Ilya',
            'review' => 'I have been there many times. And each time everything is very good and tasty.',
            'rating' => '5.0'
        ]);
    }
}
