<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchParticipantForAreasRequest extends FormRequest
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
            'areaIds' => ['required', 'array'],
            'areaIds.*' => ['required', Rule::exists('areas', 'id')],
        ];
    }
}
