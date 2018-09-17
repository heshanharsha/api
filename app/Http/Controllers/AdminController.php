<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewform(  ) {
		return view( 'companyidinsert' );
	}
    public function getcompanydetails( Request $request ) {
        $id = $request->input( 'company_id' );
        $company = Company::where( 'id',$id )->first();
        $companyname  = $company->name;
        return view( 'companyidinsert', [ 'companyname' => $companyname,] );
    }
}
