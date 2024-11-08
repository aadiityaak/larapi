<?php

namespace App\Http\Requests\Auth;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:' . User::class . ',email,' . $this->user()->id
            ],
            'phone' => ['required', 'string'],
            'address' => ['required', 'string'],
            'position' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable'],
        ];

        if (is_string($this->avatar)) {
            unset($rules['avatar']);
        } elseif ($this->hasFile('avatar')) {
            $rules['avatar'] = ['image', 'max:2048', 'mimes:jpeg,png,jpg'];
        }

        return $rules;
    }
}
