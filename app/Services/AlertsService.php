<?php

namespace App\Services;

use App\Alert;
use Illuminate\Support\Carbon;

class AlertsService {
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getAlerts() {
        return Alert::all();
    }

    /**
     * @return mixed
     */
    public function getIncompleteAlerts() {
        return Alert::where('completed', '=', 0)->get();
    }

    /**
     * @return array
     */
    public function getDueAlerts() {
        $alerts = [];
        $now = Carbon::now();
        foreach ($this->getIncompleteAlerts() as &$alert) {
            if ($now->diffInHours($alert->time) <= 24) {

                $alert->customer = $alert->comment->customer;
                $alerts[] = $alert;
            }
        }

        return $alerts;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getAlertsByUserId($user_id) {
        return Alert::where('user_id', '=', $user_id)->get();
    }
}