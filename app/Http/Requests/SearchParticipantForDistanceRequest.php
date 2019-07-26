<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchParticipantForDistanceRequest extends FormRequest
{
    use Api;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude' => ['required', 'numeric', 'min:20', 'max:46'],
            'longitude' => ['required', 'numeric', 'min:122', 'max:154'],
        ];
    }
}
