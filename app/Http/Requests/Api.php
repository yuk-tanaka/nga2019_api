<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait Api
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throw HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json($validator->errors()->toArray(), 422));
    }
}