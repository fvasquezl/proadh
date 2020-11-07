<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;


JsonApi::register('v1')->routes(function ($api) {
    $api->resource('cars')->relationships(function ($api){
        $api->hasOne('authors')->except('replace');
        $api->hasOne('models')->except('replace');
    });

    $api->resource('models')->relationships(function ($api){
        $api->hasMany('cars')->except('replace','add','remove');
    });
});

