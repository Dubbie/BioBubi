<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    /** @var AddressService  */
    private $address_service;

    public function __construct(AddressService $address_service)
    {
        $this->address_service = $address_service;
    }

    /**
     * Visszaadja a különböző városokat
     *
     * @return mixed
     */
    public function getUniques() {
        return $this->address_service->getUniqueCities(true);
    }
}
