<?php

namespace App\Http\Controllers;

use App\Address;
use App\Alert;
use App\Customer;
use App\Item;
use App\Services\AlertsService;
use App\Services\CustomersService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomersController extends Controller
{
    /** @var AlertsService  */
    private $alerts_service;

    /** @var CustomersService  */
    private $customers_service;

    /**
     * CustomersController constructor.
     * @param CustomersService $customers_service
     * @param AlertsService $alerts_service
     */
    public function __construct(CustomersService $customers_service, AlertsService $alerts_service)
    {
        $this->customers_service = $customers_service;
        $this->alerts_service = $alerts_service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $filter = [];

        // Név szűréshez
        if ($request->has('filter-name') && $request->input('filter-name')) {
            $filter['name'] = $request->input('filter-name');
        }

        // Város szűréshez
        $cities = Address::select('city', DB::raw('count(*) as total'))->groupBy('city')->get()->toArray();
        if ($request->has('filter-city')) {
            $filter['city'] = $request->input('filter-city');
        }
        if(isset($filter['city'])) {
            foreach ($cities as &$city) {
                if (in_array($city['city'], $filter['city'])) {
                    $city['checked'] = true;
                }
            }
        }

        // Esedékes teendők
        $alerts = $this->alerts_service->getDueAlerts();

        // Query lefuttatása
        $customers = $this->customers_service->get($filter);

        return view('customers.index')->with([
            'customers' => $customers,
            'cities' => $cities,
            'filter' => $filter,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Mutatja az új megrendelő létrehozására szolgáló panelt.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('customers.create');
    }

    /**
     * Megrendelő szerkesztő felület.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id) {
        $customer = Customer::find($id);

        return view('customers.edit')->with([
            'customer' => $customer,
        ]);
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
            'phone' => 'required|max:15|unique:customers,phone',
            'zip' => 'required',
            'city' => 'required',
            'street' => 'required',
            'email' => 'required|unique:customers,email|email',
            'is_reseller' => 'required',
        ]);

        // 1. Lakcím elmentése
        $address = new Address();
        $address->zip = $validated_data['zip'];
        $address->city = Str::title($validated_data['city']);
        $address->street = Str::title($validated_data['street']);
        $address->save();

        // 2. Megrendelő elmentése
        // -- Eldöntjük, hogy a megrendelő viszonteladó, vagy ügyfél
        $is_reseller = $validated_data['is_reseller'] == 'true' ? true : false;
        // -- Semlegesítjük a telefonszámot, csak számokból álljon
        $phone = preg_replace("/[^0-9]/", "", $validated_data['phone'] );
        // -- Elkezdjük a mentést
        $customer = new Customer();
        $customer->name = Str::title($validated_data['name']);
        $customer->phone = $phone;
        $customer->email = strtolower($validated_data['email']);
        $customer->address_id = $address->id;
        $customer->is_reseller = $is_reseller;
        $customer->save();

        // Sikeres mentés esetén átirányítjuk az új ügyfél oldalára
        return redirect(action('CustomersController@show', $customer))->with([
            'success' => 'Új megrendelő sikeresen létrehozva!',
        ]);
    }

    /**
     * Frissít egy meglévő felhaszálót
     *
     * @param $customer_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($customer_id, Request $request) {
        $customer = Customer::find($customer_id);

        $validated_data = $request->validate([
            'name' => 'required',
            'phone' => 'required|max:15|unique:customers,phone,' . $customer_id,
            'zip' => 'required',
            'city' => 'required',
            'street' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer_id,
            'is_reseller' => 'required',
        ]);

        // 1. Lakcím felülírása
        $customer->address->zip = $validated_data['zip'];
        $customer->address->city = Str::title($validated_data['city']);
        $customer->address->street = Str::title($validated_data['street']);
        $customer->address->save();

        // 2. Megrendelő elmentése
        // -- Eldöntjük, hogy a megrendelő viszonteladó, vagy ügyfél
        $is_reseller = $validated_data['is_reseller'] == 'true' ? true : false;
        // -- Semlegesítjük a telefonszámot, csak számokból álljon
        $phone = preg_replace("/[^0-9]/", "", $validated_data['phone'] );
        // -- Elkezdjük a mentést
        $customer->name = Str::title($validated_data['name']);
        $customer->phone = $phone;
        $customer->email = strtolower($validated_data['email']);
        $customer->is_reseller = $is_reseller;
        $customer->save();

        // Sikeres mentés esetén átirányítjuk az új ügyfél oldalára
        return redirect(action('CustomersController@show', $customer))->with([
            'success' => 'Új megrendelő sikeresen módosítva!',
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
        $items = Item::all();
        $total = 0;

        foreach ($customer->purchases as $purchase) {
            $total += $purchase->price * $purchase->quantity;
        }

        return view('customers.show')->with([
            'customer' => $customer,
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Kitörli a megrendelőt a hozzá tartozó lakcímmel és vásárolt termékekkel együtt.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id) {
        // Megkeressük a felhasználót
        $customer = Customer::find($id);

        // Kitöröljük a lakcímét
        $customer->address()->each(function ($address) {
           $address->delete();
        });

        // Kitöröljük a rögzített termékeit
        $customer->purchases()->each(function ($purchase) {
            $purchase->delete();
        });

        // Kitöröljük a felhasználót
         $customer->delete();

        return redirect(action('CustomersController@index'))->with([
            'success' => 'Megrendelő sikeresen törölve'
        ]);
    }
}
