<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildrenRegistrationOpeningHour extends Model
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

    const RED = '#FD8181';
    const YELLOW = '#F8E7A8';
    const GREEN = '#9CDAE5';
    const ORANGE = '#F5B88D';
    const TURQUOISE = '#30D5C8';
    const WHITE = "white";

    protected $fillable = ['children_registration_id', 'day_id', 'hour_id', 'color', 'created_from','order','spielgruppen_id'];

    public function childrenRegistration()
    {
        return $this->belongsTo(ChildrenRegistration::class);
    }
    public function spielgruppen()
    {
        return $this->belongsTo(Spielgruppen::class);
    }

}
