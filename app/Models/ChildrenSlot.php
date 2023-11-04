<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenSlot extends Model
{
    use HasFactory;
    protected $fillable = [
        'children_id',
        'day_id',
        'hour_id'
    ];
}
