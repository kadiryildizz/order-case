<?php

namespace App\Http\Requests;

use App\Enums\CampaignStatus;
use App\Enums\CustomerStatus;
use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasketOrderAddRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where('status', ProductStatus::ACTIVE),
            ],
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')->where('status', CustomerStatus::ACTIVE),
            ],
            'quantity' => [
                'required',
                'integer',
                'between:-5,7',
            ],
            'campaign_id' => [
                'nullable',
                'integer',
                Rule::exists('campaigns', 'id')->where('status', CampaignStatus::ACTIVE),
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'PRODUCT ID IS REQUIRED.',
            'product_id.integer' => 'PRODUCT ID MUST BE A VALID INTEGER.',
            'product_id.exists' => 'THE SELECTED PRODUCT IS EITHER INACTIVE OR DOES NOT EXIST.',

            'customer_id.required' => 'CUSTOMER ID IS REQUIRED.',
            'customer_id.integer' => 'CUSTOMER ID MUST BE A VALID INTEGER.',
            'customer_id.exists' => 'THE SELECTED CUSTOMER IS EITHER INACTIVE OR DOES NOT EXIST.',

            'quantity.required' => 'QUANTITY IS REQUIRED.',
            'quantity.integer' => 'QUANTITY MUST BE A VALID INTEGER.',
            'quantity.between' => 'QUANTITY MUST BE BETWEEN -5 AND 7.',

            'campaign_id.integer' => 'CAMPAIGN ID MUST BE A VALID INTEGER.',
            'campaign_id.exists' => 'THE SELECTED CAMPAIGN IS EITHER INACTIVE OR DOES NOT EXIST.',
        ];
    }



}
