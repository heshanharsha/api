<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .full-height {
                height: 100vh;
            }
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
            .position-ref {
                position: relative;
            }
            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
            .content {
                text-align: center;
            }
            .title {
                font-size: 84px;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <h5>Company Incorparation details search</h5>
                <div class="row">
                    <div class="col-md-8 offset-2">
                        <form method="get" action="{{ route('admin-companyIncorporation') }}">
                            <br>
                            <div class="row">
                                <div class="col-md-4 offset-md-2">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="company_id"><b>Company ID</b></label>
                                            <input type="text" class="form-control" id="company_id" name="company_id">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-sm btn-primary">Check details</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <br>

                <div class="row">
                    <div class="col-md-8 offset-2">
                        <div class="form-group">
                            <!-- company name -->
                            @if(!empty($companyname))
                                <label for="company_id"><b>Company Name</b></label>
                                {{ $companyname }}
                            @else
                                <label for="company_id"><b>Company Name</b></label>
                             @endif
                             <br>
                            <!-- company registration number -->
                            @if(!empty($registration_no))
                                <label for="company_id"><b>Registration Number</b></label>
                                {{ $registration_no }}
                            @else
                                <label for="company_id"><b>Registration Number</b></label>
                            @endif
                            <br>

                            <!-- company address -->
                            @if(!empty($address1))
                                <label for="company_id"><b>Address</b></label>
                                {{ $address1 }}
                                {{ $address2 }}
                                {{ $city }}
                                
                            @else
                                <label for="company_id"><b>Address</b></label>
                            @endif
                            <br>

                            <!-- to hear -->
                            <!--  Name/ NIC number of any director -->
                            

                            <!--  Telephone/ email/ Fax of the selected director.-->
                            

                            <!-- location -->
                            @if(!empty($location))
                                <label for="company_id"><b>Location</b></label>
                                {{ $location }}
                            @else
                                <label for="company_id"><b>Company Name</b></label>
                             @endif
                             <br>

                            <!-- location -->
                            @if(!empty($objective))
                                <label for="company_id"><b>Nature of Business</b></label>
                                {{ $objective }}
                            @else
                                <label for="company_id"><b>Company Name</b></label>
                             @endif
                             <br>

                             <!-- Name/ Address and the Telephone Number of the company secretary -->
                            <!-- @if(!empty($secretarylastname))
                                <label for="company_id"><b>Name/ Address and the Telephone Number of the company secretary</b></label>
                                {{ $secretarylastname }} 
                            @else
                                <label for="company_id"><b>if is not working</b></label>
                            @endif
                            <br> -->
                            
                            <!--  Name/ Address and the Telephone Number of the company secretary using foreach -->
                            
                            

                             

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>