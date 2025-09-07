<?php

namespace App\Models\leave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days_requested',
        'status',
        'reason',
        'reviewed_by',
        'reviewed_at',
        'review_comments',
        'documentation_path',
        'pdf_report_path'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

     // Check if PDF report exists
    public function hasPdfReport(): bool
    {
        return !empty($this->pdf_report_path) && Storage::disk('public')->exists($this->pdf_report_path);
    }

    // Get PDF report URL
    public function getPdfReportUrl(): ?string
    {
        if ($this->hasPdfReport()) {
            return asset('storage/' . $this->pdf_report_path);
        }
        return null;
    }
}
