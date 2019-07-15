<?php


Route::group(['namespace' => 'LWS\ExportActions\Http\Controllers'], function () {
    Route::post('export', 'Exporter@export');
    Route::get('progress', 'Exporter@progress');
    Route::get('download/{id}', 'Exporter@download');
});

