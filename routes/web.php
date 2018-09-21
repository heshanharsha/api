<?php

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

// testing
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'viewform', 'AdminController@viewform' )->name( 'admin-viewform' );
    Route::get( 'getcompanydetails', 'AdminController@getcompanydetails' )->name( 'admin-getcompanydetails' );
} );


// company incoporation route
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'civiewform', 'CompanyIncorporationController@civiewform' )->name( 'admin-civiewform' );
    Route::get( 'getcidetails', 'CompanyIncorporationController@getcidetails' )->name( 'admin-getcidetails' );
} );

// Form D Annexure
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'formDAnnexureView', 'CompanyIncorporationController@formDAnnexureView' )->name( 'admin-formDAnnexureView' );
    Route::get( 'formDAnnexure', 'CompanyIncorporationController@formDAnnexure' )->name( 'admin-formDAnnexure' );
} );

// // Company Name registration Number Change
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'companyNameRegistrationNumberChangeView', 'CompanyIncorporationController@companyNameRegistrationNumberChangeView' )->name( 'admin-companyNameRegistrationNumberChangeView' );
    Route::get( 'companyNameRegistrationNumberChange', 'CompanyIncorporationController@companyNameRegistrationNumberChange' )->name( 'admin-companyNameRegistrationNumberChange' );
} );

// // Change of Director
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'changeOfDirectorView', 'CompanyIncorporationController@changeOfDirectorView' )->name( 'admin-changeOfDirectorView' );
    Route::get( 'changeOfDirector', 'CompanyIncorporationController@changeOfDirector' )->name( 'admin-changeOfDirector' );
} );

// // Liquidation/ Strike off
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'liquidationView', 'CompanyIncorporationController@liquidationView' )->name( 'admin-liquidationView' );
    Route::get( 'liquidation', 'CompanyIncorporationController@liquidation' )->name( 'admin-liquidation' );
} );

// // Amalgamations
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'amalgamationsView', 'CompanyIncorporationController@amalgamationsView' )->name( 'admin-amalgamationsView' );
    Route::get( 'amalgamations', 'CompanyIncorporationController@amalgamations' )->name( 'admin-amalgamations' );
} );


