<?php

use App\Controllers\AuthenticationsController;
use Core\Router\Route;
use App\Controllers\ClientsController;

//Authentication
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('authenticate');


//Mid
Route::middleware("auth")->group(function () {
    //Clients
    Route::get("/", [AuthenticationsController::class, "new"])->name("users.login");
    Route::get("/clients/page/{page}", [ClientsController::class, "index"])->name("clients.paginate");

    //create
    Route::get("/clients/new", [ClientsController::class, "new"])->name("new.client");
    Route::post("/clients", [ClientsController::class, "create"])->name("create.client");

    // Retrieve
    Route::get("/clients", [ClientsController::class, "index"])->name("clients.list");
    Route::get("/client/{id}", [ClientsController::class, "show"])->name("client.show");

    // Update
    Route::get("/client/{id}/edit", [ClientsController::class, "edit"])->name("client.edit");
    Route::put("/client/update/{id}", [ClientsController::class, "update"])->name("client.update");

    //Delete
    Route::delete("/client/{id}", [ClientsController::class, "delete"])->name("client.destroy");


    //-----------------------------------------------------------------------------
    //routes about fixed_schedules


    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('logout');
});
