<?php

namespace App\Http\Controllers;

use App\Demo\CustomerService;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    /** @var CustomerService */
    private $customer_service;

    /**
     * DemoController constructor.
     * @param CustomerService $customer_service
     */
    public function __construct(CustomerService $customer_service)
    {
        $this->customer_service = $customer_service;
    }

    /**
     *
     */
    public function generateCustomers() {
        for ($i = 0; $i < 100; $i++) {
            $this->customer_service->generateCustomer();
        }
        return redirect(action('CustomersController@index'))->with([
            'success' => 'Demo megrendelők sikeresen legenerálva',
        ]);
    }
}
