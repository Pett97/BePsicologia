<?php

use App\Controllers\AuthenticationsController;
use Core\Router\Route;
use App\Controllers\CarsController;
use App\Controllers\BrandsController;

//Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('authenticate');


//Mid
Route::middleware("auth")->group(function () {
    //Brands
    Route::get("/", [AuthenticationsController::class, "new"])->name("users.login");
    Route::get("/brands/page/{page}", [BrandsController::class, "index"])->name("brands.paginate");

    //create
    Route::get("/brands/new", [BrandsController::class, "new"])->name("new.brand");
    Route::post("/brands", [BrandsController::class, "create"])->name("create.brand");

    // Retrieve
    Route::get("/brands", [BrandsController::class, "index"])->name("brands.list");
    Route::get("/brand/{id}", [BrandsController::class, "show"])->name("brands.show");

    // Update
    Route::get("/brand/{id}/edit", [BrandsController::class, "edit"])->name("brand.edit");
    Route::put("/brands/update/{id}", [BrandsController::class, "update"])->name("brand.update");

    //Delete
    Route::delete("/brands/{id}", [BrandsController::class, "delete"])->name("brand.destroy");

    //Cars--------------------------
    Route::get("/cars", [CarsController::class, "index"])->name("cars");
    Route::get("/cars/page/{page}", [CarsController::class, "index"])->name("cars.paginate");
    //create
    Route::get("/cars/new", [CarsController::class, "new"])->name("new.car");

    Route::post("/cars", [CarsController::class, "create"])->name("create.car");


    // Retrieve
    Route::get("/car/{id}", [CarsController::class, "show"])->name("show.car");

    //edit
    Route::get("/cars/{id}", [CarsController::class, "edit"])->name("car.edit");
    Route::put("/cars/{id}", [CarsController::class, "update"])->name("car.update");

    //delete
    Route::delete("/cars/{id}", [CarsController::class, "delete"])->name("car.destroy");

    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('logout');
});
