<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    use HasFactory;
    // （ここから）追記
    // Controllerのfill用
    protected $fillable = [
        'event_title',
        'event_body',
        'start_date',
        'end_date',
        'event_color',
        'event_border_color',
        'total_amount',
        'per_person',
        'upper'
    ];
// （ここまで）
}


