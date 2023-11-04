<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ChildrenRegistration extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'child_first_name',
        'child_last_name',
        'gender_id',
        'child_birthday',
        'nationality_id',
        'mother_tongue_id',
        'other_mother_tongue',
        'mother_first_name',
        'mother_last_name',
        'father_first_name',
        'father_last_name',
        'street',
        'street_number',
        'postal_code',
        'place',
        'email',
        'phone_number_prefix',
        'phone_number',
        'mobile_phone_number_prefix',
        'mobile_phone_number',
        'semester_id',
        'membership_id',
        'note',
        'internal_comments',
        'place_id',
        'company_id',
        'session_id',
        'spielgruppen_id',
        'closest_spielgruppen',
        'created_from',
        'status_id',
        'marked',
        'division_id',
        'mail_sent_at',
        'optional_address',
        'prefix_id'
    ];

    protected $dates = ['child_birthday', 'mail_sent_at'];

    const PREFIX = "AM";


    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_IN_PROGRESS = 3;



    /**
     * Register any events for your application.
     *
     */

    protected static function booted()
    {
        static::creating(function($registration) {
            $registration->prefix_id = self::getNextPrefixId($registration->company_id);
        });
    }

    public function getFullNameAttribute()
    {
        return $this->child_first_name . ' ' . $this->child_last_name;
    }

    public function motherTongue()
    {
        return $this->belongsTo(MotherTongue::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }
    public function selectedSpielgruppen()
    {
        return $this->belongsTo(Spielgruppen::class, 'spielgruppen_id');
    }
    public function closestSpielgruppen()
    {
        return $this->belongsTo(Spielgruppen::class, 'closest_spielgruppen');
    }

    public function spielgruppen()
    {
        return $this->belongsToMany(Spielgruppen::class, 'children_registration_opening_hours', 'children_registration_id', 'spielgruppen_id')->wherePivotNull('deleted_at');
    }

    public function childrenRegistrationOpeningHours()
    {
        return $this->hasMany(ChildrenRegistrationOpeningHour::class, 'children_registration_id');
    }
    public function slots()
    {
        return $this->hasMany(ChildrenSlot::class, 'children_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    //Local Scopes

    /**
     * When Creation Date is between $start_date and $end_date
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param string|null $start_date DateTime string or null
     * @param string|null $end_date DateTime string or null
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeCreatedAt($q, $start_date, $end_date)
    {
        if (is_null($start_date) || is_null($end_date)) {
            return $q;
        }
        return $q->whereBetween('created_at', [Carbon::parse($start_date)->addSecond(), Carbon::parse($end_date)->addHours(23)->addMinutes(59)->addSeconds(59)]);
    }

    /**
     * Search for the child's first name, surname, date of birth or place of residence
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param string $search
     *
     * @return Illuminate\Database\Query\Builder
     */

    public function scopeSearch($q, $search)
    {


        return $q->where(function ($query) use ($search) {
            $query->whereRaw("CONCAT(`child_first_name`, ' ', `child_last_name`) LIKE ?", ["%$search%"])
            // ->orWhereRaw('child_birthday LIKE ?', ["%$search%"])
            ->orWhereRaw('street LIKE ?', ["%$search%"])
            ;


            if(preg_match("/([0-9]{2}).([0-9]{2}).([0-9]{4})/", $search, $matches)){
                $date = sprintf("%s-%s-%s", $matches[3], $matches[2], $matches[1]);
                $query->orWhere('child_birthday', 'LIKE', "$date%");
            }
        });
    }

    /**
     * Where assigned play groups is in the array
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param String|null $assigned_playgroups
     *
     * @return Illuminate\Database\Query\Builder
     */

    public function scopeAssignedPlaygroup($q, $assigned_playgroups)
    {
        if (!$assigned_playgroups) {
            return $q;
        }

        return $q->whereHas('childrenRegistrationOpeningHours', function ($query) use ($assigned_playgroups) {
            $query->whereIn('children_registration_opening_hours.spielgruppen_id', explode(',',  $assigned_playgroups));
        });
    }

    /**
     * Where preferred play groups (spielgruppen_id) is in the array
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param String|null $preferred_playgroups
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePreferredPlaygroup($q, $preferred_playgroups = null)
    {
        if (!$preferred_playgroups) {
            return $q;
        }

        $playgroups = explode(',', $preferred_playgroups);
        if(in_array('keine', $playgroups) && count($playgroups) == 1){
            info('hello');
            return $q->whereNull('spielgruppen_id');
        }
        if(in_array('keine', $playgroups) && count($playgroups) > 1){
           return $q->where(function ($query) use ($playgroups) {
                        $query->whereIn('spielgruppen_id',$playgroups)->orWhereNull('spielgruppen_id');
                    });
        }
        return $q->whereIn('spielgruppen_id', $playgroups);
    }

    /**
     * Registration has comment or not
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param Boolean|null $commented
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeIsCommented($q, $commented)
    {
        if (is_null($commented)) {
            return $q;
        }

        return $commented ? $q->whereNotNull('note') : $q->whereNull('note');
    }

    /**
     * Registration is marked or not
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param bool $marked
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeIsMarked($q, $marked)
    {
        if (is_null($marked)) {
            return $q;
        }

        return $q->where('marked', $marked);
    }

    /**
     * Where status is $status
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param integer $status
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeStatusIs($q, $status)
    {
        if (is_null($status)) {
            return $q;
        }

        return $q->where('status_id', $status);
    }

    /**
     * Where division is $division
     *
     * @param Illuminate\Database\Query\Builder $q
     * @param integer $status
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeDivisionIs($q, $division)
    {
        if (is_null($division)) {
            return $q;
        }

        return $q->where('division_id', $division);
    }

    public function scopeDivision($q, $divisions = null)
    {
        if (!$divisions) {
            return $q;
        }

        return $q->whereIn('division_id', explode(',', $divisions));
    }


    /**
     * Order By Columns And Directions
     * @param String $column
     * @param ASC|DESC $direction
     */
    public function scopeOrderByColumns($q, $column = 'created_at', $direction = "DESC")
    {
        $orderable_columns = ['child_name', 'preferred_playgroup', 'closest_playgroup', 'note', 'status_id', 'marked', 'divided_playgroup'];
        $directions = ['ASC', 'DESC'];
        if (!$column || !in_array($column, $orderable_columns) || !$direction || !in_array($direction, $directions)) {
            return $q->orderBy('created_at', 'DESC');
        }

        if ($column == 'child_name') {
            return $q->orderByRaw("CONCAT(child_first_name, ' ', child_last_name) $direction");
        }

        if ($column == 'preferred_playgroup') {
            return $q->orderBy(Spielgruppen::whereColumn('children_registrations.spielgruppen_id', 'spielgruppens.id')->select('name'), $direction);
        }
        if ($column == 'closest_playgroup') {
            return $q->orderBy(Spielgruppen::whereColumn('children_registrations.closest_spielgruppen', 'spielgruppens.id')->select('name'), $direction);
        }
        if ($column == 'divided_playgroup') {
            return $q->orderBy(
                Spielgruppen::join('children_registration_opening_hours as openingHours', 'children_registrations.id', '=', 'openingHours.children_registration_id')
                    ->join('spielgruppens as playgroups', 'playgroups.id', '=', 'openingHours.spielgruppen_id')
                    ->distinct()
                    ->limit(1)
                    ->select('playgroups.name'),
                $direction
            );
        }
        return $q->orderBy($column, $direction);
    }
    //getters

    public function getChildName()
    {
        return "$this->child_first_name $this->child_last_name";
    }

    public function getPrefixIdAttribute($value)
    {
        return sprintf(self::PREFIX."%05d", (int) $value);
    }


    public static function getNextPrefixId($company_id)
    {
        $last_registration = ChildrenRegistration::where('company_id', $company_id)->orderBy('id', 'desc')->first()?->getRawOriginal('prefix_id');
        return (int) $last_registration + 1;
    }

    public function scopeActiveAndPending($query)
    {
        return $query->whereIn('status_id', [1, 3]);
    }

    /**
     * Registrations with assigned playgroup
     *
     */
    public function scopeOptimallyAssigned($query)
    {
        return $query->whereHas('childrenRegistrationOpeningHours', fn($q) => $q->where('color', ChildrenRegistrationOpeningHour::GREEN));
    }

     /**
     * Registrations where assigned playgroup is not in closest locationt
     *
     */
    public function scopeNotInClosestLocation($query)
    {
        return $query->whereHas('childrenRegistrationOpeningHours',fn($q) => $q->where('color', ChildrenRegistrationOpeningHour::ORANGE));
    }

    /**
     * Registrations where assigned playgroup lead is not the same
     *
     */
    public function scopeNotAssignedToSameLead($query)
    {
        return $query->whereHas('childrenRegistrationOpeningHours',fn($q) => $q->where('color', ChildrenRegistrationOpeningHour::YELLOW));
    }

    /**
     * Registrations with manullay assigned lead
     *
     */
    public function scopeManuallyAssigned($query)
    {
        return $query->whereHas('childrenRegistrationOpeningHours', fn($q) => $q->where('color', ChildrenRegistrationOpeningHour::WHITE));
    }

    /**
     * Not In Both Closest location and playgroup lead
     *
     */
    public function scopeNotInBothBothLocationAndLead($query)
    {
        return $query->whereHas('childrenRegistrationOpeningHours', fn($q) => $q->where('color', ChildrenRegistrationOpeningHour::TURQUOISE));
    }

    /**
     * Registrations with not assigned
     *
     */
    public function scopeNotAssigned($query)
    {
        return $query->whereDoesntHave('childrenRegistrationOpeningHours');
    }


}
