<?php

namespace App\Http\Controllers;

use App\Companies;
use App\CompanyCertificate;
use App\Addresses;
use App\CompanyMembers;

use Illuminate\Http\Request;

class CompanyIncorporationController extends Controller
{   
    public function civiewform(  ) {
		return view( 'civiewform' );
    }
    
    public function getcidetails( Request $request ) {
        $id = $request->input( 'company_id' );

        // company name
        $company = Companies::where( 'id',$id )->first();
        $companyname  = $company->name;
        // Nature of Business(objective)
        $objective  = $company->objective;


        // company registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;

        // company address
        $address_id_in_companies_table = Companies::where( 'id',$id )->first();
        $address_id = $address_id_in_companies_table->address_id;
        $address_id_in_address_table = Addresses::where( 'id',$address_id)->first();
        $address1 = $address_id_in_address_table->address1;
        $address2 = $address_id_in_address_table->address2;
        $city = $address_id_in_address_table->city;
        //Location
        $location = $address_id_in_address_table->district;

        // Name/ NIC number of any director
        //$companymembers = CompanyMembers::where('company_id',$id)->groupBy('id')->havingRaw('designation_type = 69')->get();
         $companymembers = CompanyMembers::where('company_id',$id)->where('designation_type',69)->get();


        // Name/ Address and the Telephone Number of the company secretary
        $companysecretary = CompanyMembers::where('company_id',$id)->where('designation_type',70)->first();
        $secretaryfirstname = $companysecretary->first_name;
        $secretarylastname = $companysecretary->last_name;
        //$secretaryaddress = $companysecretary->address2;
        //$secretarytelephone = $companysecretary->city;
        

        


        return view( 'civiewform', [ 'companyname' => $companyname, 'registration_no' => $registration_no, 
        'address1' => $address1, 
        'address2' => $address2,
        'city' => $city,
        'companymembers' => $companymembers,
        'location' => $location,
        'objective' => $objective,
        'secretaryfirstname ' =>$secretaryfirstname,
        'secretarylastname ' =>$secretarylastname,
        ]  
        );
    }
}
