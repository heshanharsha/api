<?php

namespace App\Http\Controllers;

use App\CompanyIncorporationModel;
use App\CompanyCertificate;
use App\Addresses;

use Illuminate\Http\Request;

class CompanyIncorporationController extends Controller
{   
    public function civiewform(  ) {
		return view( 'civiewform' );
    }
    
    public function getcidetails( Request $request ) {
        $id = $request->input( 'company_id' );

        // company name
        $company = CompanyIncorporationModel::where( 'id',$id )->first();
        $companyname  = $company->name;

        // company registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;

        // company address
        $address_id_in_companies_table = CompanyIncorporationModel::where( 'id',$id )->first();
        $address_id = $address_id_in_companies_table->address_id;
        $address_id_in_address_table = Addresses::where( 'id',$address_id)->first();
        $address1 = $address_id_in_address_table->address1;
        $address2 = $address_id_in_address_table->address2;
        $city = $address_id_in_address_table->city;

        return view( 'civiewform', [ 'companyname' => $companyname, 'registration_no' => $registration_no, 
        'address1' => $address1, 
        'address2' => $address2,
        'city' => $city
        ]  
        );
    }
}
