<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // users
            'name'               => 'required|string|max:255',
            'nip'                => 'required|string|max:30|unique:users,nip|regex:/^\d+$/',
            'email'              => 'required|email|unique:users,email',
            'password'           => ['required', Password::defaults()],
            'role'               => 'required|in:admin,pegawai,kph',
            'status_akun'        => 'required|in:aktif,nonaktif',
            'catatan_verifikasi' => 'nullable|string',

            // pegawai
            'unitkerja_id'   => 'required|exists:ref_unitkerja,id',
            'golongan_id'    => 'required|exists:ref_golongan,id',
            'jabatan_id'     => 'required|exists:ref_jabatan,id',
            'status_pegawai' => 'required|string',
        ];
    }
}
