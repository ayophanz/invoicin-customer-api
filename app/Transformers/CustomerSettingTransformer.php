<?php

namespace App\Transformers;

class CustomerSettingTransformer extends BaseTransformer
{
    /**
     * Transformer for Customer setting.
     *
     * @param  \Illuminate\Database\Eloquent\Model $item The Customer setting.
     *
     * @return string[] The valid output, displayed in the API.
     */
    public function transform($item, $method = 'index') : array
    {
        return [
            'id'              => $item->id,
            'sourceable_type' => $item->sourceable_type,
            'sourceable_id'   => $item->sourceable_id,
            'key'             => $item->key,
            'value'           => $item->value,
        ];
    }
}
