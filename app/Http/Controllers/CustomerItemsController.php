<?php

namespace App\Http\Controllers;

use App\CustomerItems;
use App\Item;
use Illuminate\Http\Request;

class CustomerItemsController extends Controller
{
    public function store(Request $request) {
        $validated_data = $request->validate([
            'customer_id' => 'required',
            'item_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        // Végigmegyünk a megadott termékeken
        for ($i = 0; $i < count($validated_data['item_id']); $i++) {
            $customer_item = new CustomerItems();
            $customer_item->item_id = intval($validated_data['item_id'][$i]);
            $customer_item->price = intval($validated_data['price'][$i]);
            $customer_item->quantity = intval($validated_data['quantity'][$i]);
            $customer_item->customer_id = intval($validated_data['customer_id']);
            $customer_item->save();
        }

        return redirect(action('CustomersController@show', ['id' => $validated_data['customer_id']]))->with([
            'success' => 'Vásárolt termékek sikeresen rögzítve'
        ]);
    }

    public function loadNew(Request $request) {
        $items = Item::all();
        return view('inc.customer_item')->with([
            'items' => $items,
            'count' => $request->input('count'),
        ]);
    }
}
