<?php

declare(strict_types= 1);

use Illuminate\Support\Facades\Route;

Route::view("products", "catalogues.products.index")->name("products");
Route::view("categories", "categories.categories.index")->name("categories");
Route::view("brands", "brands.bradds.index")->name("brands");
Route::view("units", "units.units.index")->name("units");