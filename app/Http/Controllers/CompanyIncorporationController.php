<?php

namespace App\Http\Controllers;

use App\Companies;
use App\CompanyCertificate;
use App\Addresses;
use App\CompanyMembers;

use Illuminate\Http\Request;

class CompanyIncorporationController extends Controller
{   

    //..................................................................................
    //..................................................................................
    //............................Company Incorporation.................................
    //..................................................................................
    //..................................................................................

    public function civiewform(  ) {
		return view( 'civiewform' );
    }
    

    //.......... Company Incorporation ..........//
    public function getcidetails( Request $request ) {

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );


        //.......... Retrieve data from database and assign to variable ..........//

        // company name
        $company = Companies::where( 'id',$id )->first();
        $companyname  = $company->name;
        
        // Nature of Business(objective)
        $objective  = $company->objective;

        // Business  registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;

        // Registered Address of the Company
        $address_id_in_companies_table = Companies::where( 'id',$id )->first();
        $address_id = $address_id_in_companies_table->address_id;
        $address_id_in_address_table = Addresses::where( 'id',$address_id)->first();
        $address1 = $address_id_in_address_table->address1;
        $address2 = $address_id_in_address_table->address2;
        $city = $address_id_in_address_table->city;

        // Location
        $location = $address_id_in_address_table->district;

        // Name/ NIC number of any director
        $companymembers = CompanyMembers::where('company_id',$id)->where('designation_type',69)->get();

        // Name/ Address and the Telephone Number of the company secretary
        $companysecretary = CompanyMembers::where('company_id',$id)->where('designation_type',70)->first();

        
        //..........XML -> Company Incorporation..........//

        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("CompanyIncorporation");
        $CompanyIncorporation = $xml->appendChild($container);
            
            // Nature of Business
            $NatureofBusiness = $xml->createElement("NatureofBusiness",$objective); 
            $CompanyIncorporation->appendChild($NatureofBusiness);

            // Business Registration Number
            $BusinessRegistrationNumber = $xml->createElement("BusinessRegistrationNumber",$registration_no); 
            $CompanyIncorporation->appendChild($BusinessRegistrationNumber);

            // Registered Address of the Company
            $RegisteredAddress = $xml->createElement("RegisteredAddress",$address1." ". $address2." ". $city); 
            $CompanyIncorporation->appendChild($RegisteredAddress);

            // Location
            $DistrictDivisionalSecretariat = $xml->createElement("DistrictDivisionalSecretariat",$location); 
            $CompanyIncorporation->appendChild($DistrictDivisionalSecretariat);

            $i=0;

            // Name/ NIC number of any director
            $DirectorsNICNumbers = $xml->createElement("DirectorsNICNumbers");
            $CompanyIncorporation->appendChild($DirectorsNICNumbers);

            foreach($companymembers as $companymember){
                $i = $i+1;
                    $NameoftheproprietorNICnumber = $xml->createElement("NameoftheproprietorNICnumber",$companymember->nic); 
                    $DirectorsNICNumbers->appendChild($NameoftheproprietorNICnumber);
            }
            
            // Telephone/ email/ Fax of the selected director.
            $DirectorsTelephonesNumber = $xml->createElement("DirectorsTelephonesNumber");
            $CompanyIncorporation->appendChild($DirectorsTelephonesNumber);

            foreach($companymembers as $companymember){
                $i = $i+1;
                    $DirectorEmailPhoneFax = $xml->createElement("DirectorEmailPhoneFax",$companymember->telephone." ".$companymember->email); // Name/ NIC number of any director
                    $DirectorsTelephonesNumber->appendChild($DirectorEmailPhoneFax);
            }

            // Date of Incorporation
            $DateOfIncorporation = $xml->createElement("DateOfIncorporation",$company->created_at);
            $CompanyIncorporation->appendChild($DateOfIncorporation);

            // Name/ Address and the Telephone Number of the company secretary
            $SecratryDetails = $xml->createElement("SecratryDetails"); 
            $CompanyIncorporation->appendChild($SecratryDetails);

                // Name of the company secretary
                $SecratyName = $xml->createElement("SecratyName"); 
                $SecratryDetails->appendChild($SecratyName);

                    // First Name of the company secretary
                    $SecratyFirstName = $xml->createElement("SecratyFirstName",$companysecretary->first_name); 
                    $SecratyName->appendChild($SecratyFirstName);

                    // Last Name of the company secretary
                    $SecratyLastName = $xml->createElement("SecratyLastName",$companysecretary->last_name); 
                    $SecratyName->appendChild($SecratyLastName);

                // Address of the company secretary
                $SecretaryAddress = $xml->createElement("SecretaryAddress"); 
                $SecratryDetails->appendChild($SecretaryAddress);
                    
                    // Address Line 1 of the company secretary
                    $SecretaryAddress1 = $xml->createElement("SecretaryAddress1",$companysecretary->last_name); 
                    $SecretaryAddress->appendChild($SecretaryAddress1);

                    // Address Line 2 of the company secretary
                    $SecretaryAddress2 = $xml->createElement("SecretaryAddress2",$companysecretary->last_name); 
                    $SecretaryAddress->appendChild($SecretaryAddress2);

                    // city of the company secretary
                    $SecretaryCity = $xml->createElement("SecretaryCity",$companysecretary->last_name); 
                    $SecretaryAddress->appendChild($SecretaryCity);

                // Telephone Number of the company secretary
                $SecratyTelephone = $xml->createElement("SecratyTelephone",$companysecretary->telephone); 
                $SecratryDetails->appendChild($SecratyTelephone);

            // Total Number of Employees
            $TotalNumberofEmployees = $xml->createElement("TotalNumberofEmployees"); 
            $CompanyIncorporation->appendChild($TotalNumberofEmployees);
            
            // Total Number of Employees in covered employment
            $TotalNumberofEmployeesincoveredemployment = $xml->createElement("TotalNumberofEmployeesincoveredemployment"); 
            $CompanyIncorporation->appendChild($TotalNumberofEmployeesincoveredemployment);

            // Employee recruited date
            $Employeerecruiteddate = $xml->createElement("Employeerecruiteddate"); 
            $CompanyIncorporation->appendChild($Employeerecruiteddate);

            // As per the form D Not Mandatory
            $AspertheformDNotMandatory = $xml->createElement("AspertheformDNotMandatory");  
            $CompanyIncorporation->appendChild($AspertheformDNotMandatory);

            // As per the form D Not Mandatory 
            $AspertheformDNotMandatory = $xml->createElement("AspertheformDNotMandatory"); 
            $CompanyIncorporation->appendChild($AspertheformDNotMandatory);

            // As per the form D Not Mandatory 
            $AspertheformDNotMandatory = $xml->createElement("AspertheformDNotMandatory"); 
            $CompanyIncorporation->appendChild($AspertheformDNotMandatory);

            // As per the form D Not Mandatory
            $AspertheformDNotMandatory = $xml->createElement("AspertheformDNotMandatory");  
            $CompanyIncorporation->appendChild($AspertheformDNotMandatory);

            // BR Certificate
            $BRCertificate = $xml->createElement("BRCertificate"); 
            $CompanyIncorporation->appendChild($BRCertificate);

        $xml->FormatOutput = true;
        $xml->saveXML();
        $xml->save("example.xml");
        $xml->load("example.xml");
        dd($xml);
    }

    //..................................................................................
    //..................................................................................
    //...............................Form D Annexure....................................
    //..................................................................................
    //..................................................................................

    public function formDAnnexureView(  ) {
		return view( 'formDAnnexureView' );
    }

    //......... Form D Annexure ..........//
    public function formDAnnexure( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//

        //..........XML -> Form D Annexure ..........//

        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("FormDAnnexure");
        $FormDAnnexure = $xml->appendChild($container);
            
            // Name of Establishment (Company Name)
            $NameOfEstablishment = $xml->createElement("NameOfEstablishment"); 
            $FormDAnnexure->appendChild($NameOfEstablishment);

            // Employee Details
            $EmployeeDetails = $xml->createElement("EmployeeDetails"); 
            $FormDAnnexure->appendChild($EmployeeDetails);

                // Employee Name
                $EmployeeName = $xml->createElement("EmployeeName"); 
                $EmployeeDetails->appendChild($EmployeeName);

                // Date of Appointment
                $DateOfAppointment = $xml->createElement("DateOfAppointment"); 
                $EmployeeDetails->appendChild($DateOfAppointment);

                // Salary of That Date
                $SalaryOfThatDate = $xml->createElement("SalaryOfThatDate"); 
                $EmployeeDetails->appendChild($SalaryOfThatDate);

                // Nature of Employment
                $NatureOfEmployment = $xml->createElement("NatureOfEmployment"); 
                $EmployeeDetails->appendChild($NatureOfEmployment);

                // Age of Employee on that date
                $AgeOfEmployeeOnThatDate = $xml->createElement("AgeOfEmployeeOnThatDate"); 
                $EmployeeDetails->appendChild($AgeOfEmployeeOnThatDate);

                // ID number
                $IDNumber = $xml->createElement("IDNumber"); 
                $EmployeeDetails->appendChild($IDNumber);

                // Personal Address
                $PersonalAddress = $xml->createElement("PersonalAddress"); 
                $EmployeeDetails->appendChild($PersonalAddress);
            
            // Personal Address of the employer
            $PersonalAddressOfTheEmployer = $xml->createElement("PersonalAddressOfTheEmployer"); 
            $FormDAnnexure->appendChild($PersonalAddressOfTheEmployer);

            // Date of Incorporation
            $DateOfIncorporation = $xml->createElement("DateOfIncorporation"); 
            $FormDAnnexure->appendChild($DateOfIncorporation);

            // Earlier EPF number
            $EarlierEPFNumber = $xml->createElement("EarlierEPFNumber"); 
            $FormDAnnexure->appendChild($EarlierEPFNumber);

            // Number of employees
            $NumberOfEmployees = $xml->createElement("NumberOfEmployees"); 
            $FormDAnnexure->appendChild($NumberOfEmployees);


            $xml->FormatOutput = true;
            $xml->saveXML();
            $xml->save("formDAnnexure.xml");
            $xml->load("formDAnnexure.xml");
            dd($xml);

    }

    //..................................................................................
    //..................................................................................
    //................Company Name registration Number Change...........................
    //..................................................................................
    //..................................................................................
    
    public function companyNameRegistrationNumberChangerView(  ) {
		return view( 'companyNameRegistrationNumberChangerView' );
    }

    //......... Company Name registration Number Change ..........//
    public function companyNameRegistrationNumberChanger( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//

        //..........XML -> Company Name registration Number Change ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("CompanyNameRegistrationNumberChangerView");
        $CompanyNameRegistrationNumberChangerView = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($BRNumber);

            // New Name
            $NewName = $xml->createElement("NewName"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($NewName);

            // New Address
            $NewAddress = $xml->createElement("NewAddress"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($NewAddress);

            // New BR Number
            $NewBRNumber = $xml->createElement("NewBRNumber"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($NewBRNumber);

            // BR Certificate
            $BRCertificate = $xml->createElement("BRCertificate"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($BRCertificate);

            // Date
            $Date = $xml->createElement("Date"); 
            $CompanyNameRegistrationNumberChangerView->appendChild($Date);
    }

    //..................................................................................
    //..................................................................................
    //.............................Change of Director...................................
    //..................................................................................
    //..................................................................................
    
    public function changeOfDirectorView(  ) {
		return view( 'changeOfDirectorView' );
    }

    //......... Change of Director ..........//
    public function changeOfDirector( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//

        //..........XML -> Change of Director ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("ChangeOfDirector");
        $ChangeOfDirector = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber"); 
            $ChangeOfDirector->appendChild($BRNumber);

            // Add or Remove
            $AddOrRemove = $xml->createElement("AddOrRemove"); 
            $ChangeOfDirector->appendChild($AddOrRemove);

            // Name of Director
            $NameOfDirector = $xml->createElement("NameOfDirector"); 
            $ChangeOfDirector->appendChild($NameOfDirector);

            // Address of Director
            $AddressOfDirector = $xml->createElement("AddressOfDirector"); 
            $ChangeOfDirector->appendChild($AddressOfDirector);

            // NIC/ Passport Number
            $NICOrPassportNumber = $xml->createElement("NICOrPassportNumber"); 
            $ChangeOfDirector->appendChild($NICOrPassportNumber);

                // passport issuing country
                $PassportIssuingCountry = $xml->createElement("PassportIssuingCountry"); 
                $NICOrPassportNumber->appendChild($PassportIssuingCountry);

            // Date
            $Date = $xml->createElement("Date"); 
            $ChangeOfDirector->appendChild($Date);
    }

    //..................................................................................
    //..................................................................................
    //.............................Liquidation/ Strike off...................................
    //..................................................................................
    //..................................................................................
    
    public function liquidationView(  ) {
		return view( 'liquidationView' );
    }

    //......... Change of Director ..........//
    public function liquidation( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//

        //..........XML -> Liquidation/ Strike off ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("Liquidation");
        $Liquidation = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber"); 
            $Liquidation->appendChild($BRNumber);

            // Date
            $Date = $xml->createElement("Date"); 
            $Liquidation->appendChild($Date);
    }

    //..................................................................................
    //..................................................................................
    //.............................Amalgamations...................................
    //..................................................................................
    //..................................................................................
    
    public function amalgamationsView(  ) {
		return view( 'amalgamationsView' );
    }

    //......... Change of Director ..........//
    public function amalgamations( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//

        //..........XML -> Amalgamations ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("Amalgamations");
        $Amalgamations = $xml->appendChild($container);
            
            // BR Number of the closed company
            $ClosedCompanyBRNumber = $xml->createElement("ClosedCompanyBRNumber"); 
            $Amalgamations->appendChild($ClosedCompanyBRNumber);

            // Effective Date
            $EffectiveDate = $xml->createElement("EffectiveDate"); 
            $Liquidation->appendChild($EffectiveDate);

            // BR number of the amalgamated company
            $AmalgamatedCompanyBRNumber = $xml->createElement("AmalgamatedCompanyBRNumber"); 
            $Amalgamations->appendChild($AmalgamatedCompanyBRNumber);

            // BR Certificate
            $BRCertificate = $xml->createElement("BRCertificate"); 
            $Amalgamations->appendChild($BRCertificate);
    }
    

}
