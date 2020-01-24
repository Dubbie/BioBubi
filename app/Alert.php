<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Alert extends Model
{
    /**
     * Visszaadja a hozzátartozó felhasználót
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Visszaadja a hozzátartozó kommentet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment() {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    /**
     * Átalakítja a dátumot Carbon dátummá
     *
     * @param $value
     * @return Carbon
     */
    public function getTimeAttribute($value) {
        return $value != null ? Carbon::parse($value) : null;
    }

    /**
     * Visszaadja a hátramaradt időt
     * @return string
     */
    public function getRemainingTime() {
        return $this->time->shortRelativeToNowDiffForHumans();
    }

    /**
     * Visszaadja, hogy határidőn túl ment-e a teendő
     *
     * @return bool
     */
    public function isOverdue() {
        return !$this->completed && $this->time->diffInHours(Carbon::now(), false) > 0;
    }

    /**
     * Visszaadja a státusz badge-t
     *
     * @return string
     */
    public function getStatusBadge() {
        return view('inc.status_badge')->with('alert', $this)->toHtml();
    }
}
