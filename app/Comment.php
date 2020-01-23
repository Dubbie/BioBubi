<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    /**
     * Visszaadja a kommenthez tartozó felhasználót
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Visszaadja a kommenthez tartozó megrendelőt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer() {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    /**
     * Visszaadja a kommenthez tartpzó teendőket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alerts() {
        return $this->hasMany(Alert::class, 'comment_id')->where('user_id', '=', Auth::id());
    }
}
