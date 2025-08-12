<?php

namespace App\Models\leave;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class LeaveGroup extends Model
{
   use HasFactory;

    protected $fillable = ['name', 'description'];

    public function leaveTypes(): HasMany
    {
        return $this->hasMany(LeaveType::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
