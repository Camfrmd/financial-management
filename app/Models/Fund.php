<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['group_id', 'activity_id', 'name', 'current_balance', 'description'])]
class Fund extends Model
{
    protected $primaryKey = 'fund_id';

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id', 'group_id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'fund_id', 'fund_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(MemberContribution::class, 'fund_id', 'fund_id');
    }
}