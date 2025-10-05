<?php
namespace App\Services;

class GeoLocationService {
    public static function getLocationFromIp($ip) {
        $url = "http://ipinfo.io/{$ip}/json";
        $response = HttpService::get($url);
        if(!empty($response['error'])) {
            return null;
        } else {
            if(empty($response['data']['loc'])) {
                return null;
            }
            
            $coordinates = explode(',', $response['data']['loc']);
            return [
                'latitude' => $coordinates[0],
                'longitude' => $coordinates[1],
            ];
        }
    }
}

?>