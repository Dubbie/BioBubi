<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * Visszaadja a rögzítéseket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases() {
        return $this->hasMany('App\CustomerItems', 'item_id');
    }

    /**
     * Visszaadja a formázott értékét az árnak
     *
     * @param bool $with_currency
     * @return string
     */
    public function getFormattedPrice($with_currency = false) {
        $output = number_format($this->price, 0, '.', ' ');

        if ($with_currency) {
            $output .= ' Ft';
        }

        return $output;
    }
}
