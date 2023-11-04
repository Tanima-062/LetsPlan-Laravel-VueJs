<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Spielgruppen extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
        'image',
        'name',
        'street_name',
        'place_id',
        'street_number',
        'postal_code',
        'location',
        'minimum_occupancy',
        'maximum_occupancy',
        'status_id',
        'parent_admin_id',
        'company_id',
        'order'
    ];

    protected $appends = [
        'file_url'
    ];
    public $with = ['openingHours'];

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class);
    }
    public function leaderOpeningHours()
    {
        return $this->hasMany(OpeningHour::class)->where('lead_id',auth()->id());
    }
    public function assistantOpeningHours()
    {
        return $this->hasMany(OpeningHour::class)->where('assistant_id',auth()->id());
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status_id',1);
    }
    public function childrenRegistrations()
    {
        return $this->belongsToMany(ChildrenRegistration::class, 'children_registration_opening_hours','spielgruppen_id','children_registration_id')->withPivot('day_id','hour_id','color','order','id')->wherePivotNull('deleted_at');
    }
    public function uniqueChildrens(){
        return $this->belongsToMany(ChildrenRegistration::class, 'children_registration_opening_hours','spielgruppen_id','children_registration_id')->distinct();
    }
    public function getFileUrlAttribute()
    {
        if (is_null($this->file)) {
            return asset('/images/image.png');
        }

        if (preg_match("@http@", $this->file)) {
            return $this->image;
        }

        return Storage::disk(env('FILESYSTEM_DISK'))->url($this->file);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($spielgruppen){
            $spielgruppen->prefix_id = nextId('spielgruppens', self::PREFIX);
        });
    }
    public const PREFIX = 'SPG';

    public function scopeLocation($q, $locations = null)
    {
        if (!$locations) {
            return $q;
        }

        return $q->whereIn('location', explode(',', $locations));
    }

    public function scopeTime($q, $times = null){
        if(!$times){
            return $q;
        }
        $timeArr = explode(',',$times);
        $dayIds = [];
        $selectedTimes = [];
        foreach($timeArr as $time){
            $currentTimes = explode('_', $time);
            if(!in_array($currentTimes[0], $dayIds)){
                array_push($dayIds, $currentTimes[0]);
            }
            if(!in_array($currentTimes[1], $selectedTimes)){
                array_push($selectedTimes, $currentTimes[1]);
            }
        }

        return $q->whereIn('morning_time_range', $selectedTimes)->whereHas('openingHours', function ($query) use ($dayIds) {
                    $query->whereIn('day_id', $dayIds);
                });

    }

    public function scopeLead($q, $leads = null){
        if(!$leads){
            return $q;
        }

        $q->whereHas('openingHours', function ($query) use ($leads) {
            $query->whereIn('lead_id', explode(',', $leads));
        });
    }

    public function scopeAssistant($q, $assistants = null){
        if(!$assistants){
            return $q;
        }
        $q->whereHas('openingHours', function ($query) use ($assistants) {
            $query->whereIn('assistant_id', explode(',', $assistants));
        });
    }

    public function scopeStatusIs($q, $statusIds)
    {
        if (!$statusIds) {
            return $q;
        }

        return $q->whereIn('status_id', explode(',', $statusIds));
    }

    public function scopeOrderByColumns($q, $column = null, $direction = "DESC")
    {
        $orderable_columns = ['name', 'street_name', 'status_id'];
        $directions = ['ASC', 'DESC'];

        if (!$column || !in_array($column, $orderable_columns) || !$direction || !in_array($direction, $directions)) {
            return $q->orderBy('id','DESC');
        }

        return $q->orderBy($column, $direction);
    }

    public function scopeSearch($q, $search)
    {
        return $q->where(function ($query) use ($search) {
            $query->whereRaw("name LIKE ?", ["%$search%"])
                ->orWhereRaw('location LIKE ?', ["%$search%"])
                ->orWhereRaw('postal_code LIKE ?', ["%$search%"]);
        });
    }




    public function scopeFilterByPlaygroupLeader($query, $lead)
    {
        $query->whereHas('openingHours', function($q) use($lead){
            $playgroupLeaders = explode(',',  $lead);
            $q->whereIn('lead_id', $playgroupLeaders);
        });
    }
    public function scopeFilterByPlaygroupAssistant($query, $assistants)
    {
        $query->whereHas('openingHours', function($q) use($assistants){
            $playgroupLeaders = explode(',',  $assistants);
            $q->whereIn('assistant_id', $playgroupLeaders);
        });
    }

}
