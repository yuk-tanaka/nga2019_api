<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchParticipantForFavoritesRequest extends FormRequest
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
            'participantIds' => ['required', 'array'],
            'participantIds.*' => ['required', Rule::exists('participants', 'id')],
        ];
    }
}
