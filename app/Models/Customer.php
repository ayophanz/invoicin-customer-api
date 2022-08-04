<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'profile', 
        'type',
        'first_name', 
        'last_name',
        'company_name',
        'email',
        'phone_no',
    ];
}
