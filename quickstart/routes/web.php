<?php

use App\Http\Controllers\AgeGroupController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\AmentitesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabinTypesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\RealtorController;
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

Route::post('/user', [UserController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/changeBlockUser', [UserController::class, 'changeBlockUser']);
Route::post('/crashHandler', [UserController::class, 'handleCrash']);
Route::post('/addUser', [UserController::class, 'addUser']);
Route::post('/changeUserRole', [UserController::class, 'changeUserRole']);
Route::post('/getUsersByOffice', [UserController::class, 'getUsersByOffice']);

Route::post('/getOffices', [OfficeController::class, 'getOffices']);

Route::post('/getAllRoles', [RoleController::class, 'getAllRoles']);

Route::post('/getAirportsCodes', [AirportController::class, 'getAirportsCodes']);

Route::post('/getSchedule', [ScheduleController::class, 'getSchedule']);
Route::post('/changeFlightConfirm', [ScheduleController::class, 'changeFlightConfirm']);
Route::post('/updateFlight', [ScheduleController::class, 'updateFlight']);
Route::post('/loadFromFile', [ScheduleController::class, 'loadFromFile']);

Route::post('/getCabinTypes', [CabinTypesController::class, 'getCabinTypes']);

Route::post('/checkBooking', [BookingController::class, 'checkBooking']);
Route::post('/createTickets', [BookingController::class, 'createTickets']);
Route::post('/getFlightsForBooking', [BookingController::class, 'getFlightsForBooking']);
Route::post('/getTicketsByReference', [BookingController::class, 'getTicketsByReference']);
Route::post('/getReport', [BookingController::class, 'getReport']);
Route::post('/getFreeSeats', [BookingController::class, 'getFreeSeats']);
Route::post('/getAverageSeatsPrice', [BookingController::class, 'getAverageSeatsPrice']);

Route::post('/loadSummaryFromFile', [SummaryController::class, 'loadSummaryFromFile']);
Route::post('/getDefaultSummary', [SummaryController::class, 'getDefaultSummary']);
Route::post('/getSummaryTimePeriods', [SummaryController::class, 'getSummaryTimePeriods']);
Route::post('/getAdvancedInformation', [SummaryController::class, 'getAdvancedInformation']);

Route::post('/getAllAgeGroup', [AgeGroupController::class, 'getAllAgeGroup']);

Route::post('/getAllGenders', [GenderController::class, 'getAllGenders']);

Route::post('/getAmetitesForTicket', [AmentitesController::class, 'getAmetitesForTicket']);
Route::post('/editAmentitesToTicket', [AmentitesController::class, 'editAmentitesToTicket']);



Route::post('/addClient', [ClientController::class, 'addClient']);
Route::post('/getAllClients', [ClientController::class, 'getAllClients']);
Route::post('/editClient', [ClientController::class, 'editClient']);
Route::post('/deleteClient', [ClientController::class, 'deleteClient']);
Route::post('/findClient', [ClientController::class, 'findClient']);

Route::post('/addRealtor', [RealtorController::class, 'addRealtor']);
Route::post('/getAllRealtors', [RealtorController::class, 'getAllRealtors']);
Route::post('/editRealtor', [RealtorController::class, 'editRealtor']);
Route::post('/editRealtor', [RealtorController::class, 'editRealtor']);
Route::post('/findRealtor', [RealtorController::class, 'findRealtor']);




