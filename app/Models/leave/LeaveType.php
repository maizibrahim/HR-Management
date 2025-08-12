<?php

namespace App\Models\leave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_group_id',
        'leave_code',
        'leave_name',
        'days_allowed',
        'requires_documentation'
    ];

    protected $casts = [
        'requires_documentation' => 'boolean',
    ];

    public function leaveGroup(): BelongsTo
    {
        return $this->belongsTo(LeaveGroup::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
