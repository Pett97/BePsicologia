<?php

use App\Controllers\AppointmentsController;
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
    Route::get("/schedule/{id}", [FixedsSchedulesController::class, "show"])->name("schedule.show");


    // Update
    Route::get("/schedule/{id}/edit", [FixedsSchedulesController::class, "edit"])->name("schedule.edit");
    Route::put("/schedule/update/{id}", [FixedsSchedulesController::class, "update"])->name("schedule.update");

    //Delete
    Route::delete("/schedule/{id}", [FixedsSchedulesController::class, "delete"])->name("schedule.destroy");


    //------------------------------------------------------------------

    //Routes about Appointaments
    //create
    Route::get("/appointaments/new", [AppointmentsController::class, "new"])->name("appointament.new");
    Route::post("/appointaments", [AppointmentsController::class, "create"])->name("create.appointaments");

    // Retrieve
    Route::get("/appointaments", [AppointmentsController::class, "index"])->name("list.appointaments");

    Route::get("/appointaments/page/{page}", [AppointmentsController::class, "index"])->name("appointments.paginate");

    Route::get("/appointaments/{id}", [AppointmentsController::class, "show"])->name("appointaments.show");
    // Update
    Route::get("/appointaments/{id}/edit", [AppointmentsController::class, "edit"])->name("appointaments.edit");
    Route::put("/appointaments/update/{id}", [AppointmentsController::class, "update"])->name("appointaments.update");

    //Delete
    Route::delete("/appointaments/{id}", [AppointmentsController::class, "delete"])->name("appointament.destroy");

    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('logout');
});
