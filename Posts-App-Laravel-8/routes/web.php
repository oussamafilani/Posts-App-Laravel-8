<?php

use Illuminate\Support\Facades\Route;

Route::get('/{id}/{author}', function ($id, $author) {
    return $id . " author $author";
});
