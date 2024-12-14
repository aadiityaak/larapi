<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
{
  public function rules()
  {
    return [
      'favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg|max:2048',
      'app_name' => 'nullable|string|max:255', // Changed to nullable for flexibility
      'app_description' => 'nullable|string|max:500', // Changed to nullable for flexibility
      'alamat' => 'nullable|string|max:255', // Changed to nullable for flexibility
      'pdf_sample' => 'nullable|file|mimes:pdf|max:2048',
      'email' => 'nullable|email|max:255',
    ];
  }

  public function authorize()
  {
    return true; // Ensure that the user is authorized to make this request
  }

  public function messages()
  {
    return [
      'favicon.mimes' => 'Favicon must be a file of type: ico, png, jpg, jpeg.',
      'app_name.max' => 'App name may not be greater than :max characters.',
      'app_description.max' => 'App description may not be greater than :max characters.',
      'alamat.max' => 'Address may not be greater than :max characters.',
      'pdf_sample.mimes' => 'PDF sample must be a file of type: pdf.',
      'email.email' => 'Email must be a valid email address.',
      'email.max' => 'Email may not be greater than :max characters.',
    ];
  }
}
