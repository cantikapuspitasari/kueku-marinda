@extends('admin.layout', ['aktif' => 'transaksi'])

@section('title', 'Riwayat Transaksi')
@section('judul', 'Riwayat Transaksi')
@section('subjudul', 'Saring transaksi berdasarkan metode pembayaran dan status pesanan. Total nominal dihitung dari seluruh data yang sesuai filter.')

@php
  $badgeStatus = [
    'PENDING'      => 'bg-amber-100 text-amber-800 border-amber-200',
    'DIKONFIRMASI' => 'bg-sky-100 text-sky-800 border-sky-200',
    'DIPROSES'     => 'bg-indigo-100 text-indigo-800 border-indigo-200',
    'SELESAI'      => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'DIBATALKAN'   => 'bg-red-100 text-red-700 border-red-200',
  ];
  $adaFilter = $metode !== '' || $status !== '';
@endphp

@section('isi')
<section class="bg-white rounded-2xl border border-warm shadow-sm p-6">
  <form method="GET" action="{{ route('admin.transaksi.index') }}" class="flex flex-col md:flex-row md:items-end gap-4">
    <label class="md:w-60">
      <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Metode Pembayaran</span>
      <select name="metode" class="w-full rounded-xl border-warm text-sm focus:border-maroon focus:ring-maroon">
        <option value="">Semua metode</option>
        @foreach ($opsiMetode as $kode => $label)
          <option value="{{ $kode }}" @selected($metode === $kode)>{{ $label }}</option>
        @endforeach
      </select>
    </label>
    <label class="md:w-60">
      <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Status Pesanan</span>
      <select name="status" class="w-full rounded-xl border-warm text-sm focus:border-maroon focus:ring-maroon">
        <option value="">Semua status</option>
        @foreach ($opsiStatus as $kode => $label)
          <option value="{{ $kode }}" @selected($status === $kode)>{{ $label }}</option>
        @endforeach
      </select>
    </label>
    <div class="flex gap-2">
      <button type="submit" class="bg-maroon hover:bg-maroon-dark text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">Filter</button>
      <a href="{{ route('admin.transaksi.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-semibold border border-warm text-stone-600 hover:text-stone-900 bg-white transition">Reset</a>
    </div>
  </form>

  <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="rounded-xl bg-[#F6F3EE] px-5 py-4">
      <p class="text-xs font-bold uppercase tracking-wider text-stone-500">Jumlah Transaksi{{ $adaFilter ? ' (terfilter)' : '' }}</p>
      <p class="text-2xl font-bold font-serif text-stone-900 mt-1">{{ number_format($jumlah, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-xl bg-[#F6F3EE] px-5 py-4">
      <p class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Nominal{{ $adaFilter ? ' (terfilter)' : '' }}</p>
      <p class="text-2xl font-bold font-serif text-maroon mt-1">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
    </div>
  </div>
</section>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-100">
        <tr>
          <th class="py-3.5 px-6">No. Pesanan</th>
          <th class="py-3.5 px-6">Pelanggan</th>
          <th class="py-3.5 px-6">Metode Bayar</th>
          <th class="py-3.5 px-6">Status Pesanan</th>
          <th class="py-3.5 px-6 text-right">Total</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100 text-stone-700">
        @forelse ($pesanan as $p)
          <tr class="hover:bg-stone-50/70 transition">
            <td class="py-4 px-6 whitespace-nowrap">
              <span class="font-mono font-semibold text-stone-900">{{ $p->kode_pesanan }}</span>
              <span class="block text-[11px] text-stone-400">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</span>
            </td>
            <td class="py-4 px-6">{{ $p->pembeli->nama ?? '-' }}</td>
            <td class="py-4 px-6 whitespace-nowrap">
              {{ \App\Models\Pesanan::METODE_PEMBAYARAN[$p->metode_pembayaran ?? 'TUNAI'] ?? $p->metode_pembayaran }}{{ ($p->metode_pembayaran ?? '') === 'TRANSFER' && $p->bank_tujuan ? ' ' . $p->bank_tujuan : '' }}
            </td>
            <td class="py-4 px-6">
              <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badgeStatus[$p->status_pesanan] ?? 'bg-stone-100 text-stone-700 border-stone-200' }}">{{ strtoupper($opsiStatus[$p->status_pesanan] ?? $p->status_pesanan) }}</span>
            </td>
            <td class="py-4 px-6 text-right font-semibold text-stone-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="py-10 px-6 text-center text-sm text-stone-500">Tidak ada transaksi yang sesuai dengan filter.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if ($pesanan->hasPages())
    <div class="px-6 py-4 border-t border-stone-100">{{ $pesanan->links() }}</div>
  @endif
</section>
@endsection
