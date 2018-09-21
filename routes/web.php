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

// company incoporation route
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'companyIncorporationView', 'LaborDepartmentSystemController@companyIncorporationView' )->name( 'admin-companyIncorporationView' );
    Route::get( 'companyIncorporation', 'LaborDepartmentSystemController@companyIncorporation' )->name( 'admin-companyIncorporation' );
} );

// Form D Annexure
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'formDAnnexureView', 'LaborDepartmentSystemController@formDAnnexureView' )->name( 'admin-formDAnnexureView' );
    Route::get( 'formDAnnexure', 'LaborDepartmentSystemController@formDAnnexure' )->name( 'admin-formDAnnexure' );
} );

// // Company Name registration Number Change
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'companyNameRegistrationNumberChangeView', 'LaborDepartmentSystemController@companyNameRegistrationNumberChangeView' )->name( 'admin-companyNameRegistrationNumberChangeView' );
    Route::get( 'companyNameRegistrationNumberChange', 'LaborDepartmentSystemController@companyNameRegistrationNumberChange' )->name( 'admin-companyNameRegistrationNumberChange' );
} );

// // Change of Director
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'changeOfDirectorView', 'LaborDepartmentSystemController@changeOfDirectorView' )->name( 'admin-changeOfDirectorView' );
    Route::get( 'changeOfDirector', 'LaborDepartmentSystemController@changeOfDirector' )->name( 'admin-changeOfDirector' );
} );

// // Liquidation/ Strike off
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'liquidationView', 'LaborDepartmentSystemController@liquidationView' )->name( 'admin-liquidationView' );
    Route::get( 'liquidation', 'LaborDepartmentSystemController@liquidation' )->name( 'admin-liquidation' );
} );

// // Amalgamations
Route::prefix( '/admin/' )->group( function () {
    Route::get( 'amalgamationsView', 'LaborDepartmentSystemController@amalgamationsView' )->name( 'admin-amalgamationsView' );
    Route::get( 'amalgamations', 'LaborDepartmentSystemController@amalgamations' )->name( 'admin-amalgamations' );
} );


