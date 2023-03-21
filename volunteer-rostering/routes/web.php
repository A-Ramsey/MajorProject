<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WorkingTTController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\EventVolunteerController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailController;
use App\Models\Event;
use App\Models\User;
use App\Mail\TestEmail;

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

Route::get('register', [AuthController::class, 'register'])
    ->middleware('guest');
Route::post('register', [AuthController::class, 'doRegister'])
    ->middleware('guest');
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth');
Route::get('login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');
Route::post('login', [AuthController::class, 'doLogin'])
    ->middleware('guest');

Route::post('add-availability', [AvailabilityController::class, 'addAvailability'])
    ->middleware('auth');
Route::post('delete-availability', [AvailabilityController::class, 'deleteAvailability'])
    ->middleware('auth');

Route::get('workingTT/list', [WorkingTTController::class, 'list'])
    ->middleware(['auth', 'roleCheck:roster clerk, administrator']);
Route::get('workingTT/create', [WorkingTTController::class, 'create'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('workingTT/create', [WorkingTTController::class, 'store'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('workingTT/{workingTTid}/delete', [WorkingTTController::class, 'delete'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::get('workingTT/{workingTTid}/edit', [WorkingTTController::class, 'edit'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('workingTT/{workingTTid}/edit', [WorkingTTController::class, 'update'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('workingTT/{workingTTid}/add-qualification', [WorkingTTController::class, 'addQualification'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::post('workingTT/{workingTTid}/remove-qualification/{qualificationId}', [WorkingTTController::class, 'removeQualification'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::get('workingTT/{workingTTid}/pdf', [WorkingTTController::class, 'pdf'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::get('workingTT/{workingTTid}', [WorkingTTController::class, 'show'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);

Route::get('event/create', [EventController::class, 'create'])
    ->middleware(['auth', 'roleCheck:Administrator,Roster Clerk']);
Route::post('event/create', [EventController::class, 'store'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::get('event/{eventId}', [EventController::class, 'show'])
    ->middleware('auth');
Route::get('event/{eventId}/edit', [EventController::class, 'edit'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::post('event/{eventId}/edit', [EventController::class, 'update'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::post('event/{eventId}/delete', [EventController::class, 'delete'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::get('event/{eventId}/volunteer', [EventVolunteerController::class, 'create'])
    ->middleware('auth');
Route::post('event/{eventId}/volunteer', [EventVolunteerController::class, 'store'])
    ->middleware('auth');
Route::get('event/{eventId}/add-staff', [EventVolunteerController::class, 'addStaffCreate'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::post('event/{eventId}/add-staff', [EventVolunteerController::class, 'addStaffStore'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::get('event/{eventId}/confirm-roster', [EventVolunteerController::class, 'confirmRosterCreate'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
Route::post('event/{eventId}/confirm-roster', [EventVolunteerController::class, 'confirmRosterStore'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);

Route::get('qualification/create', [QualificationController::class, 'create'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::post('qualification/create', [QualificationController::class, 'store'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::get('qualification/list', [QualificationController::class, 'list'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::post('qualification/{qualificationId}/delete', [QualificationController::class, 'delete'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::get('qualification/{qualificationId}', [QualificationController::class, 'view'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::get('qualification/{qualificationId}/assign', [QualificationController::class, 'pickUserLevel'])
    ->middleware(['auth', 'roleCheck:Training Officer']);
Route::post('level/{levelId}/user/{userId}', [QualificationController::class, 'removeUserLevel'])
    ->middleware(['auth', 'roleCheck:Training Officer']);
Route::post('qualification/{qualificationId}/assign', [QualificationController::class, 'assignLevel'])
    ->middleware(['auth', 'roleCheck:Training Officer']);
Route::get('qualification/{qualificationId}/edit', [QualificationController::class, 'edit'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);
Route::post('qualification/{qualificationId}/edit', [QualificationController::class, 'update'])
    ->middleware(['auth', 'roleCheck:Training Officer, Administrator']);

Route::get('user/{userId}', [UserController::class, 'view'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::get('user/{userId}/edit', [UserController::class, 'edit'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('user/{userId}/edit', [UserController::class, 'update'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::get('user/{userId}/reset-password', [UserController::class, 'resetPasswordForm'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::post('user/{userId}/reset-password', [UserController::class, 'resetPassword'])
    ->middleware(['auth', 'roleCheck:Administrator']);
Route::get('users', [UserController::class, 'list'])
    ->middleware(['auth', 'roleCheck:administrator']);
Route::post('user/{userId}/approve', [UserController::class, 'approve'])
    ->middleware(['auth', 'roleCheck:administrator']);
Route::post('user/{userId}/delete', [UserController::class, 'delete'])
    ->middleware(['auth', 'roleCheck:administrator']);
Route::get('reset-password', [UserController::class, 'resetOwnPasswordForm'])
    ->middleware(['auth']);
Route::post('reset-password', [UserController::class, 'resetOwnPassword'])
    ->middleware(['auth']);
Route::get('change-details', [UserController::class, 'changeDetails'])
    ->middleware(['auth']);
Route::post('change-details', [UserController::class, 'updateDetails'])
    ->middleware(['auth']);

Route::get('company/edit', [CompanyController::class, 'edit'])
    ->middleware(['auth', 'roleCheck:administrator']);
Route::post('company/edit', [CompanyController::class, 'update'])
    ->middleware(['auth', 'roleCheck:administrator']);

Route::get('home', function () {
    return view('home', ['events' => Event::all()]);
})->middleware('auth');

Route::get('personal-calendar', function () {
    return view('personal_calendar');
})->middleware('auth');

Route::get('/', function () {
    return view('home', ['events' => Event::all()]);
})->middleware('auth');

Route::get('email/event/{eventId}/need-volunteers/{qualId}', [EmailController::class, 'needVolunteers'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);

Route::get('email/event/{eventId}/roster-confirm', [EmailController::class, 'rosterConfirm'])
    ->middleware(['auth', 'roleCheck:Administrator, Roster Clerk']);
