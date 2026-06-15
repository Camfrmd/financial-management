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

    protected static function booted(): void
    {
        static::saving(function (Transaction $transaction) {
            if ($transaction->category_id) {
                $category = Category::find($transaction->category_id);
                if ($category && is_null($category->parent_id)) {
                    throw new \Exception('A transaction cannot be linked to a parent category (Header Account).');
                }
            }
        });

        static::created(function (Transaction $transaction) {
            if ($transaction->validation_status === 'validated') {
                $transaction->applyToFund();
            }
        });

        static::updated(function (Transaction $transaction) {
            $originalStatus = $transaction->getOriginal('validation_status');
            $originalAmount = $transaction->getOriginal('amount');
            $originalType = $transaction->getOriginal('type');
            $originalFundId = $transaction->getOriginal('fund_id');

            if ($originalStatus === 'validated') {
                $transaction->revertFromFund($originalAmount, $originalType, $originalFundId);
            }

            if ($transaction->validation_status === 'validated') {
                $transaction->applyToFund();
            }
        });

        static::deleted(function (Transaction $transaction) {
            if ($transaction->validation_status === 'validated') {
                $transaction->revertFromFund($transaction->amount, $transaction->type, $transaction->fund_id);
            }
        });
    }

    private function applyToFund()
    {
        if (!$this->fund_id) return;
        $fund = Fund::find($this->fund_id);
        if (!$fund) return;

        if ($this->type === 'income') {
            $fund->current_balance += $this->amount;
        } elseif ($this->type === 'expense') {
            $fund->current_balance -= $this->amount;
        }
        $fund->save();
    }

    private function revertFromFund($amount, $type, $fund_id)
    {
        if (!$fund_id) return;
        $fund = Fund::find($fund_id);
        if (!$fund) return;

        if ($type === 'income') {
            $fund->current_balance -= $amount;
        } elseif ($type === 'expense') {
            $fund->current_balance += $amount;
        }
        $fund->save();
    }
}