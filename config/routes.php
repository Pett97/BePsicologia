<?php

use App\Controllers\AuthenticationsController;
use Core\Router\Route;
use App\Controllers\ClientsController;
use App\Controllers\FixedsSchedulesController;

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

    //create
    Route::get("/schedules/new", [FixedsSchedulesController::class, "new"])->name("schedule.new");
    Route::post("/schedules", [FixedsSchedulesController::class, "create"])->name("create.schedule");

    // Retrieve
    Route::get("/schedules", [FixedsSchedulesController::class, "index"])->name("schedules.list");
    Route::get("/schedules/{id}", [FixedsSchedulesController::class, "show"])->name("schedule.show");

    // Update
    Route::get("/schedule/{id}/edit", [FixedsSchedulesController::class, "edit"])->name("schedule.edit");
    Route::put("/schedule/update/{id}", [FixedsSchedulesController::class, "update"])->name("schedule.update");

    //Delete
    Route::delete("/schedule/{id}", [FixedsSchedulesController::class, "delete"])->name("schedule.destroy");




    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('logout');
});
