<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerItems;
use App\Item;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Számlálók
        $counters = [
            'customers' => Customer::all()->count(),
            'sold_items' => CustomerItems::sum('quantity'),
            'sold_total' => $this->number_format_short(CustomerItems::all()->sum(function ($sale) {
                return $sale->price * $sale->quantity;
            }), 0),
            'items' => Item::all()->count(),
        ];

        return view('statistics.index')->with([
            'counters' => $counters,
        ]);
    }

    /**
     *  Számok lerövidítése
     */
    private function number_format_short($n, $precision = 1)
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision, ',', ' ');
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision, ',', ' ');
            $suffix = 'e';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision, ',', ' ');
            $suffix = 'm';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision, ',', ' ');
            $suffix = 'b';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision, ',', ' ');
            $suffix = 't';
        }
        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }
        return $n_format . $suffix;
    }
}
