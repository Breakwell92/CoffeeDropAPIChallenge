<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class PostcodeHelper
{

    /**
     * Postcode.io endpoint
     * 
     * @var string
     */
    protected static $postcode_api_endpoint = 'POSTCODES_API_ENDPOINT';

    /**
     * Returns latitude and longitude of the {$postcode} or false if bad response.
     *
     * @param string $postcode
     * @return array|bool
     */
    public static function getLatitudeLongitude(string $postcode)
    {
        $request = Http::get(env(self::$postcode_api_endpoint).$postcode);

        if($request->successful()){
            $result = json_decode($request->body())->result;
            
            return [
                'latitude' => $result->latitude,
                'longitude' => $result->longitude                
            ];

        }else{
            abort($request->status());
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
        $request = Http::get(env(self::$postcode_api_endpoint).$postcode.'/validate');

        if($request->successful()){
            $result = json_decode($request->body())->result;

            return $result;
        }else{
            //abort($request->status());
        }
    }
}
    