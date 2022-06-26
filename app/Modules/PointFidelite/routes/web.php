<?php

use Illuminate\Support\Facades\Route;

//Route::get('point-fidelite', 'PointFideliteController@welcome');

Route::middleware([
    'web',
    'admin',
])->name('backend.')->prefix('/admin')->group(function () {
    
    // Points configs
    Route::get('config-points','PointConfigController@index')->name('pointsConfig.index');
    Route::get('config-points/{id}/edit','PointConfigController@edit')->name('pointsConfig.edit');
    Route::patch('config-points/{id}','PointConfigController@update')->name('pointsConfig.update');
});