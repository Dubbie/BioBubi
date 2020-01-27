<?php

namespace App\Services;

use App\Customer;
use Illuminate\Support\Arr;

class CustomersService {
    public function __construct()
    {
    }

    /**
     * @param array $filter
     * @param bool $json
     * @return string
     */
    public function get($filter = [], $json = false) {
        $customers_query = Customer::query();

        if ($json) {
            $customers_query = Customer::with('address');
        }

        // Név szűrés
        if (Arr::has($filter, 'name')) {
            $customers_query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        // Város szűrése
        if (Arr::has($filter, 'city')) {
            $customers_query->whereHas('address', function ($query) use ($filter) {
                $query->whereIn('city', $filter['city']);
            });
        }

        // Query lefuttatása
        return $customers_query->orderBy('name', 'asc')->paginate(50);
    }

    /**
     * Kitörli a megadott ID alapján a megrendelőt
     *
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        $customer = Customer::find($id);

        if ($customer) {
            // Kitöröljük a lakcímét
            $customer->address()->each(function ($address) {
                $address->delete();
            });

            // Kitöröljük a rögzített termékeit
            $customer->purchases()->each(function ($purchase) {
                $purchase->delete();
            });

            // Megjegyzések törlése
            $customer->comments()->each(function ($comment) {
                // Teendők törlése
                $comment->alerts()->each(function ($alert) {
                    $alert->delete();
                });

                $comment->delete();
            });

            // Kitöröljük a felhasználót
            return $customer->delete();
        }

        return false;
    }
}