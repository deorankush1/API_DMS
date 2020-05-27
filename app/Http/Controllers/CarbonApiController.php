<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FootPrintService;
use App\Http\Requests\FootPrintRequest;
use App\Http\Resources\CarbonApiResource;
use Illuminate\Support\Facades\Redis;

class CarbonApiController extends Controller
{
    protected $footPrintService;

    public function __construct(FootPrintService $footPrintService)
    {
        $this->footPrintService = $footPrintService;
    }

    public function getFootPrintData(FootPrintRequest $request)
    {
        try {
            $country_code = strtoupper($request->country_code); //convert into uppercase the field
            
            $country_data = config('carbon.country');

            if (!array_key_exists($country_code, $country_data)) {  //validate the country_code is exist or not
                return failed('Please enter a valid country code in ountry section', 417);
            }

            $country = $this->getCountryCode($country_code);

            $appTKN=config('carbon.apitoken');//retrive api token for the 100 request per minutes
            
            $mode = $fuelType = null;
            
            if ($request->has('mode')) {
                $mode = $request->mode;
            }

            if ($request->has('fuelType')) {
                $fuelType = $request->fuelType;
            }
                        
            $request_body = [
                'activity'=> $request->activity,
                'activityType' =>$request->activityType,
                'appTKN' =>$appTKN,
                'country' =>$country,
                'mode' =>$mode,
                'fuelType'=>$fuelType
            ];

            $url = config('carbon.url');

            $footPrint = getHttpHeader($url, [], 'laravel.log', $request_body);

            $carbon_footPrint = json_decode($footPrint, true);

            $request_body['footprint'] = $carbon_footPrint['carbonFootprint'];

            $insetData = $this->footPrintService->store($request_body);

            $id = $insetData->id;
            $ttl = 60*60*24;
            $storeInRedis = config_set($id, $insetData);
            $setExpiryInRedis = config_set_expiry($id, $ttl);

            //dd(Redis::ttl(35)); //for fetching the redis expiry time
            //dd(Redis::keys("*")); // for fetch the whole data in redis
            //dd(Redis::get(35)); // for fetch the particular record from redis

            $data =  new  CarbonApiResource($insetData);
            return success('data saved successfully', 200, $data);
        } catch (\Exception $e) {
            \Log::error('Carbon api fetch error'.$e);// we can define the log chaneels
            return failed('Something went wrong', 500, $e->getMessage());
        }
    }

    protected function getCountryCode($country_code)
    {
        if ($country_code != 'USA' && $country_code != 'GBR') { //intialize a country variable according to the country code condition
            return  'def';
        } else {
            return strtolower($country_code);
        }
    }
}
