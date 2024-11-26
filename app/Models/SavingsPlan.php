<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class SavingsPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($plan) {
            // Benachrichtigungen löschen, die sich auf diesen Sparplan beziehen
            Notification::where('user_id', $plan->user_id)
                ->where('message', 'like', 'Ihr Sparplan "' . $plan->name . '"%')
                ->delete();
        });
    }
}
