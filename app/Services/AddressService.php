<?php
/**
 * Created by PhpStorm.
 * User: subesz
 * Date: 2020. 01. 26.
 * Time: 23:11
 */

namespace App\Services;


use App\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddressService
{
    /**
     * Visszaadja a különböző városokat megszámlálva
     * @return mixed
     */
    public function getUniqueCities() {
        $address_query = Address::select('city as name', DB::raw('count(city) quantity'))->groupBy('city')->get();
        foreach ($address_query as &$city) {
            $city['slug'] = Str::slug($city['name']);
        }

        return $address_query;
    }
}