<?php

namespace App\Transformers;

class CustomerAddressTransformer extends BaseTransformer
{
    /**
     * Transformer for Customer address.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Customer address.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'           => $item->id,
            'customer_id'  => $item->customer_id,
            'address_type' => $item->addressType ? (array) $this->relationTransformer($item->addressType, new CustomerAddressTypeTransformer) : [],
            'country'      => $item->country ? (array) $this->relationTransformer($item->country, new CountryTransformer) : [],
            'address'      => $item->address,
        ];
    }
}
