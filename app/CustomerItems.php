<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CustomerItems extends Model
{
    /**
     * Visszaadja a rögzített tárgy helyes nevét
     *
     * @return string
     */
    public function getItemName() {
        if ($this->item) {
            return $this->item->name;
        } else {
            return $this->item_name . ' (Törölve)';
        }
    }

    /**
     * Átalakítja a dátumot Carbon dátummá
     *
     * @param $value
     * @return Carbon
     */
    public function getDateAttribute($value) {
        return $value != null ? Carbon::parse($value) : null;
    }

    /**
     * Visszaadja a vásárolt terméket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item() {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
