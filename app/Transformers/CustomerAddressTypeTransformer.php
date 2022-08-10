<?php

namespace App\Transformers;

class CustomerAddressTypeTransformer extends BaseTransformer
{
    /**
     * Transformer for customer address type.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The customer address type.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'   => $item->id,
            'name' => $item->name,
        ];
    }
}
