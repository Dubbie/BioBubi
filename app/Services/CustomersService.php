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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get($filter = []) {
        $customers_query = Customer::query();

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
}