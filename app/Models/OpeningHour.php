<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpeningHour extends Model
{
    use HasFactory, SoftDeletes;

    // 1 => MONDAY
    // 2 => TUESDAY
    // 3 => WEDNESDAY
    // 4 => THURSDAY
    // 5 => FRIDAY
    const DAYS = [1, 2, 3, 4, 5];
    //1 => Morning
    //2 => Afternoon
    const HOURS = [1, 2];

    public $with = ['lead', 'assistant'];

    protected $fillable = ['spielgruppen_id', 'day_id', 'hour_id', 'lead_id', 'assistant_id'];

    public function spielgruppen()
    {
        return $this->belongsTo(Spielgruppen::class);
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function childrenRegistrations()
    {
        return $this->hasMany(ChildrenRegistration::class, 'spielgruppen_id', 'spielgruppen_id');
    }
}
