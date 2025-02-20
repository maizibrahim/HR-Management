<?php

namespace App\Models\designation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class classification extends Model
{
    protected $fillable = [
        'name'
    ];
}
