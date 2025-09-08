<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
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
            "username" => "required|string|min:4|max:12|regex:/^\S*$/|unique:users,username",
            "fullname" => "required|string",
            "email"    => "required|email|unique:users,email",
            "password" => "required|string|min:6",
            "class"    => "required|string",
        ];
    }

    public function messages(): array
    {
        return [
            "username.required" => "Username wajib diisi.",
            "username.string"   => "Username harus berupa teks.",
            "username.min"      => "Username minimal 4 karakter.",
            "username.max"      => "Username maksimal 12 karakter.",
            "username.regex"    => "Username tidak boleh mengandung spasi.",
            "username.unique"   => "Username sudah digunakan, silakan pilih yang lain.",

            "fullname.required" => "Nama lengkap wajib diisi.",
            "fullname.string"   => "Nama lengkap harus berupa teks.",

            "email.required"    => "Email wajib diisi.",
            "email.email"       => "Format email tidak valid.",
            "email.unique"      => "Email sudah terdaftar.",

            "password.required" => "Password wajib diisi.",
            "password.string"   => "Password harus berupa teks.",
            "password.min"      => "Password minimal 6 karakter.",

            "class.required"    => "Kelas wajib diisi.",
            "class.string"      => "Kelas harus berupa teks.",
        ];
    }
}
