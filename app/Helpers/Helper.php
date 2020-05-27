<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Method: POST
 * This function is used to curl Raw json data
 *
 */

if (!function_exists('getHttpHeader')) {
    function getHttpHeader($url, $data = array(), $log_module_name, $headers)
    {
        $response = "";
        try {
            $client = new Client();
            
            $res = $client->request('GET', $url, [
                'query' => $headers
            ]);
            if ($res->getStatusCode() == 200) {
                $response = $res->getBody()->getContents();
                Log::channel($log_module_name)->info("response : " . $response);
                return $response;
            } else {
                $response = $res->getBody()->getContents();
                Log::channel($log_module_name)->error("response: " . $res->getBody()->getContents());
                return $response;
            }
        } catch (BadResponseException $e) {
            Log::channel($log_module_name)->error("Exception : ".$e);
            throw new \Exception("BadRequestException - " . $e);
            return $response;
        }
    }
}

if (!function_exists('success')) {
    function success($message, $status_code, $data = array())
    {
        if (empty($data)) {
            $data = json_encode(json_decode("{}"));
        }
        return response()->json(['status' => 'Success' ,'message' => $message, 'data' => $data], $status_code);
    }
}

if (!function_exists('failed')) {
    function failed($message, $status_code, $data = array())
    {
        if (empty($data)) {
            $data = json_encode(json_decode("{}"));
        }

        return response()->json(['status' => 'Failed', 'message' => $message, 'data' => $data], $status_code);
    }
}

if (!function_exists('config_set')) {
    function config_set($key, $value)
    {
        if (empty($key)) {
            return false;
        }
        return Redis::set($key, json_encode($value));
    }
}

if (!function_exists('config_get')) {
    function config_get($key)
    {
        if (empty($key)) {
            return false;
        }
        return json_decode(Redis::get($key), true);
    }
}

if (!function_exists('config_set_expiry')) {
    function config_set_expiry($id, $ttl)
    {
        if (empty($id)) {
            return false;
        }
        return Redis::EXPIRE($id, $ttl);
    }
}
