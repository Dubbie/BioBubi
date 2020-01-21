<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * Visszaadja a formázott lakcímet
     *
     * @return string
     */
    public function getFormattedAddress() {
        return sprintf('%s %s, %s', $this->zip, $this->city, $this->street);
    }

    public function customer() {
        return $this->belongsTo('App\User');
    }
}
