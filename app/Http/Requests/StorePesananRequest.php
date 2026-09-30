<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cukup pastikan sudah login; middleware 'auth' sudah menjaga route ini
        return true;
    }

    public function rules(): array
    {
        return [
            'id_alamat'          => 'nullable|exists:alamat,id_alamat',
            'tanggal_ambil'      => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(30)->toDateString(),
            'jenis_pengambilan'  => 'required|in:PICKUP,DELIVERY',
            'items'              => 'required|array|min:1',
            'items.*.id_produk'  => 'required|exists:produk,id_produk',
            'items.*.jumlah'     => 'required|integer|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_ambil.required'        => 'Tanggal pengambilan wajib diisi.',
            'tanggal_ambil.after_or_equal'  => 'Tanggal pengambilan tidak boleh di masa lalu.',
            'tanggal_ambil.before_or_equal' => 'Tanggal pengambilan maksimal 30 hari ke depan.',
            'jenis_pengambilan.required'    => 'Pilih jenis pengambilan (ambil di toko atau delivery).',
            'jenis_pengambilan.in'          => 'Jenis pengambilan tidak valid.',
            'items.required'                => 'Pesanan harus berisi minimal 1 produk.',
            'items.min'                     => 'Pesanan harus berisi minimal 1 produk.',
            'items.*.id_produk.exists'      => 'Salah satu produk yang dipilih tidak ditemukan.',
            'items.*.jumlah.min'            => 'Jumlah tiap produk minimal 1.',
            'items.*.jumlah.max'            => 'Jumlah tiap produk maksimal 50 per pesanan.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Aturan lintas-kolom: delivery wajib punya alamat
            if ($this->input('jenis_pengambilan') === 'DELIVERY' && empty($this->input('id_alamat'))) {
                $validator->errors()->add('id_alamat', 'Alamat wajib diisi untuk pesanan delivery.');
            }
        });
    }
}