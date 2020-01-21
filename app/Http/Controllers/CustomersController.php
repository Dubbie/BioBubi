<?php

namespace App\Http\Controllers;

use App\Address;
use App\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Betölti a meglévő megrendelőket
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $customers = Customer::all();

        return view('customers.index')->with([
            'customers' => $customers,
        ]);
    }

    /**
     * Mutatja az új megrendelő létrehozására szolgáló panelt
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('customers.create');
    }

    /**
     * Elmenti az új megrendelőt
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $validated_data = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'zip' => 'required',
            'city' => 'required',
            'street' => 'required',
            'email' => 'required|unique:customers|email',
            'is_reseller' => 'required',
        ]);

        // 1. Lakcím elmentése
        $address = new Address();
        $address->zip = $validated_data['zip'];
        $address->city = $validated_data['city'];
        $address->street = $validated_data['street'];
        $address->save();

        // 2. Megrendelő elmentése
        // -- Eldöntjük, hogy a megrendelő viszonteladó, vagy ügyfél
        $is_reseller = $validated_data['is_reseller'] == 'true' ? true : false;
        // -- Semlegesítjük a telefonszámot, csak számokból álljon
        $phone = preg_replace("/[^0-9]/", "", $validated_data['phone'] );
        // -- Elkezdjük a mentést
        $customer = new Customer();
        $customer->name = $validated_data['name'];
        $customer->phone = $phone;
        $customer->email = $validated_data['email'];
        $customer->address_id = $address->id;
        $customer->is_reseller = $is_reseller;
        $customer->save();

        // Sikeres mentés esetén átirányítjuk az új ügyfél oldalára
        return redirect(action('CustomersController@show', $customer))->with([
            'success' => 'Új megrendelő sikeresen létrehozva!',
        ]);
    }

    /**
     * Megmutatja az adott megrendelő részletes adatait
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id) {
        $customer = Customer::find($id);

        return view('customers.show')->with([
            'customer' => $customer,
        ]);
    }
}
