<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['group_id', 'member_name', 'status'])]
class Member extends Model
{
    protected $primaryKey = 'member_id';

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id', 'group_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(MemberContribution::class, 'member_id', 'member_id');
    }
}