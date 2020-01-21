<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerItems extends Model
{
    /**
     * Visszaadja a vásárolt terméket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item() {
        return $this->belongsTo('App\Item', 'item_id');
    }
}
