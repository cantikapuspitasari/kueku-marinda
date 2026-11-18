@extends('admin.layout', ['aktif' => 'transfer'])

@section('title', 'Pembayaran Transfer')
@section('judul', 'Pembayaran Transfer')
@section('subjudul', 'Periksa bukti transfer pembeli, lalu terima atau tolak. Pesanan transfer baru dianggap LUNAS setelah diterima.')

@php
  $tab = [
    'MENUNGGU_VERIFIKASI' => 'Menunggu Verifikasi',
    'DITOLAK'             => 'Ditolak',
    'LUNAS'               => 'Lunas',
    'BELUM_BAYAR'         => 'Belum Bayar',
    'SEMUA'               => 'Semua',
  ];
  $badge = [
    'BELUM_BAYAR'         => 'bg-stone-100 text-stone-700 border-stone-200',
    'MENUNGGU_VERIFIKASI' => 'bg-amber-100 text-amber-800 border-amber-200',
    'DITOLAK'             => 'bg-red-100 text-red-700 border-red-200',
    'LUNAS'               => 'bg-emerald-100 text-emerald-800 border-emerald-200',
  ];
@endphp

@section('isi')
<div class="flex flex-wrap gap-2">
  @foreach ($tab as $kode => $label)
    <a href="{{ route('admin.transfer.index', ['status' => $kode]) }}"
       class="px-4 py-2 rounded-full text-xs font-semibold border transition {{ $filter === $kode ? 'bg-maroon text-white border-maroon' : 'bg-white text-stone-700 border-warm hover:border-maroon hover:text-maroon' }}">
      {{ $label }} <span class="ml-1 opacity-80">({{ $hitung[$kode] ?? 0 }})</span>
    </a>
  @endforeach
</div>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-100">
        <tr>
          <th class="py-3.5 px-6">No. Pesanan</th>
          <th class="py-3.5 px-6">Pelanggan</th>
          <th class="py-3.5 px-6">Bank</th>
          <th class="py-3.5 px-6">Nominal</th>
          <th class="py-3.5 px-6">Bukti</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Tindakan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100 text-stone-700">
        @forelse ($pesanan as $p)
          @php $b = $buktiTerakhir[$p->id_pesanan] ?? null; $sb = $p->statusBayar(); @endphp
          <tr class="hover:bg-stone-50/70 transition">
            <td class="py-4 px-6 whitespace-nowrap">
              <span class="font-mono font-semibold text-stone-900">{{ $p->kode_pesanan }}</span>
              <span class="block text-[11px] text-stone-400">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</span>
            </td>
            <td class="py-4 px-6">{{ $p->pembeli->nama ?? '-' }}</td>
            <td class="py-4 px-6 font-semibold">{{ $p->bank_tujuan ?: '-' }}</td>
            <td class="py-4 px-6 font-semibold text-stone-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            <td class="py-4 px-6 whitespace-nowrap">
              @if ($b)
                <a href="{{ route('transfer.bukti', $b->id_bukti) }}" target="_blank" rel="noopener" class="text-maroon font-semibold hover:underline">Lihat bukti</a>
                <span class="block text-[11px] text-stone-400">{{ $b->diunggah_pada->format('d M Y, H:i') }}</span>
              @else
                <span class="text-stone-400">Belum ada</span>
              @endif
            </td>
            <td class="py-4 px-6">
              <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge[$sb] ?? $badge['BELUM_BAYAR'] }}">{{ strtoupper(\App\Models\Pesanan::STATUS_PEMBAYARAN[$sb] ?? $sb) }}</span>
            </td>
            <td class="py-4 px-6 text-right">
              <a href="{{ route('admin.transfer.show', $p->id_pesanan) }}" class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-semibold transition {{ $sb === 'MENUNGGU_VERIFIKASI' ? 'bg-maroon text-white hover:bg-maroon-dark' : 'bg-stone-100 text-stone-700 hover:bg-maroon hover:text-white' }}">{{ $sb === 'MENUNGGU_VERIFIKASI' ? 'Periksa' : 'Detail' }}</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="py-10 px-6 text-center text-sm text-stone-500">Tidak ada pembayaran transfer pada kategori ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
