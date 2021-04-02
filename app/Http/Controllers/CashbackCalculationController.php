<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashbackCalculationRequest;
use App\Http\Resources\CashbackCalculationRequestCollection;

class CashbackCalculationController extends Controller
{

    /**
     * Tiers of cashback amounts based on coffee type and quantity range
     * 
     * @var array
     */
    protected $cashback_tiers = [
        [
            "min" => 0,
            "max" => 50,
            "cashback_value" => [
                "Ristretto" => 2,
                "Espresso" => 3,
                "Lungo" => 5
            ]
        ],
        [
            "min" => 50,
            "max" => 500,
            "cashback_value" => [
                "Ristretto" => 4,
                "Espresso" => 6,
                "Lungo" => 10
            ]
        ],
        [
            "min" => 500,
            'max' => null,
            "cashback_value" => [
                "Ristretto" => 6,
                "Espresso" => 9,
                "Lungo" => 15
            ]
        ],
    ];


    /**
     * Calculate the amount of cashback the user would earn based on their input
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateCashback(Request $request) : \Illuminate\Http\JsonResponse
    {
        // Get user's quantities from the request
        $user_quantities = $request->all();

        // Establish cashback value for calculations
        $cashback_value = 0;

        // Loop through the users' quantities
        foreach($user_quantities as $coffee=>$quantity){
            // Then loop through each tier to calculate cashback amount
            foreach($this->cashback_tiers as $tier){
                // Check if the quanity exceeds both the min and max value for the tier, and that there is a max value for the tier
                if($quantity > $tier['min'] && $quantity > $tier['max'] && $tier['max'] != null ){  
                    // If so, calculate based on the difference between the min and max values
                    $cashback_value += ( $tier['max'] - $tier['min'] ) * $tier['cashback_value'][$coffee]; 
                // Otherwise, check if the quantity exceeds the min value for the tier
                }else if ($quantity > $tier['min']){
                    // If so, calculate based on the quantity minus the min value, as either this is the first tier, or the min quantity has already been calculated for on the previous tier
                    $cashback_value += ($quantity - $tier['min']) * $tier['cashback_value'][$coffee];
                }
            }
        }

        // TODO: Add validation and error handling
        self::storeRequest($request, $cashback_value);

        return response()->json($cashback_value/100);

    }

    /**
     * Store a cashback calculation request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function storeRequest(Request $request, int $cashback_value)
    {

        $calculation_request = CashbackCalculationRequest::create([
            'remote_addr' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
            'input' => json_encode($request->all()),
            'cashback_value' => $cashback_value
        ]);

        return true;

    }

    /**
     * Return cashback calculation requests data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\CashbackCalculationRequestCollection
     */
    public function getRequests(Request $request) : CashbackCalculationRequestCollection
    {
        
        $requests = CashbackCalculationRequest::orderBy('created_at', 'desc')->limit(5)->get();

        return new CashbackCalculationRequestCollection($requests);

    }
}
