<?php

namespace App\Http\Controllers;

use App\Companies;
use App\CompanyCertificate;
use App\Addresses;
use App\CompanyMembers;
use App\Settings;

use Illuminate\Http\Request;

class LaborDepartmentSystemController extends Controller
{   

    //..................................................................................
    //..................................................................................
    //............................Company Incorporation.................................
    //..................................................................................
    //..................................................................................

    public function companyIncorporationView(  ) 
    {
		return view( 'companyIncorporationView' );
    }
    

    //.......... Company Incorporation ..........//
    public function companyIncorporation( Request $request ) 
    {

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );


        //.......... Retrieve data from database and assign to variable ..........//

        // get relevent company id record
        $company = Companies::where( 'id',$id )->first();

        // company name
        if(strrchr($company->name, "&")==false){
            $companyname  = $company->name;
        }else{
            $companyname  = str_replace('&','&amp;', $company->name);
        }
        
        
        // Nature of Business(objective)
        $objective_id  = $company->objective; // get relevent company object code that unique to relevent company from company record
        $settings = Settings::where('id',$objective_id)->first(); // map that object code to eroc_settings table and get that record
        $objective = $settings->value; // get the objective valu in string format

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

        // All directors record
        $Directors = CompanyMembers::where('company_id',$id)->where('designation_type',69)->get();

        // All secretaries record
        $secretaries = CompanyMembers::where('company_id',$id)->where('designation_type',70)->get();
 


        
        //..........XML -> Company Incorporation..........//

        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("CompanyIncorporation");
        $CompanyIncorporation = $xml->appendChild($container);

            // Name of Establishment
            $NameOfEstablishment = $xml->createElement("NameOfEstablishment",$companyname); 
            $CompanyIncorporation->appendChild($NameOfEstablishment);
            
            // Nature of Business
            $NatureOfBusiness = $xml->createElement("NatureOfBusiness",$objective); 
            $CompanyIncorporation->appendChild($NatureOfBusiness);

            // Business Registration Number
            $BusinessRegistrationNumber = $xml->createElement("BusinessRegistrationNumber",$registration_no); 
            $CompanyIncorporation->appendChild($BusinessRegistrationNumber);

            // Registered Address of the Company
            $RegisteredAddress = $xml->createElement("RegisteredAddress",$address1." ". $address2." ". $city); 
            $CompanyIncorporation->appendChild($RegisteredAddress);

            // Location
            $DistrictDivisionalSecretariat = $xml->createElement("DistrictDivisionalSecretariat",$location); 
            $CompanyIncorporation->appendChild($DistrictDivisionalSecretariat);

            // Directors Details
            // Directors Date of Incorporation 
            $createDate = new \DateTime($company->created_at);
            $strip = $createDate->format('Y-m-d');
            $directorDateOfIncorporation = str_replace('-', '', $strip);

            

            foreach ($Directors as $Director){
                
                if((!$Director->nic))
                {   
                    // Foreign Director Details
                    // Passport number and Issuing Country
                    $DirectorNICorPassport = $xml->createElement("DirectorNICorPassport",$Director->passport_no." ".$Director->passport_issued_country); 
                    $CompanyIncorporation->appendChild($DirectorNICorPassport);
                        
                        // Telephone/ email of the above directors.
                        $EmailandPhone = $xml->createElement("EmailandPhone",$Director->telephone." ".$Director->email); // Name/ NIC number of any director
                        $DirectorNICorPassport->appendChild($EmailandPhone);

                        // Date of Incorporation 
                        $DateOfIncorporation = $xml->createElement("DateOfIncorporation",$directorDateOfIncorporation);
                        $DirectorNICorPassport->appendChild($DateOfIncorporation);
                
                }
                else
                {   
                    // Local Directors
                    // NIC number of directors
                    $DirectorNICorPassport = $xml->createElement("DirectorNICorPassport",$Director->nic); 
                    $CompanyIncorporation->appendChild($DirectorNICorPassport);

                        // Telephone/ email of the above directors.
                        $EmailandPhone = $xml->createElement("EmailandPhone",$Director->telephone." ".$Director->email); // Name/ NIC number of any director
                        $DirectorNICorPassport->appendChild($EmailandPhone);

                        // Date of Incorporation
                        $DateOfIncorporation = $xml->createElement("DateOfIncorporation",$directorDateOfIncorporation);
                        $DirectorNICorPassport->appendChild($DateOfIncorporation);
                    
                }
            }
            

            foreach($secretaries as $secretary){
                // pick the induvidual Secratary address id
                $secretaryAddID = $secretary->address_id;
                // map secretaryAddID to address table
                $secretatyAddRecord = Addresses::where('id',$secretaryAddID)->first();

                // Name/ Address and the Telephone Number of the company secretary
                $SecratryDetails = $xml->createElement("SecratryDetails"); 
                $CompanyIncorporation->appendChild($SecratryDetails);

                    // Name of the company secretary
                    $Name = $xml->createElement("Name"); 
                    $SecratryDetails->appendChild($Name);

                        // First Name of the company secretary
                        $FirstName = $xml->createElement("FirstName",$secretary->first_name); 
                        $Name->appendChild($FirstName);

                        // Last Name of the company secretary
                        $LastName = $xml->createElement("LastName",$secretary->last_name); 
                        $Name->appendChild($LastName);

                // Address of the company secretary
                $Address = $xml->createElement("Address"); 
                $SecratryDetails->appendChild($Address);
                    
                    // Address Line 1 of the company secretary
                    $AddressLine1 = $xml->createElement("AddressLine1",$secretatyAddRecord->address1); 
                    $Address->appendChild($AddressLine1);

                    // Address Line 2 of the company secretary
                    $Addressline2 = $xml->createElement("Addressline2",$secretatyAddRecord->address2); 
                    $Address->appendChild($Addressline2);

                    // city of the company secretary
                    $City = $xml->createElement("City",$secretatyAddRecord->city); 
                    $Address->appendChild($City);

                // Telephone Number of the company secretary
                $Telephone = $xml->createElement("Telephone",$secretary->telephone); 
                $SecratryDetails->appendChild($Telephone);

            }



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
        $xml->save("CompanyIncorporation.xml");
        $xml->load("CompanyIncorporation.xml");
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
        // get relevent company id record
        $company = Companies::where( 'id',$id )->first();
        // company name
        if(strrchr($company->name, "&")==false){
            $companyname  = $company->name;
        }else{
            $companyname  = str_replace('&','&amp;', $company->name);
        }

        //..........XML -> Form D Annexure ..........//

        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("FormDAnnexure");
        $FormDAnnexure = $xml->appendChild($container);
            
            // Name of Establishment (Company Name)
            $NameOfEstablishment = $xml->createElement("NameOfEstablishment",$companyname); 
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
    
    public function companyNameRegistrationNumberChangeView(  ) {
		return view( 'companyNameRegistrationNumberChangeView' );
    }

    //......... Company Name registration Number Change ..........//
    public function companyNameRegistrationNumberChange( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );


        //.......... Retrieve data from database and assign to variable ..........//

        // Business  registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;



        //..........XML -> Company Name registration Number Change ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("CompanyNameRegistrationNumberChange");
        $CompanyNameRegistrationNumberChange = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber",$registration_no); 
            $CompanyNameRegistrationNumberChange->appendChild($BRNumber);

            // New Name
            $NewName = $xml->createElement("NewName"); 
            $CompanyNameRegistrationNumberChange->appendChild($NewName);

            // New Address
            $NewAddress = $xml->createElement("NewAddress"); 
            $CompanyNameRegistrationNumberChange->appendChild($NewAddress);

            // New BR Number
            $NewBRNumber = $xml->createElement("NewBRNumber"); 
            $CompanyNameRegistrationNumberChange->appendChild($NewBRNumber);

            // BR Certificate
            $BRCertificate = $xml->createElement("BRCertificate"); 
            $CompanyNameRegistrationNumberChange->appendChild($BRCertificate);

            // Date
            $Date = $xml->createElement("Date"); 
            $CompanyNameRegistrationNumberChange->appendChild($Date);

            $xml->FormatOutput = true;
            $xml->saveXML();
            $xml->save("companyNameRegistrationNumberChange.xml");
            $xml->load("companyNameRegistrationNumberChange.xml");
            dd($xml);
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
        // Business  registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;



        //..........XML -> Change of Director ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("ChangeOfDirector");
        $ChangeOfDirector = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber",$registration_no); 
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

            $xml->FormatOutput = true;
            $xml->saveXML();
            $xml->save("ChangeOfDirector.xml");
            $xml->load("ChangeOfDirector.xml");
            dd($xml);
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
        // Business  registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;


        //..........XML -> Liquidation/ Strike off ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("Liquidation");
        $Liquidation = $xml->appendChild($container);
            
            // BR Number
            $BRNumber = $xml->createElement("BRNumber",$registration_no); 
            $Liquidation->appendChild($BRNumber);

            // Date
            $Date = $xml->createElement("Date"); 
            $Liquidation->appendChild($Date);

            $xml->FormatOutput = true;
            $xml->saveXML();
            $xml->save("liquidation.xml");
            $xml->load("liquidation.xml");
            dd($xml);
    }

    //..................................................................................
    //..................................................................................
    //.............................Amalgamations...................................
    //..................................................................................
    //..................................................................................
    
    public function amalgamationsView(  ) {
		return view( 'amalgamationsView' );
    }

    //......... Amalgamations ..........//
    public function amalgamations( Request $request ){

        //.......... Company ID that manual enter from view .........//
        $id = $request->input( 'company_id' );

        //.......... Retrieve data from database and assign to variable ..........//
        // Business  registration number
        $companycertificate = CompanyCertificate::where('company_id',$id)->first();
        $registration_no = $companycertificate->registration_no;

        //..........XML -> Amalgamations ..........//
        $xml = new \DOMDocument("1.0","UTF-8");
        $container = $xml->createElement("Amalgamations");
        $Amalgamations = $xml->appendChild($container);
            
            // BR Number of the closed company
            $ClosedCompanyBRNumber = $xml->createElement("ClosedCompanyBRNumber",$registration_no); 
            $Amalgamations->appendChild($ClosedCompanyBRNumber);

            // Effective Date
            $EffectiveDate = $xml->createElement("EffectiveDate"); 
            $Amalgamations->appendChild($EffectiveDate);

            // BR number of the amalgamated company
            $AmalgamatedCompanyBRNumber = $xml->createElement("AmalgamatedCompanyBRNumber"); 
            $Amalgamations->appendChild($AmalgamatedCompanyBRNumber);

            // BR Certificate
            $BRCertificate = $xml->createElement("BRCertificate"); 
            $Amalgamations->appendChild($BRCertificate);

            $xml->FormatOutput = true;
            $xml->saveXML();
            $xml->save("amalgamations.xml");
            $xml->load("amalgamations.xml");
            dd($xml);
    }
    

}
