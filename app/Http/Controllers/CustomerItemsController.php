<?php

namespace App\Http\Controllers;

use App\CustomerItems;
use App\Item;
use Illuminate\Http\Request;

class CustomerItemsController extends Controller
{
    /**
     * Rögzíti a megadottak alapján a termékeket
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $validated_data = $request->validate([
            'customer_id' => 'required',
            'item_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'date' => 'nullable',
            'completed' => 'nullable',
        ]);

        // Végigmegyünk a megadott termékeken
        for ($i = 0; $i < count($validated_data['item_id']); $i++) {
            $actual_item = Item::find(intval($validated_data['item_id'][$i]));

            $customer_item = new CustomerItems();
            $customer_item->item_id = intval($validated_data['item_id'][$i]);
            $customer_item->item_name = $actual_item->name;
            $customer_item->price = intval($validated_data['price'][$i]);
            $customer_item->quantity = intval($validated_data['quantity'][$i]);
            $customer_item->date = $validated_data['date'][$i];
            $customer_item->completed = $validated_data['completed'][$i] == 'on' ? true : false;
            $customer_item->customer_id = intval($validated_data['customer_id']);
            $customer_item->save();
        }

        return redirect(action('CustomersController@show', ['id' => $validated_data['customer_id']]))->with([
            'success' => 'Vásárolt termékek sikeresen rögzítve!'
        ]);
    }

    /**
     * A rögzített terméket frissíti teljesültté
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function complete(Request $request) {
        $purchase = CustomerItems::find($request->input('complete_purchase_id'));
        $purchase->completed = true;
        $purchase->date = $request->input('complete_purchase_date');
        $purchase->save();

        return redirect(url()->previous())->with([
            'success' => 'Vásárolt termékek sikeresen teljesítve!'
        ]);
    }

    /**
     * Segéd funckió ami betölti az új rögzítéshez szükséges inputokat.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadNew(Request $request) {
        $items = Item::all();
        return view('inc.customer_item')->with([
            'items' => $items,
            'count' => $request->input('count'),
        ]);
    }

    /**
     * Kitörli a megadott ID alapján a hozzá tartozó rögzített vásárlást.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id) {
        CustomerItems::destroy($id);

        return redirect(url()->previous())->with([
            'success' => 'Rögzített vásárlás sikeresen törölve!',
        ]);
    }
}
