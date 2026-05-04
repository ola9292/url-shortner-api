<?php

namespace App\Services;

use App\Models\Url;
use Stevebauman\Location\Facades\Location;
use Vinkla\Hashids\Facades\Hashids;

class UrlTrimService
{
    public function shorten_url($url)
    {
        $url = Url::create([
            'original_url' => $url,
        ]);
        $hashedText = Hashids::encode($url->id);
        // $shortened_url = url('/').'/'.$hashedText;

        $url->update([
            'hash' => $hashedText,
        ]);

        return $url;
    }

    public function redirectUrl($hash, $ip_address, $user_agent)
    {
        $decoded = Hashids::decode($hash);
        if (empty($decoded)) {
            return null;
        }

        $id = $decoded[0];
        $url = Url::findOrFail($id);

        if (app()->environment('local')) {
            $ip_address = '212.58.244.70';
        }

        dispatch(function () use ($url, $ip_address, $user_agent) {
            $location = Location::get($ip_address);
            $country = $location ? $location->countryName : null;

            $url->clicks()->create([
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'country' => $country,
            ]);
        });

        return $url;
    }
}
