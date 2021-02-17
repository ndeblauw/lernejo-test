<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Tenant as baseTenant;

class Tenant extends baseTenant
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
