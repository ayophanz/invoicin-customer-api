<?php

namespace App\Transformers;

class CustomerTransformer extends BaseTransformer
{
    /**
     * Transformer for Customer.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Customer.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'              => $item->id,
            'type'            => $item->type,
            'organization_id' => $item->organization_id,
            'profile'         => $item->profile,
            'first_name'      => $item->first_name,
            'last_name'       => $item->last_name,
            'company_name'    => $item->company_name,
            'email'           => $item->email,
            'phone_no'        => $item->phone_no,
            'addresses'       => $item->addresses ? (array) $this->relationTransformer($item->addresses, new CustomerAddressTransformer) : [],
            'settings'        => $item->settings ? (array) $this->relationTransformer($item->settings, new CustomerSettingTransformer) : [],
        ];
    }
}
