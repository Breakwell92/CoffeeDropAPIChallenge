<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="token" name="_token" content="{!! csrf_token() !!}"/>

        <title>CoffeeDrop</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/coffeedrop.css') }}">

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script src="{{ asset('js/coffeedrop.js') }}"></script>

    </head>
    <body class="antialiased">
        
        <div class="jumbotron">
            <h1 class="display-4">CoffeeDrop</h1>
            <p class="lead">Welcome to CoffeeDrop. Get money back for recycling Nespresso coffee pods!</p>
        </div>

        <div class="container" id="main">

            <div class="row" id="find-location">
                <div class="col">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-sm-12 col-md-6">
                                    <h5 class="card-title">Find your local recycling location</h5>
                                    <p class="card-text">Enter your postcode below to find your nearest recycling location</p>

                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Enter your postcode" name="postcode" value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary submit" type="sumbit">Search</button>
                                        </div>
                                    </div>
                                </div>
                                    
                                <div class="col-sm-12 col-md-6 results">
                                        
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="row" id="calculate-cashback">
                <div class="col">

                    <div class="card">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-sm-12 col-md-6">

                                    <h5 class="card-title">Calculate Cashback</h5>
                                    <p class="card-text">We accept 3 types of coffee pods. Tell us how many you're recycling, and we'll tell you how much cashback you'll earn</p>
                                    <div class="row">
                                        <div class="col-9">
                                            <form>
                                                <div class="form-row">
                                                    <div class="col-sm-12 col-md-4">
                                                        <label>Ristretto</label>
                                                        <input type="number" class="form-control" name="Ristretto" value="0">
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <label>Espresso</label>
                                                        <input type="number" class="form-control" name="Espresso" value="0">
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <label>Lungo</label>
                                                        <input type="number" class="form-control" name="Lungo" value="0">
                                                    </div>
                                                </div>                                    
                                            </form> 
                                        </div>

                                        <div class="col-sm-12 col-md-3 d-flex flex-column">
                                            <button class="btn btn-primary submit mt-auto" type="sumbit">Calculate</button>
                                        </div>
                                       
                                    </div>
                                    
                                    
                                </div>

                                <div class="col-sm-12 col-md-6 results">
                                        
                                </div>                                
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row" id="new-location">
                <div class="col">

                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-12 col-md-6">

                                    <h5 class="card-title">Add a new recycling location</h5>
                                    <p class="card-text">Tell us about another recycling location and we'll add it to our database</p>

                                    <form>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Location's Postcode</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="postcode"  placeholder="Enter the location's postcode">
                                            </div>
                                        </div>
                                        <p> Opening Times: </p>
                                        
                                        @foreach($days_of_week as $day)
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">{{ ucwords($day) }}</label>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" name="opening_times[{{ $day }}]" placeholder="00:00">
                                                </div>
                                                <div class="col-sm-2 text-center">
                                                    <span>to</span>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" name="closing_times[{{ $day }}]" placeholder="23:59">
                                                </div>
                                            </div>
                                        @endforeach
                                    </form>

                                    <button class="btn btn-primary submit">Submit</button>                                   
                                    
                                </div>

                                <div class="col-sm-12 col-md-6 results">
                                        
                                </div>                                
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </body>
</html>
