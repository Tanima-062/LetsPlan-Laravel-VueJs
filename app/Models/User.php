<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Company;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    const PREFIX = "BN";

    const PLAYGROUP_LEADER = 2;
    const PLAYGROUP_ASSISTANT = 3;
    const ADMIN = 1;
    const SUPER_ADMIN = 4;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public static function getUserRoles(): array
    {
        return [
            self::PLAYGROUP_LEADER => 'Spielgruppenleiter/in',
            self::PLAYGROUP_ASSISTANT => 'Spielgruppen Assistent/in',
            self::ADMIN => 'Spielgruppen Admin',
            self::SUPER_ADMIN => 'System Admin'
        ];
    }
    public static function getUserByRole($roleId): string
    {
        return self::getUserRoles()[$roleId] ?? '-';
    }
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['role', 'company'];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uniq_id',
        'first_name',
        'last_name',
        'image',
        'email',
        'password',
        'gender_id',
        'role_id',
        'status_id',
        'street_name',
        'house_number',
        'postal_code',
        'prefix',
        'phone_number',
        'location',
        'parent_admin_id',
        'company_id',
        'email_notifications',
        'email_verified_at',
        'prefix_id'
    ];


    /**
     * Register any events for your application.
     *
     */

    protected static function booted()
    {
        static::creating(function ($user) {
            // $user->prefix_id = self::getNextPrefixId($user->company_id);
        });
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopePlaygroups($query)
    {
        return $query->whereIn('role_id', [self::PLAYGROUP_LEADER, self::PLAYGROUP_ASSISTANT]);
    }
    public function scopeLeads($query)
    {
        return $query->where('role_id', self::PLAYGROUP_LEADER);
    }
    public function scopeAssistants($query)
    {
        return $query->where('role_id', self::PLAYGROUP_ASSISTANT);
    }
    public function scopeByCompany($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function scopeCompanyIs($q, $companyIds)
    {
        if (!$companyIds) {
            return $q;
        }

        return $q->whereIn('company_id', explode(',', $companyIds));
    }
    public function scopeStatusIs($q, $statusIds)
    {
        if (!$statusIds) {
            return $q;
        }

        return $q->whereIn('status_id', explode(',', $statusIds));
    }
    public function scopeSearch($q, $search)
    {
        return $q->where(function ($query) use ($search) {
            $query->whereRaw("first_name LIKE ?", ["%$search%"])
                ->orWhereRaw('last_name LIKE ?', ["%$search%"]);
        })->whereHas('company', function ($query) use ($search) {
            $query->whereRaw("name LIKE ?", ["%$search%"]);
        });
    }


    public function scopeSearchAdminUser($query, $search)
    {
        $query
            ->where('first_name', 'LIKE', "$search%")
            ->orWhere('last_name', 'LIKE', "$search%")
            ->orWhereRaw("CONCAT(`first_name`, ' ', `last_name`) LIKE ?", [$search . '%'])
            ->orWhere(function ($q) use ($search) {
                $q->whereHas('company', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            });
    }


    public static function adminNamebyCompany($company_id)
    {
        $user = static::where('company_id', $company_id)->where('role_id', self::ADMIN)->get()->first();
        return $user ? ($user->first_name . ' ' . $user->last_name) : '';
    }
    public function scopeOrderByColumns($q, $column = null, $direction = "DESC")
    {
        $orderable_columns = ['created_at', 'id', 'first_name', 'status_id', 'email', 'company'];
        $directions = ['ASC', 'DESC'];

        if (!$column || !in_array($column, $orderable_columns) || !$direction || !in_array($direction, $directions)) {
            return $q->orderBy('id', 'DESC');
        }

        if ($column == 'company') {
            return $q->orderBy(Company::select('name')
                ->whereColumn('users.company_id', 'companies.id'), $direction);
        }

        return $q->orderBy($column, $direction);
    }


    //getters

    // public function getPrefixIdAttribute($value)
    // {
    //     return sprintf(self::PREFIX . "%05d", (int) $value);
    // }


    public static function getNextPrefixId($company_id)
    {
        $last_registration = User::where('company_id', $company_id)->where(fn($q) => $q->where('role_id', 3)->orWhere('role_id', 2))->orderBy('id', 'desc')->first()?->getRawOriginal('prefix_id');

        return (int) $last_registration + 1;
    }

    public static function getNextPrefixIdByRole($company_id, $role_id)
    {
        $last_registration = User::where('company_id', $company_id)
        ->where('role_id', $role_id)
        ->orderBy('id', 'desc')
        ->count()
        ;

        info('last registteration');
        info($last_registration);

        $id =  $last_registration > 0 ? $last_registration + 1 : 1;

        return $id;
    }
}
