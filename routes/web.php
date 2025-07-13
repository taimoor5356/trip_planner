<?php

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AclController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LandMarkController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\BuiltTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\SeasonTypeController;
use App\Http\Controllers\RoomAmenityController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\BuildingTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OriginSeasonController;
use App\Http\Controllers\RegionSeasonController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\PlaceOfOriginController;
use App\Http\Controllers\VehicleRegionController;
use App\Http\Controllers\DetailedReportController;
use App\Http\Controllers\LandMarkSeasonController;
use App\Http\Controllers\PropertyAmenityController;
use App\Http\Controllers\RoomCategoryCostController;
use App\Http\Controllers\PlaceOfDestinationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Auth::routes();

Route::get('/', [TripController::class, 'custom'])->name('custom');
Route::get('/customer_logout', function (){
    Auth::logout();
    return redirect()->route('custom');
})->name('customer_logout');

Route::get('/trip-design-result', [TripController::class, 'designMyTrip'])->name('design_my_trip');

Route::get('/update-trip-design-result/{accommodation_id}/{town_id}', function($accommodationId, $townId) {
    Accommodation::where('town_id', $townId)->update([
        'default_status' => 'no'
    ]);
    Accommodation::where('id', $accommodationId)->update([
        'default_status' => 'yes'
    ]);
    return redirect()->back();
})->name('update_design_my_trip');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('post_login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('post_register');
Route::get('/logout', [AuthController::class, 'logout'])->name('postlogout');

Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('send_notification');
Route::post('/get-notifications', [NotificationController::class, 'getNotifications'])->name('get_notifications');

// Filter endpoints
Route::post('/fetch-date-wise-destination', [FilterController::class, 'fetchDateWiseDestination'])->name('fetch_date_wise_destination');
Route::post('/fetch-destination-wise-days', [FilterController::class, 'fetchDestinationWiseDays'])->name('fetch_destination_wise_days');
Route::post('/fetch-people-wise-vehicles', [FilterController::class, 'fetchPeopleWiseVehicles'])->name('fetch_people_wise_vehicles');
Route::post('/fetch-city-landmarks', [FilterController::class, 'fetchCityLandmarks'])->name('fetch_city_landmarks');

Route::group(['prefix' => ''], function () {
        
    //Admin Routes
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {

        //Dashboard Routes
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        ////////////////////////////// Accommodation Routes //////////////////////////////
        //Accommodations Routes
        Route::group(['prefix' => '/accommodations'], function () {
            $controllerClass = AccommodationController::class;
            $routeName = 'accommodations';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-towns', [$controllerClass, 'deleteMultipleAccommodations'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Activity Types Routes //////////////////////////////
        //ActivityType Routes
        Route::group(['prefix' => '/activity-types'], function () {
            $controllerClass = ActivityTypeController::class;
            $routeName = 'activity_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-activity-types', [$controllerClass, 'deleteMultipleActivityTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Buildings Routes //////////////////////////////
        //BuildingType Routes
        Route::group(['prefix' => '/building-types'], function () {
            $controllerClass = BuildingTypeController::class;
            $routeName = 'building_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-building-types', [$controllerClass, 'deleteMultipleBuildingTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Built Routes
        Route::group(['prefix' => '/builts'], function () {
            $controllerClass = BuiltTypeController::class;
            $routeName = 'builts';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-builts', [$controllerClass, 'deleteMultipleBuilts'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });
        
        //PropertyAmenities Routes
        Route::group(['prefix' => '/property-amenities'], function () {
            $controllerClass = PropertyAmenityController::class;
            $routeName = 'property_amenities';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-property-amenities', [$controllerClass, 'deleteMultiplePropertyAmenities'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Categories Routes //////////////////////////////
        //Categories Routes
        Route::group(['prefix' => '/categories'], function () {
            $controllerClass = CategoryController::class;
            $routeName = 'categories';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-categories', [$controllerClass, 'deleteMultipleCategories'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Itineraries Routes //////////////////////////////
        //Itineraries Routes
        Route::group(['prefix' => '/itineraries'], function () {
            $controllerClass = ItineraryController::class;
            $routeName = 'itineraries';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-itineraries', [$controllerClass, 'deleteMultipleCountries'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Places Routes //////////////////////////////
        //Country Routes
        Route::group(['prefix' => '/countries'], function () {
            $controllerClass = CountryController::class;
            $routeName = 'countries';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-countries', [$controllerClass, 'deleteMultipleCountries'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Provinces Routes
        Route::group(['prefix' => '/provinces'], function () {
            $controllerClass = ProvinceController::class;
            $routeName = 'provinces';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-provinces', [$controllerClass, 'deleteMultipleProvinces'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Regions Routes
        Route::group(['prefix' => '/regions'], function () {
            $controllerClass = RegionController::class;
            $routeName = 'regions';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-regions', [$controllerClass, 'deleteMultipleRegions'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //City Routes
        Route::group(['prefix' => '/cities'], function () {
            $controllerClass = CityController::class;
            $routeName = 'cities';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-cities', [$controllerClass, 'deleteMultipleCities'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Towns Routes
        Route::group(['prefix' => '/towns'], function () {
            $controllerClass = TownController::class;
            $routeName = 'towns';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-towns', [$controllerClass, 'deleteMultipleSeasonTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //LandMark Routes
        Route::group(['prefix' => '/land-marks'], function () {
            $controllerClass = LandMarkController::class;
            $routeName = 'land_marks';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-land-marks', [$controllerClass, 'deleteMultipleLandMarks'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //LandMark Season Routes
        Route::group(['prefix' => '/land-mark-seasons'], function () {
            $controllerClass = LandMarkSeasonController::class;
            $routeName = 'land_mark_season';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-land-marks', [$controllerClass, 'deleteMultipleLandMarks'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Origin Routes
        Route::group(['prefix' => '/origins'], function () {
            $controllerClass = OriginController::class;
            $routeName = 'origins';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-origins', [$controllerClass, 'deleteMultipleOrigins'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Origin Routes
        Route::group(['prefix' => '/origin-seasons'], function () {
            $controllerClass = OriginSeasonController::class;
            $routeName = 'origin_seasons';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-origins', [$controllerClass, 'deleteMultipleOrigins'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Rooms Routes //////////////////////////////
        //RoomAmenity Routes
        Route::group(['prefix' => '/room-amenities'], function () {
            $controllerClass = RoomAmenityController::class;
            $routeName = 'room_amenities';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-room-amenities', [$controllerClass, 'deleteMultipleRoomAmenities'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //RoomCategory Routes
        Route::group(['prefix' => '/room-categories'], function () {
            $controllerClass = RoomCategoryController::class;
            $routeName = 'room_categories';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-room-categories', [$controllerClass, 'deleteMultipleRoomCategories'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //RoomCategoryCost Routes
        Route::group(['prefix' => '/room-category-costs'], function () {
            $controllerClass = RoomCategoryCostController::class;
            $routeName = 'room_category_costs';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-room-category-costs', [$controllerClass, 'deleteMultipleRoomCategoryCosts'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        ////////////////////////////// Seasons Routes //////////////////////////////
        //Seasons Routes
        Route::group(['prefix' => '/seasons'], function () {
            $controllerClass = SeasonController::class;
            $routeName = 'seasons';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-seasons', [$controllerClass, 'deleteMultipleSeasons'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //SeasonTypes Routes
        Route::group(['prefix' => '/season-types'], function () {
            $controllerClass = SeasonTypeController::class;
            $routeName = 'season_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-season-types', [$controllerClass, 'deleteMultipleSeasonTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //RegionSeasons Routes
        Route::group(['prefix' => '/region-seasons'], function () {
            $controllerClass = RegionSeasonController::class;
            $routeName = 'region_seasons';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-region-seasons', [$controllerClass, 'deleteMultipleRegionSeasons'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Trips Routes
        Route::group(['prefix' => '/trips'], function () {
            $controllerClass = TripController::class;
            $routeName = 'trips';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-trips', [$controllerClass, 'deleteMultipleTrips'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Vehicles Routes
        Route::group(['prefix' => '/vehicles'], function () {
            $controllerClass = VehicleController::class;
            $routeName = 'vehicles';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicles', [$controllerClass, 'deleteMultipleVehicles'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //VehicleTypes Routes
        Route::group(['prefix' => '/vehicle-types'], function () {
            $controllerClass = VehicleTypeController::class;
            $routeName = 'vehicle_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicle-types', [$controllerClass, 'deleteMultipleVehicleTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //VehicleRegions Routes
        Route::group(['prefix' => '/vehicle-regions'], function () {
            $controllerClass = VehicleRegionController::class;
            $routeName = 'vehicle_regions';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicle-regions', [$controllerClass, 'deleteMultipleVehicleRegions'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //VehicleTypes Routes
        Route::group(['prefix' => '/place-of-origin'], function () {
            $controllerClass = PlaceOfOriginController::class;
            $routeName = 'vehicle_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicle-types', [$controllerClass, 'deleteMultipleVehicleTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //VehicleTypes Routes
        Route::group(['prefix' => '/place-of-destination'], function () {
            $controllerClass = PlaceOfDestinationController::class;
            $routeName = 'vehicle_types';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicle-types', [$controllerClass, 'deleteMultipleVehicleTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });

        //Users Routes
        Route::group(['prefix' => '/users'], function () {
            $controllerClass = UserController::class;
            $routeName = 'users';
            Route::get('/list', [$controllerClass, 'index'])->name('admin.'.$routeName.'.list');
            Route::get('/create', [$controllerClass, 'create'])->name('admin.'.$routeName.'.create');
            Route::post('/store', [$controllerClass, 'store'])->name('admin.'.$routeName.'.store');
            Route::get('/edit/{id}', [$controllerClass, 'edit'])->name('admin.'.$routeName.'.edit');
            Route::post('/update/{id}', [$controllerClass, 'update'])->name('admin.'.$routeName.'.update');
            Route::post('/delete', [$controllerClass, 'destroy'])->name('admin.'.$routeName.'.destroy');
            Route::get('/sync', [$controllerClass, 'sync'])->name('admin.'.$routeName.'.sync');

            Route::post('/delete-multiple-vehicle-types', [$controllerClass, 'deleteMultipleVehicleTypes'])->name('delete_multiple_'.$routeName.'');
            Route::get('/trashed', [$controllerClass, 'trashed'])->name('admin.'.$routeName.'.trashed');
            Route::get('/restore/{id}', [$controllerClass, 'restore'])->name('admin.'.$routeName.'.restore');
            Route::post('/export', [$controllerClass, 'export'])->name('admin.'.$routeName.'.export');
            Route::post('/import', [$controllerClass, 'importData'])->name('admin.'.$routeName.'.import');
        });
        
        //ACL Routes
        Route::group(['prefix' => '/acl'], function () {
            Route::get('/roles/{status?}', [AclController::class, 'index'])->name('admin.acl.roles');
            Route::get('/role/create', [AclController::class, 'create'])->name('admin.acl.role.create');
            Route::post('/role/store', [AclController::class, 'store'])->name('admin.acl.role.store');
            Route::get('/role/{id}/edit', [AclController::class, 'edit'])->name('admin.acl.role.edit');
            Route::post('/role/update/{id}', [AclController::class, 'update'])->name('admin.acl.role.update');
        });
    });
    
    //Customer Routes
    Route::group(['prefix' => 'customer'], function () {
        Route::post('/book-trip', function (Request $request) {
            Trip::create([
                'itinerary_id' => $request->itinerary_id,
                'user_id' => $request->user_id,
                'link' => $request->link
            ]);
            return response()->json([
                'status' => true,
                'msg' => 'Trip booked successfully'
            ]);
        })->name('customer_book_trip');

        //Trips Routes
        Route::group(['prefix' => '/trips'], function () {
            $controllerClass = TripController::class;
            $routeName = 'trips';
            Route::get('/list', [$controllerClass, 'index'])->name('customer.'.$routeName.'.list');
        });
    });
    
    //User Routes
    Route::group(['prefix' => 'user', 'middleware' => ['auth', 'role:user|admin']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/list', [UserController::class, 'users'])->name('user.list');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class,'store'])->name('user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
    });

});