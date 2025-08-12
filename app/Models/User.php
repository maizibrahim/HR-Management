<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Models\leave\LeaveBalance;
use App\Models\leave\LeaveGroup;
use App\Models\leave\LeaveRequest;
use App\Models\leave\LeaveType;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'leave_group_id',
        'supervisor_id',
        'join_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
             'join_date' => 'date',
        ];
    }

    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;
    }

    public static function getpermissionByGroupName ($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name','id')
            ->where('group_name',$group_name)
            ->get();
        return $permissions;
    }



    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
            }

            return $hasPermission;
        }

    }

    public function leaveGroup(): BelongsTo
    {
        return $this->belongsTo(LeaveGroup::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function reviewedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'reviewed_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor' || $this->subordinates()->count() > 0;
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    // Permission checking methods
    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->isHR();
    }

    public function canApproveLeave(): bool
    {
        return $this->isSupervisor() || $this->isHR() || $this->isAdmin();
    }

    public function canManageLeaveTypes(): bool
    {
        return $this->isAdmin() || $this->isHR();
    }

    public function canViewReports(): bool
    {
        return $this->isAdmin() || $this->isHR();
    }

    public function canManageLeaveBalances(): bool
    {
        return $this->isAdmin() || $this->isHR();
    }

    // Helper methods
    public function getFullNameWithRoleAttribute(): string
    {
        return $this->name . ' (' . ucfirst($this->role) . ')';
    }

    public function getSubordinatesCountAttribute(): int
    {
        return $this->subordinates()->count();
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            'admin' => 'bg-danger',
            'hr' => 'bg-warning',
            'supervisor' => 'bg-info',
            'employee' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_active ? 'bg-success' : 'bg-danger';
    }

    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    // Check if user can approve a specific leave request
    public function canApproveRequest(LeaveRequest $leaveRequest): bool
    {
        // User can approve if they are the supervisor of the requesting user
        if ($this->id === $leaveRequest->user->supervisor_id) {
            return true;
        }

        // HR and Admin can approve any request
        if ($this->isHR() || $this->isAdmin()) {
            return true;
        }

        return false;
    }

    // Check if user can view a specific leave request
    public function canViewRequest(LeaveRequest $leaveRequest): bool
    {
        // User can view their own requests
        if ($this->id === $leaveRequest->user_id) {
            return true;
        }

        // User can view requests from their subordinates
        if ($this->id === $leaveRequest->user->supervisor_id) {
            return true;
        }

        // HR and Admin can view any request
        if ($this->isHR() || $this->isAdmin()) {
            return true;
        }

        return false;
    }

    // Check if user can edit another user
    public function canEditUser(User $user): bool
    {
        // Users cannot edit themselves through user management (use profile instead)
        if ($this->id === $user->id) {
            return false;
        }

        // Admin can edit anyone
        if ($this->isAdmin()) {
            return true;
        }

        // HR can edit non-admin users
        if ($this->isHR() && !$user->isAdmin()) {
            return true;
        }

        return false;
    }

    // Check if user can delete another user
    public function canDeleteUser(User $user): bool
    {
        // Users cannot delete themselves
        if ($this->id === $user->id) {
            return false;
        }

        // Only admin can delete users
        if ($this->isAdmin()) {
            return true;
        }

        return false;
    }

    // Get available supervisors for this user
    public function getAvailableSupervisors()
    {
        return User::where('id', '!=', $this->id)
            ->whereIn('role', ['supervisor', 'hr', 'admin'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    // Scope for active users
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for users by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope for users with subordinates
    public function scopeWithSubordinates($query)
    {
        return $query->whereHas('subordinates');
    }

    // Get pending leave requests count for supervisor dashboard
    public function getPendingApprovalsCountAttribute(): int
    {
        if (!$this->canApproveLeave()) {
            return 0;
        }

        return LeaveRequest::whereHas('user', function ($query) {
            $query->where('supervisor_id', $this->id);
        })
        ->where('status', 'pending')
        ->count();
    }

    // Get user's remaining leave days for a specific leave type
    public function getRemainingLeaveDays($leaveTypeId): int
    {
        $balance = $this->leaveBalances()
            ->where('leave_type_id', $leaveTypeId)
            ->first();

        return $balance ? $balance->days_remaining : 0;
    }

    // Check if user has sufficient leave balance
    public function hasSufficientBalance($leaveTypeId, $daysRequested): bool
    {
        return $this->getRemainingLeaveDays($leaveTypeId) >= $daysRequested;
    }


}
