<?php

namespace App\Http\Controllers;

use App\CompanyIncorporationModel;
use Illuminate\Http\Request;

class CompanyIncorporationController extends Controller
{   
    public function civiewform(  ) {
		return view( 'civiewform' );
    }
    
    public function getcidetails( Request $request ) {
        $id = $request->input( 'company_id' );
        $company = CompanyIncorporationModel::where( 'id',$id )->first();
        $companyname  = $company->name;
        return view( 'civiewform', [ 'companyname' => $companyname,] );
    }
}
