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
        $NameofEstablishment = $companyname;
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

        // $companysecraties = CompanyMembers::where('company_id',$id)->where('designation_type',70)->get();
        // $addressarrays=[];
        // foreach ($companysecraties as $companysecretary){
        //     $addresse = Addresse::where( 'id',$companysecretary->address_id )->first();
        //     $addressarrays[$companysecretary->address_id] = $addresse;
        // }
        // $secratiesfirstname=[];
        // foreach ($companysecraties as $companysecretary){
        //     $secratiesfirstname = CompanyMembers::where( 'company_id',$companysecretary->address_id )->first();
        //     $addressarrays[$companysecretary->address_id] = $addresse;
        // }

        //$secretaryfirstname = $companysecretary->first_name;
        //$secretarylastname = $companysecretary->last_name;
        //$secretaryaddress = $companysecretary->address2;
        //$secretarytelephone = $companysecretary->city;
        

        


        // return view( 'civiewform', [ 'companyname' => $companyname, 'registration_no' => $registration_no, 
        //  'address1' => $address1, 
        //  'address2' => $address2,
        //  'city' => $city,
        //  'companymembers' => $companymembers,
        //  'location' => $location,
        //  'objective' => $objective,
        // // //'companysecretary' => $companysecretary,
        // // //'secretaryfirstname ' =>$secretaryfirstname,
        // // //'secretarylastname ' =>$secretarylastname,
        // ]  
        // );

        $createDate = new \DateTime($company->created_at);
        $strip = $createDate->format('Y-m-d');
        $num = str_replace('-', '', $strip);
        $incoDate = (int) $num;
        $companytypeid = $company->type_id;
        $checksum = $incoDate+$companytypeid;
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("CompanyIncorporation");
        $container = $xml->appendChild($container);
            $header = $xml->createElement("Header");
            $container->appendChild($header);
                $username = $xml->createElement("username","username");
                $header->appendChild($username);
                $password = $xml->createElement("password","password");
                $header->appendChild($password);
                $checksum = $xml->createElement("checksum",$checksum);
                $header->appendChild($checksum);
            $SendRegisteredCompanyFile = $xml->createElement("SendRegisteredCompanyFile");
            $container->appendChild($SendRegisteredCompanyFile);
                $maindetails = $xml->createElement("MainDetails");
                $SendRegisteredCompanyFile->appendChild($maindetails);

                    $NameofEstablishment = $xml->createElement("NameofEstablishment"); // Company Name
                    $maindetails->appendChild($NameofEstablishment);

                    $NatureofBusiness = $xml->createElement("NatureofBusiness"); // Nature of Business
                    $maindetails->appendChild($NatureofBusiness);

                    $BusinessRegistrationNumber = $xml->createElement("BusinessRegistrationNumber"); // Business Registration Number
                    $maindetails->appendChild($BusinessRegistrationNumber);

                    $RegisteredAddress = $xml->createElement("RegisteredAddress"); // Registered Address of the Company
                    $maindetails->appendChild($RegisteredAddress);

                    $DistrictDivisionalSecretariat = $xml->createElement("DistrictDivisionalSecretariat"); // Location
                    $maindetails->appendChild($DistrictDivisionalSecretariat);

                    $NameoftheproprietorNICnumber = $xml->createElement("NameoftheproprietorNICnumber"); // Name/ NIC number of any director
                    $maindetails->appendChild($NameoftheproprietorNICnumber);

                    $TelephoneemailFax = $xml->createElement("TelephoneemailFax"); // Telephone/ email/ Fax of the selected director
                    $maindetails->appendChild($TelephoneemailFax);

                    $NameofManager = $xml->createElement("NameofManager"); // Name/ Address and the Telephone Number of the company secretary
                    $maindetails->appendChild($NameofManager);
        $xml->FormatOutput = true;
        $xml->saveXML();
        $xml->save("example.xml");
        $xml->load("example.xml");
        dd($xml);
    }
}
