<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenugasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tugas' => 'required|date',
            'deadline' => 'required|date|after_or_equal:tanggal_tugas',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'pegawai_id' => 'required|array|min:1',
            'pegawai_id.*' => 'exists:pegawai,id',
            'template' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
