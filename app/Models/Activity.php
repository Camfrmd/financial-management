<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['activity_name', 'start_date', 'end_date', 'status'])]
class Activity extends Model
{
    protected $primaryKey = 'activity_id';

    public function funds(): HasMany
    {
        return $this->hasMany(Fund::class, 'activity_id', 'activity_id');
    }
}