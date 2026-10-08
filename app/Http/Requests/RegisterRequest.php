<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'                  => 'required|string|min:3|max:100',
            'email'                 => 'required|email|max:120|unique:pembeli,email',
            'password'              => 'required|min:6|confirmed',
            'no_telepon'            => 'required|string|regex:/^[0-9+\-\s]{10,20}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'      => 'Nama wajib diisi.',
            'nama.min'           => 'Nama minimal 3 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar, silakan login.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_telepon.required'=> 'Nomor telepon wajib diisi.',
            'no_telepon.regex'   => 'Format nomor telepon tidak valid (10-20 digit, boleh pakai + atau -).',
        ];
    }
}