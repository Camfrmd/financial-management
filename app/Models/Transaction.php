<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id', 'fund_id', 'user_id', 'type', 
    'amount', 'date', 'description', 'receipt_path', 
    'validation_status', 'validated_by'
])]
class Transaction extends Model
{
    protected $primaryKey = 'transaction_id';

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class, 'fund_id', 'fund_id');
    }

    // Le trésorier qui a saisi l'opération
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Le Kelian qui a validé
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by', 'user_id');
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(MemberContribution::class, 'transaction_id', 'transaction_id');
    }
}