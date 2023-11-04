<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Division extends Model
{
    use HasFactory;

    const PREFIX = "ET";

    const NEW = 'Neu';
    const ACTIVE_PROFISIONAL = 'Aktiv-Provisorisch';
    const READY_FOR_DISCUSSION = 'Aktiv-Bereit zur Besprechung';
    const COMPLETED = 'Abgeschlossen';

    protected $dates = [
        'registration_start_date',
        'registration_end_date',
        'playgroup_start_date',
        'playgroup_end_date',
        'date_parent',
    ];

    protected $fillable = [
        'name',
        'uniq_id',
        'registration_start_date',
        'registration_end_date',
        'playgroup_start_date',
        'playgroup_end_date',
        'date_parent',
        'open_door_date',
        'open_door_start_time',
        'open_door_end_time',
        'place',
        'status_id',
        'company_id',
        'prefix_id'
    ];


    /**
     * Register any events for your application.
     *
     */

    protected static function booted()
    {
        static::creating(function($division) {
            $division->prefix_id = self::getNextPrefixId($division->company_id);
        });
    }

    /**
     * Upcoming Division
     *
     */

    public function scopeUpComingDivision($q)
    {
        $now = Carbon::now()->toDateString();
        return $q->where('status_id', 1)->whereDate('registration_start_date', '>', $now)->orderBy('registration_start_date', 'ASC');
    }

    /**
     * Get status name accessor
     *
     * @return void
     */
    public function getStatusNameAttribute()
    {
        if ($this->status_id == 1) {
            return self::NEW;
        } else if ($this->status_id == 2) {
            return self::ACTIVE_PROFISIONAL;
        } else if ($this->status_id == 3) {
            return self::READY_FOR_DISCUSSION;
        } else if ($this->status_id == 4) {
            return self::COMPLETED;
        }
    }

    public function getPrefixIdAttribute($value)
    {
        return sprintf(self::PREFIX."%05d", (int) $value);
    }

    public static function getNextPrefixId($company_id)
    {
        $last_registration = Division::where('company_id', $company_id)->orderBy('id', 'desc')->first()?->getRawOriginal('prefix_id');
        return (int) $last_registration + 1;
    }
}
