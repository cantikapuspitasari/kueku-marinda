<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Permintaan hitung ongkir hanya membutuhkan koordinat.
        if ($this->input('mode') === 'hitung_ongkir') {
            return [
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
            ];
        }

        return [
            'ongkir_token' => 'nullable|string',

            'id_alamat' => [
                'nullable',
                Rule::exists('alamat', 'id_alamat')
                    ->where('id_pembeli', Auth::guard('pembeli')->id()),
            ],

            'alamat_lengkap' => 'nullable|string|max:500',
            'no_telepon' => 'required|string|min:9|max:20',

            'tanggal_ambil' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
                'before_or_equal:' . now()->addDays(7)->toDateString(),
            ],

            'jam_ambil' => 'required|date_format:H:i',

            'jenis_pengambilan' => 'required|in:PICKUP,DELIVERY',

            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|integer|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_ambil.required' => 'Tanggal pengambilan wajib diisi.',
            'tanggal_ambil.after_or_equal' => 'Tanggal pengambilan tidak boleh di masa lalu.',
            'tanggal_ambil.before_or_equal' => 'Pesanan hanya bisa dijadwalkan sampai 7 hari ke depan.',
            'jam_ambil.required' => 'Jam pengambilan wajib diisi.',
            'jam_ambil.date_format' => 'Format jam tidak valid.',
            'jenis_pengambilan.required' => 'Pilih jenis pengambilan.',
            'jenis_pengambilan.in' => 'Jenis pengambilan tidak valid.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'items.required' => 'Pesanan harus berisi minimal 1 produk.',
            'items.min' => 'Pesanan harus berisi minimal 1 produk.',
            'items.*.id_produk.exists' => 'Salah satu produk tidak ditemukan.',
            'items.*.jumlah.min' => 'Jumlah produk minimal 1.',
            'items.*.jumlah.max' => 'Jumlah produk maksimal 50 per pesanan.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('mode') === 'hitung_ongkir') {
                return;
            }

            if (
                $this->input('jenis_pengambilan') === 'DELIVERY' &&
                empty($this->input('id_alamat')) &&
                empty($this->input('alamat_lengkap'))
            ) {
                $validator->errors()->add(
                    'alamat_lengkap',
                    'Alamat wajib diisi untuk pesanan delivery.'
                );
            }
        });
    }
}
