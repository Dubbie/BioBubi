<?php
/**
 * User: subesz
 * Date: 2020. 02. 03.
 * Time: 23:28
 */

namespace App\Demo;


use App\Address;
use App\Customer;
use App\Services\PhoneService;
use Faker\Factory;

class CustomerService
{
    /** @var PhoneService */
    private $phone_service;

    /**
     * CustomerService constructor.
     * @param PhoneService $phoneService
     */
    public function __construct(PhoneService $phoneService)
    {
        $this->phone_service = $phoneService;
    }

    /**
     * @return bool
     */
    public function generateCustomer() {
        $faker = Factory::create('hu_HU');

        // 1. Lakcím
        $address = new Address();
        $address->zip = $faker->postcode;
        $address->city = $faker->city;
        $address->street = $faker->streetAddress;
        $address->save();

        // 2. Megrendelő
        $customer = new Customer();
        $customer->is_reseller = mt_rand(0, 1);
        $customer->name = $faker->name;
        $customer->email = $faker->email;
        $customer->phone = $this->phone_service->cleanNumber($faker->phoneNumber);
        $customer->address_id = $address->id;

        return $customer->save();
    }
}