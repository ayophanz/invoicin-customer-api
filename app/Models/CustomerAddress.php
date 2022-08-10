<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_address_type_id',
        'address',
        'country_id'
    ];

    /**
     * An address belongs to a customer.
     *
     * @return BelongsTo The attached customer.
     */
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * An address belongs to a address type.
     *
     * @return BelongsTo The attached address type.
     */
    public function customerAddressType() : BelongsTo
    {
        return $this->belongsTo(CustomerAddressType::class);
    }

    /**
     * An address belongs to a country.
     *
     * @return BelongsTo The attached address type.
     */
    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
