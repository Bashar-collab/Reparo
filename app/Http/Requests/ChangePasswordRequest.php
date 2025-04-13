<?php

namespace App\Http\Requests;

use App\Http\Resources\ApiResponseResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // You can customize authorization logic if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'current_password.required' => __('validation.required', ['attribute' => __('fields.current_password')]),
            'new_password.required' => __('validation.required', ['attribute' => __('fields.new_password')]),
            'new_password.min' => __('validation.min', ['attribute' => __('fields.new_password'), 'min' => 6]),
            'new_password.confirmed' => __('validation.confirmed', ['attribute' => __('fields.new_password')]),
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
