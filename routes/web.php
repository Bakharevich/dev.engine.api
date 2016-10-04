<?php
Route::group(['domain' => '{companyDomain}.api.engine.dev'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::get('/', 'Web\IndexController@index')->name('index');

//});
Route::get('/{debugbar}', function($params) {
    dd(1);
    //return redirect('_debugbar' . $params);
})->where('debugbar', '_debugbar');

Route::get('/news/{id}/', function($id) {
    echo "News id: " . $id;
});
//Route::get('/{city}/{category}/{company}', 'CompanyController@show');
Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');