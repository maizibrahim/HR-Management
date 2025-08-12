<?php

namespace App\Models\leave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LeaveBalance extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'days_remaining',
        'reset_date'
    ];

    protected $casts = [
        'reset_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
