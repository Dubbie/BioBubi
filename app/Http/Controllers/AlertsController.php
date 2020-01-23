<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertsController extends Controller
{
    /**
     * Teendő elmentése
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
        $data = $request->validate([
            'new_alert_comment_id' => 'required',
            'new_alert_message' => 'required',
            'new_alert_time' => 'required|date',
        ]);

        $alert = new Alert();
        $alert->comment_id = $data['new_alert_comment_id'];
        $alert->message = $data['new_alert_message'];
        $alert->time = $data['new_alert_time'];
        $alert->user_id = Auth::id();
        $alert->save();

        return redirect(url()->previous())->with([
            'success' => 'Új teendő sikeresen hozzáadva.',
        ]);
    }

    /**
     * Teendő frissítése
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request) {
        $data = $request->validate([
            'edit_alert_id' => 'required',
            'edit_alert_message' => 'required',
            'edit_alert_time' => 'required|date',
        ]);

        $alert = Alert::find($data['edit_alert_id']);
        $alert->message = $data['edit_alert_message'];
        $alert->time = $data['edit_alert_time'];
        $alert->save();

        return redirect(url()->previous())->with([
            'success' => 'Teendő sikeresen frissítve.',
        ]);
    }

    /**
     * Teendő teljesítése
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function complete($id) {
        $alert = Alert::find($id);
        $alert->completed = true;
        $alert->save();

        return redirect(url()->previous())->with([
            'success' => 'Teendő sikeresen teljesítve.',
        ]);
    }

    /**
     * Teendő törlése
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id) {
        Alert::destroy($id);

        return redirect(url()->previous())->with([
            'success' => 'Teendő sikeresen törölve.',
        ]);
    }
}
