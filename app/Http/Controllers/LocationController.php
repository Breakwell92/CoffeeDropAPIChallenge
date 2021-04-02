<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

use App\Models\Location;
use App\Http\Resources\LocationResource;

use App\Helpers\PostcodeHelper;

class LocationController extends Controller
{

    /**
     * Find the nearest location based on the user's input
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\LocationResource
     */
    public function getNearestLocation(Request $request) 
    {
        $postcode = $request->postcode;

        if(!$postcode){
            return response()->json(['message' => 'Please enter a postcode'], 412);
        }

        // Validate the postcode the user input
        if(!PostcodeHelper::validatePostcode($postcode)){
            abort(400, 'Postcode is invalid');
        }

        // Establish the users latitude and longitude
        $user_lat_long = PostcodeHelper::getLatitudeLongitude($postcode);

        // Destructure latitude and longitude array
        ['latitude' => $latitude, 'longitude' => $longitude] = $user_lat_long;

        // Haversine formula as sql script to calculate distance from users latitude and longitude and Coffee Drop Locations
        $distance = "( 3959 * acos( cos( radians($latitude) ) * cos( radians(latitude) ) * cos( radians(longitude) - radians($longitude) ) + sin( radians($latitude) ) * sin(radians(latitude))) )";

        // Calculate distance to all locations, order acending and return the first result as the nearest to the user
        $nearestLocation = 
            Location::select('id', 'postcode', 'latitude', 'longitude', DB::raw("$distance AS distance"))
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with('openingTimes')
                ->orderBy('distance', 'asc')
                ->first();

        return new LocationResource($nearestLocation);
    }

    /**
     * Store a new location
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\LocationResource
     */
    public function storeNewLocation(Request $request)
    {

        // Collect data from request
        $postcode = $request->postcode;
        $open_times = $request->opening_times;
        $closed_times = $request->closing_times;

        // Validate the postcode the user input
        if(!PostcodeHelper::validatePostcode($postcode)){
            return response()->json(['message' => 'Postcode invalid'], 400);
        }

        // Establish the users latitude and longitude
        $lat_long = PostcodeHelper::getLatitudeLongitude($postcode);

        $location = Location::create([
            'postcode' => $postcode,
            'latitude' => $lat_long['latitude'],
            'longitude' => $lat_long['longitude']
        ]);

        foreach($open_times as $day=>$time){
            if($time != null){
                try{
                    $location->openingTimes()->create([
                        'day' => $day,
                        'open_time' => $time,
                        'closed_time' => $closed_times[$day]
                    ]); 
                }catch(\Illuminate\Database\QueryException $e){
                    // Catch for invalid time format entered
                    if(str_contains($e->getMessage(), 'Incorrect time value:')){
                        $location->forceDelete();
                        return response()->json(['message' => $time.' is not a correct time format. Please use the 00:00 format'], 400);

                    // Catch for invalid day of week string, where the same incorrect key is used in both open and closed time arrays
                    }else if(str_contains($e->getMessage(), 'Incorrect time value:')){
                        $location->forceDelete();
                        return response()->json(['message' => $day.' is not a day of the week. Please check an try again'], 400);
                    }
                }catch(\ErrorException $e){
                    // Catch for when incorrect day of week string used in open_times, as error occurs on line 98 when trying to access
                    // closed_times using the day string. This error is thrown before attempting to store in the DB
                    if(str_contains($e->getMessage(), 'Undefined array key') && $e->getFile() == __FILE__){
                        $location->forceDelete();
                        return response()->json(['message' => $day.' is not a day of the week. Please check an try again'], 400);
                    }
                }catch(\Exception $e){
                    throw $e;
                }
               
            }
            
        }

        return new LocationResource($location);

    }

}
