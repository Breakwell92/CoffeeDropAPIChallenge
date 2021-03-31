<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\LocationController;
use App\Models\Location;
use App\Models\LocationOpeningTime;

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

            while (($data = fgetcsv( $handle, 1000, ",")) !== FALSE ){
                // Skip first line containing headers.
                if ( $row === 1 ) {
                    $row++;
                    continue;
                }

                $postcode = $data[0];
                $latitude_longitude = LocationController::getLatitudeLongitude($postcode);
                $location = Location::create([
                    'postcode' => $postcode,
                    'latitude' => $latitude_longitude['latitude'] ?? null,
                    'longitude' => $latitude_longitude['longitude'] ?? null
                ]);

                $openingTimes = [
                    'monday' => [$data[1], $data[8]],
                    'tuesday' => [$data[2], $data[9]],
                    'wednesday' => [$data[3], $data[10]],
                    'thursday' => [$data[4], $data[11]],
                    'friday' => [$data[5], $data[12]],
                    'saturday' => [$data[6], $data[13]],
                    'sunday' => [$data[7], $data[14]],
                ];

                foreach($openingTimes as $day=>$times){
                    if(!empty($times[0]) && !empty($times[1])){
                        $location->openingTimes()->create([
                            'day' => $day,
                            'open_time' => $times[0],
                            'closed_time' => $times[1]
                        ]);
                    }
                }

                $row++;
            }
        }
    }
}
