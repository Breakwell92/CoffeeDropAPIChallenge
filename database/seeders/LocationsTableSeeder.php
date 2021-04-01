<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Location;
use App\Models\LocationOpeningTime;

use PostcodeHelper;

class LocationsTableSeeder extends Seeder
{

    /**
     * Path to the CSV file for all locations and opening times
     *
     * @var string
     */
    protected $locationDataFile = '../location_data.csv';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(($handle = fopen($this->locationDataFile, "r")) !== FALSE ){

            $row = 1;
            $headers = [];

            while (($location_data = fgetcsv($handle, 1000, ",")) !== FALSE ){
                if($row === 1){
                    $row++; // Skip the first row of headers
                    continue;
                }

                // Destructure location data for readability
                [
                    $postcode,
                    $monday['open'],
                    $tuesday['open'],
                    $wednesday['open'],
                    $thursday['open'],
                    $friday['open'],
                    $saturday['open'],
                    $sunday['open'],
                    $monday['closed'],
                    $tuesday['closed'],
                    $wednesday['closed'],
                    $thursday['closed'],
                    $friday['closed'],
                    $saturday['closed'],
                    $sunday['closed'],
                ] = $location_data;

                // Fetch latitude and longitude and store in DB
                $latitude_longitude = PostcodeHelper::getLatitudeLongitude($postcode);
                $location = Location::create([
                    'postcode' => $postcode,
                    'latitude' => $latitude_longitude['latitude'] ?? null,
                    'longitude' => $latitude_longitude['longitude'] ?? null
                ]);
                
                // Build opening times array to loop through and store in DB.

                $openingTimes = [
                    'monday' => $monday,
                    'tuesday' => $tuesday,
                    'wednesday' => $wednesday,
                    'thursday' => $thursday,
                    'friday' => $friday,
                    'saturday' => $saturday,
                    'sunday' => $sunday,
                ];

                foreach($openingTimes as $day=>$times){
                    if(!empty($times['open']) && !empty($times['closed'])){
                        $location->openingTimes()->create([
                            'day' => $day,
                            'open_time' => $times['open'],
                            'closed_time' => $times['closed']
                        ]);
                    }
                }
            }
        }
    }
}
