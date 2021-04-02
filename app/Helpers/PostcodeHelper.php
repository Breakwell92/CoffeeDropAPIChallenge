<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class PostcodeHelper
{
    /**
     * Returns latitude and longitude of the {$postcode} or false if bad response.
     *
     * @param string $postcode
     * @return array|bool
     */
    public static function getLatitudeLongitude(string $postcode)
    {
        $request = Http::get(config('api-endpoints.postcode').$postcode);

        if($request->successful()){
            $result = json_decode($request->body())->result;
            
            return [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude                
            ];

        }else{
            return false;
        }
    }

    /**
     * Return whether the {$postcode} is valid.
     *
     * @param $postcode
     * @return bool
     */
    public static function validatePostcode(string $postcode)
    {
        $request = Http::get(config('api-endpoints.postcode').$postcode.'/validate');

        if($request->successful()){
            $result = json_decode($request->body())->result;

            return $result;
        }
    }
}
    