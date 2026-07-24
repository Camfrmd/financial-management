<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['group_id', 'member_name', 'status'])]
class Member extends Model
{
    use LogsActivity;

    protected $primaryKey = 'member_id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Member {$eventName}");
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(CommunityGroup::class, 'group_id', 'group_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(MemberContribution::class, 'member_id', 'member_id');
    }
}