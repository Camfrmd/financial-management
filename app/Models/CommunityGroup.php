<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityGroup extends Model
{
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'group_name',
        'description',
    ];

    public function funds(): HasMany
    {
        return $this->hasMany(Fund::class, 'group_id', 'group_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'group_id', 'group_id');
    }
}