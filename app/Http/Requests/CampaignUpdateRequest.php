<?php

namespace App\Http\Requests;

use App\Enums\CampaignStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampaignUpdateRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'campaign_id' => [
                'required',
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
            'campaign_id.required' => 'CAMPAIGN ID IS REQUIRED.',
            'campaign_id.integer' => 'CAMPAIGN ID MUST BE A VALID INTEGER.',
            'campaign_id.exists' => 'THE SELECTED CAMPAIGN IS EITHER INACTIVE OR DOES NOT EXIST.',
        ];
    }



}
