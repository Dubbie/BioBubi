<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemsController extends Controller
{
    /**
     * Betölti a meglévő termékeket
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $items = Item::all();

        return view('items.index')->with([
            'items' => $items,
        ]);
    }

    /**
     * Mutatja az új megrendelő létrehozására szolgáló panelt
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Elmenti az új terméket
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required|unique:items',
            'price' => 'required',
        ]);

        $item = new Item();
        $item->name = Str::title($validated_data['name']);
        $item->price = intval($validated_data['price']);
        $item->save();

        // Sikeres mentés esetén átirányítjuk az új ügyfél oldalára
        return redirect(action('ItemsController@index'))->with([
            'success' => 'Új termék sikeresen létrehozva!',
        ]);
    }

    /**
     * Frissíti a meglévő terméket
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $validated_data = $request->validate([
            'edit_item_id' => 'required',
            'edit_name' => 'required|unique:items,name,' . $request->input('edit_item_id'),
            'edit_price' => 'required',
        ]);

        $item = Item::find($validated_data['edit_item_id']);

        $item->name = Str::title($validated_data['edit_name']);
        $item->price = intval($validated_data['edit_price']);

        $item->save();

        // Sikeres mentés esetén átirányítjuk a termékek felületre
        return redirect(action('ItemsController@index'))->with([
            'success' => 'Termék sikeresen frissítve!',
        ]);
    }

    /**
     * Megmutatja az adott megrendelő részletes adatait
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $item = Customer::find($id);

        return view('items.show')->with([
            'item' => $item,
        ]);
    }

    /**
     * Kitörli a megadott ID alapján a terméket
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        Item::destroy($id);

        return redirect(action('ItemsController@index'))->with([
            'success' => 'Termék sikeresen törölve az adatbázisból!'
        ]);
    }
}
