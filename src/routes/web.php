<?php

    use Illuminate\Support\Facades\Route;

    Route::middleware(['web'])->group(function () {
        Route::post('jcrud/datatable/config-columns-save', 'Softinline\JCrud\Controllers\DatatableController@configColumnsSave');
    });