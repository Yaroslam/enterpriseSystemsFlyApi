<?php

use App\Http\Controllers\AgeGroupController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AmentitesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabinTypesController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user', [UserController::class, 'index'])->middleware('cors');
Route::post('/login', [UserController::class, 'login'])->middleware('cors');
Route::post('/logout', [UserController::class, 'logout'])->middleware('cors');
Route::post('/changeBlockUser', [UserController::class, 'changeBlockUser'])->middleware('cors');
Route::post('/crashHandler', [UserController::class, 'handleCrash'])->middleware('cors');
Route::post('/addUser', [UserController::class, 'addUser'])->middleware('cors');
Route::post('/changeUserRole', [UserController::class, 'changeUserRole'])->middleware('cors');
Route::get('/getUsersByOffice', [UserController::class, 'getUsersByOffice'])->middleware('cors');

Route::get('/getOffices', [OfficeController::class, 'getOffices'])->middleware('cors');

Route::get('/getAllRoles', [RoleController::class, 'getAllRoles'])->middleware('cors');

Route::get('/getAirportsCodes', [AirportController::class, 'getAirportsCodes'])->middleware('cors');

Route::get('/getSchedule', [ScheduleController::class, 'getSchedule'])->middleware('cors');
Route::post('/changeFlightConfirm', [ScheduleController::class, 'changeFlightConfirm'])->middleware('cors');
Route::post('/updateFlight', [ScheduleController::class, 'updateFlight'])->middleware('cors');
Route::post('/loadFromFile', [ScheduleController::class, 'loadFromFile'])->middleware('cors');

Route::get('/getCabinTypes', [CabinTypesController::class, 'getCabinTypes'])->middleware('cors');

Route::post('/checkBooking', [BookingController::class, 'checkBooking']);
Route::post('/createTickets', [BookingController::class, 'createTickets']);
Route::get('/getFlightsForBooking', [BookingController::class, 'getFlightsForBooking']);
Route::get('/getTicketsByReference', [BookingController::class, 'getTicketsByReference']);
Route::get('/getReport', [BookingController::class, 'getReport']);
Route::get('/getFreeSeats', [BookingController::class, 'getFreeSeats']);
Route::get('/getAverageSeatsPrice', [BookingController::class, 'getAverageSeatsPrice']);

Route::post('/loadSummaryFromFile', [SummaryController::class, 'loadSummaryFromFile']);
Route::get('/getDefaultSummary', [SummaryController::class, 'getDefaultSummary']);
Route::get('/getSummaryTimePeriods', [SummaryController::class, 'getSummaryTimePeriods']);
Route::get('/getAdvancedInformation', [SummaryController::class, 'getAdvancedInformation']);

Route::get('/getAllAgeGroup', [AgeGroupController::class, 'getAllAgeGroup']);

Route::get('/getAllGenders', [GenderController::class, 'getAllGenders']);

Route::get('/getAmetitesForTicket', [AmentitesController::class, 'getAmetitesForTicket']);
Route::post('/editAmentitesToTicket', [AmentitesController::class, 'editAmentitesToTicket']);


