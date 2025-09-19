<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'user_id',
        'due_at',
        'priority',
        'completed_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Accessor for computed is_completed boolean.
     */
    protected function isCompleted(): Attribute
    {
        return Attribute::get(fn () => ! is_null($this->completed_at));
    }

    /**
     * Determine if the task is approaching its deadline (within 24h).
     */
    public function isDueSoon(): bool
    {
        if (! $this->due_at) {
            return false;
        }

        $now = CarbonImmutable::now();
        return $this->due_at->lessThanOrEqualTo($now->addDay()) && $this->due_at->greaterThan($now) && ! $this->isCompleted;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


