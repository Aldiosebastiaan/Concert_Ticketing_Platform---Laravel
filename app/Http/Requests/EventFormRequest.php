<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Auth::check() && Auth::user()->role === 'admin';
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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_waktu' => 'required|date|after:now',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            'tikets' => 'required|array|min:1',
            'tikets.*.tipe' => 'required|in:reguler,premium',
            'tikets.*.harga' => 'required|numeric|min:0',
            'tikets.*.stok' => 'required|integer|min:0',
            'tikets.*.id' => 'nullable|exists:tikets,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul event wajib diisi.',
            'judul.string' => 'Judul event harus berupa teks.',
            'judul.max' => 'Judul event maksimal 255 karakter.',
            
            'deskripsi.required' => 'Deskripsi event wajib diisi.',
            'deskripsi.string' => 'Deskripsi event harus berupa teks.',
            
            'lokasi_id.required' => 'Lokasi event wajib dipilih.',
            'lokasi_id.exists' => 'Lokasi yang dipilih tidak valid.',
            
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            
            'tanggal_waktu.required' => 'Tanggal dan waktu event wajib diisi.',
            'tanggal_waktu.date' => 'Format tanggal dan waktu tidak valid.',
            'tanggal_waktu.after' => 'Tanggal dan waktu event harus setelah waktu saat ini.',
            
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus berupa jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            
            'tikets.required' => 'Minimal satu tiket harus ditambahkan.',
            'tikets.array' => 'Format tiket tidak valid.',
            'tikets.min' => 'Minimal satu tiket harus ditambahkan.',
            
            'tikets.*.tipe.required' => 'Tipe tiket wajib diisi.',
            'tikets.*.tipe.in' => 'Tipe tiket harus berupa reguler atau premium.',
            
            'tikets.*.harga.required' => 'Harga tiket wajib diisi.',
            'tikets.*.harga.numeric' => 'Harga tiket harus berupa angka.',
            'tikets.*.harga.min' => 'Harga tiket minimal 0.',
            
            'tikets.*.stok.required' => 'Stok tiket wajib diisi.',
            'tikets.*.stok.integer' => 'Stok tiket harus berupa angka bulat.',
            'tikets.*.stok.min' => 'Stok tiket minimal 0.',
            
            'tikets.*.id.exists' => 'Data tiket tidak ditemukan.',
        ];
    }
}
