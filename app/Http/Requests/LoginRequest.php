<?php

namespace App\Http\Requests;

use App\Http\Resources\ApiResponseResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'bail|required|string|regex:/^9639\d{8}$/|exists:users,phone_number',
            'password' => 'bail|required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            // 'phone_number' => 'required|string|exists:users,phone_number',
            'phone_number.required' => __('validation.required', ['attribute' => __('fields.phone_number')]),
            'phone_number.regex'    => __('validation.custom.phone_number.regex', ['attribute' => __('fields.phone_number')]),
            'phone_number.exists'   => __('validation.exists', ['attribute' => __('fields.phone_number')]),

            // 'password' => 'required|string|min:6',
            'password.required'     => __('validation.required', ['attribute' => __('fields.password')]),
            'password.min'          => __('validation.min', ['attribute' => __('fields.password'), 'min' => 6]),

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
        (new ApiResponseResource([
            'status' => '422 Unprocessable Entity',
            'message' => array_values($validator->errors()->all()),
            'data' => null
        ]))->response()->setStatusCode(422)
        ); // 422 Unprocessable Entity

    }
}
