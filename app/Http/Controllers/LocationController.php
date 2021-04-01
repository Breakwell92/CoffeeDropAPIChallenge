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
     * @param  string $postcode
     * @return App\Http\Resources\LocationResource
     */
    public function getNearestLocation(string $postcode) : LocationResource
    {

        // TODO: Add error handling 

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
                ->first();

        return new LocationResource($nearestLocation);
    }

    /**
     * Store a new location
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\LocationResource
     */
    public function storeNewLocation(Request $request) : LocationResource
    {
        // Collect data from request
        $postcode = $request->postcode;
        $open_times = $request->opening_times;
        $closed_times = $request->closing_times;

        // TODO: Validate postcode first

        // Establish the users latitude and longitude
        $lat_long = PostcodeHelper::getLatitudeLongitude($postcode);

        $location = Location::create([
            'postcode' => $postcode,
            'latitude' => $lat_long['latitude'],
            'longitude' => $lat_long['longitude']
        ]);

        foreach($open_times as $day=>$time){
            $location->openingTimes()->create([
                'day' => $day,
                'open_time' => $time,
                'closed_time' => $closed_times[$day]
            ]);
        }

        return new LocationResource($location);

    }

}
