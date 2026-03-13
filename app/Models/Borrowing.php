<?php

namespace App\Models;

use App\Enums\BorrowingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrowing extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'status' => BorrowingStatus::class,
    ];

    // protected $with = ['items'];
    
    // protected function borrowableQuantity(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value) {
    //             $borrowed = $this->activeBorrowingItems->sum('quantity');

    //             return max(0, $this->quantity - $borrowed);
    //         }
    //     );
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(BorrowingItem::class, 'borrowing_id');
    }
}
