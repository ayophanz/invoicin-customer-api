<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sourceable_type', 
        'sourceable_id', 
        'key',
        'value' 
    ];

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'sourceable_id');
    }
}
