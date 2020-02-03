<?php
/**
 * User: subesz
 * Date: 2020. 02. 03.
 * Time: 23:47
 */

namespace App\Services;


class PhoneService
{
    /**
     * @param $phone_number
     * @return null|string|string[]
     */
    public function cleanNumber($phone_number) {
        return preg_replace("/[^0-9]/", "", $phone_number);
    }
}