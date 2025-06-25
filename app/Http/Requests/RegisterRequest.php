<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'whatsapp' => ['nullable', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.unique' => 'This email is already registered.',
            'whatsapp.unique' => 'This WhatsApp number is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->whatsapp) {
            $this->merge([
                'whatsapp' => preg_replace('/[^0-9]/', '', $this->whatsapp)
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->email && !$this->whatsapp) {
                $validator->errors()->add('email', 'Either email or WhatsApp number is required.');
                $validator->errors()->add('whatsapp', 'Either email or WhatsApp number is required.');
            }
        });
    }
}