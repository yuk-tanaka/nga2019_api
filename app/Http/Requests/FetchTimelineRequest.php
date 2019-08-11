<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FetchTimelineRequest extends FormRequest
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
            'areaIds' => ['required_without_all:participantIds', 'array'],
            'areaIds.*' => ['required_with:areaIds', Rule::exists('areas', 'id')],
            'participantIds' => ['required_without_all:areaIds', 'array'],
            'participantIds.*' => ['required_with:participantIds', Rule::exists('participants', 'id')],
        ];
    }
}
