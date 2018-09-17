<?php

namespace App\Http\Controllers;

use App\CompanyIncorporationModel;
use App\CompanyCertificate;
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

        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;

        return view( 'civiewform', [ 'companyname' => $companyname, 'registration_no' => $registration_no,] );
    }
}
