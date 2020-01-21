<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function getResellerLabel() {
        return $this->is_reseller == true ? 'Viszonteladó' : 'Ügyfél';
    }

    /**
     * Visszaadja a megrendelőhöz tartozó lakcímet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address() {
        return $this->hasOne('App\Address', 'id', 'address_id');
    }

    /**
     * Visszaadja a megrendelőhöz tartozó kommenteket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany('App\Comment', 'customer_id');
    }
}
