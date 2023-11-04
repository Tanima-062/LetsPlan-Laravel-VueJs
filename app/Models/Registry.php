<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_admid_id',
        'company_id',
        'registration_phase_start_date',
        'registration_phase_end_date',
        'playground_period_start_date',
        'playground_period_end_date',
        'parents_inform_date',
        'open_day_date',
        'open_day_time_from',
        'open_day_time_to',
        'content'
    ];
    protected $dates = [
        'registration_phase_start_date',
        'registration_phase_end_date',
        'playground_period_start_date',
        'playground_period_end_date',
        'parents_inform_date',
        'open_day_date'
    ];
}
