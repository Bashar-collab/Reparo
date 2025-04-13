<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rules\Password;
use App\Http\Resources\ApiResponseResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'unique:users,phone_number', 'regex:/^9639\d{8}$/'],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'profilable_type' => ['required', 'string', Rule::in(array_keys(Config::get('profile_types')))],
            'fcm_token' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            // 'name.required' => 'The name field is required.',
            'name.required' => __('validation.required', ['attribute' => __('fields.name')]),
            'name.string'   => __('validation.string', ['attribute' => __('fields.name')]),
            'name.min'      => __('validation.min.string', ['attribute' => __('fields.name'), 'min' => 2]),
            'name.max'      => __('validation.max.string', ['attribute' => __('fields.name'), 'max' => 50]),
            
            // 'name.min' => 'The name must be at least 2 characters.',
            // 'name.max' => 'The name may not be greater than 255 characters.',

            // 'email.required' => 'The email field is required.',
            // 'email.email' => 'Please provide a valid email address.',
            // 'email.unique' => 'This email is already registered.',
            'phone_number.required' => __('validation.required', ['attribute' => __('fields.phone_number')]),
            'phone_number.regex'    => __('validation.phone_number.regex', ['attribute' => __('fields.phone_number')]),
            'phone_number.unique'   => __('validation.unique', ['attribute' => __('fields.phone_number')]),

            // 'password.required' => 'The pin field is required.',
            'password.required'     => __('validation.required', ['attribute' => __('fields.password')]),
            'password.min'          => __('validation.min', ['attribute' => __('fields.password'), 'min' => 6]),
            'password.confirmed'    => __('validation.confirmed', ['attribute' => __('fields.password')]),

            'profilable_type.required' => 'Please select a valid profile type.',
            'profilable_type.in' => 'Invalid profile type selected.',
            'fcm_token.string' => 'The FCM token must be a valid string.',
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
