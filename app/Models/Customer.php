<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

     /**
     * A customer has many addresses.
     *
     * @return HasMany the attached addresses
     */
    public function customerAddresses() : HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * An customer has many customer settings.
     *
     * @return morphToMany The attached customer settings.
     */
    public function settings()
    {
        return $this->morphMany(CustomerSetting::class, 'sourceable'); 
    }
}
