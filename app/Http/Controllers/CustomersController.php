<?php

namespace App\Http\Controllers;

use App\Address;
use App\Customer;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    /**
     * Betölti a meglévő megrendelőket
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $customers_query = Customer::query();
        $filter = [];

        // Név szűrés
        if ($request->has('filter-name')) {
            if (strlen($request->input('filter-name')) == 0) {
                $request->request->remove('filter-name');
            } else {
                $filter['name'] = $request->input('filter-name');
                $customers_query->where('name', 'like', sprintf('%%%s%%', $filter['name']));
            }
        }
        if ($request->has('filter-city')) {
            $filter['city'] = $request->input('filter-city');

            $customers_query->whereHas('address', function ($query) use ($filter) {
                $query->whereIn('city', $filter['city']);
            });
        }

        // Városok szűréshez
        $cities = Address::select('city', DB::raw('count(*) as total'))->groupBy('city')->get()->toArray();

        if(isset($filter['city'])) {
            foreach ($cities as &$city) {
                if (in_array($city['city'], $filter['city'])) {
                    $city['checked'] = true;
                }
            }
        }

        $customers = $customers_query->orderBy('name', 'asc')->paginate(50);
        return view('customers.index')->with([
            'customers' => $customers,
            'cities' => $cities,
            'filter' => $filter,
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
