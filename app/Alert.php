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

    public function getRemainingLabel() {
        $now = Carbon::now();

        if(!$this->completed) {
            if ($this->time->diffInHours($now, false) < 0) {
                $label = "Határidő: " . $this->time->longAbsoluteDiffForHumans($now);
            } else {
                $label = "Határidőn túl";
            }
        } else {
            $label = "Teljesítve";
        }

        return $label;
    }

    public function isOverdue() {
        return !$this->completed && $this->time->diffInHours(Carbon::now(), false) > 0;
    }
}
