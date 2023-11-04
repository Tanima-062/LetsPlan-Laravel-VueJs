<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','uniq_id','first_name','last_name','country_code', 'telephone','email','city_id','status', 'created_at', 'logo'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function division()
    {
        return $this->hasMany(Division::class, 'company_id');
    }
}
