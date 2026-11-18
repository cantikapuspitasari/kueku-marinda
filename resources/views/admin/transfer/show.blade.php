@extends('admin.layout', ['aktif' => 'transfer'])

@section('title', 'Verifikasi Transfer')
@section('judul', 'Verifikasi Transfer ' . $pesanan->kode_pesanan)
@section('subjudul', 'Cocokkan nominal, bank tujuan, dan bukti transfer sebelum menerima.')
@section('aksi')
  <a href="{{ route('admin.transfer.index') }}" class="px-4 py-2 rounded-xl border border-warm bg-white text-xs font-semibold text-stone-700 hover:border-maroon hover:text-maroon transition">&larr; Daftar Transfer</a>
@endsection

@php
  $sb = $pesanan->statusBayar();
  $badge = [
    'BELUM_BAYAR'         => 'bg-stone-100 text-stone-700 border-stone-200',
    'MENUNGGU_VERIFIKASI' => 'bg-amber-100 text-amber-800 border-amber-200',
    'DITOLAK'             => 'bg-red-100 text-red-700 border-red-200',
    'LUNAS'               => 'bg-emerald-100 text-emerald-800 border-emerald-200',
  ];
  $bisaVerifikasi = $bukti && $bukti->status === 'MENUNGGU' && $sb === 'MENUNGGU_VERIFIKASI' && $pesanan->status_pesanan !== 'DIBATALKAN';
@endphp

@section('isi')
<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

  {{-- Detail pesanan --}}
  <section class="lg:col-span-2 bg-white rounded-2xl border border-warm shadow-sm p-6 space-y-4 text-sm">
    <div class="flex items-center justify-between gap-3">
      <h3 class="font-serif font-bold text-lg text-stone-900">Detail Pesanan</h3>
      <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge[$sb] ?? $badge['BELUM_BAYAR'] }}">{{ strtoupper(\App\Models\Pesanan::STATUS_PEMBAYARAN[$sb] ?? $sb) }}</span>
    </div>
    <dl class="space-y-2">
      <div class="flex justify-between gap-3"><dt class="text-stone-500">Kode pesanan</dt><dd class="font-mono font-semibold">{{ $pesanan->kode_pesanan }}</dd></div>
      <div class="flex justify-between gap-3"><dt class="text-stone-500">Pelanggan</dt><dd class="font-semibold text-right">{{ $pesanan->pembeli->nama ?? '-' }}</dd></div>
      <div class="flex justify-between gap-3"><dt class="text-stone-500">Status pesanan</dt><dd class="font-semibold">{{ $pesanan->status_pesanan }}</dd></div>
      <div class="flex justify-between gap-3"><dt class="text-stone-500">Bank tujuan</dt><dd class="font-semibold text-right">{{ $bank['nama'] ?? '-' }}<span class="block font-mono text-xs text-stone-500">{{ $bank['rekening'] ?? '' }}</span></dd></div>
      <div class="flex justify-between gap-3 pt-2 border-t border-stone-100"><dt class="text-stone-500">Nominal yang harus dibayar</dt><dd class="font-bold text-maroon text-lg">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd></div>
    </dl>
    <ul class="divide-y divide-stone-100 border border-stone-100 rounded-xl">
      @foreach ($pesanan->detailPesanan as $d)
        <li class="px-3 py-2 flex justify-between gap-3 text-xs">
          <span>{{ $d->produk->nama_produk ?? 'Produk' }} &times; {{ $d->jumlah }}</span>
          <span class="font-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
        </li>
      @endforeach
    </ul>
  </section>

  {{-- Bukti + keputusan --}}
  <section class="lg:col-span-3 bg-white rounded-2xl border border-warm shadow-sm p-6 space-y-5">
    <h3 class="font-serif font-bold text-lg text-stone-900">Bukti Transfer</h3>

    @if ($bukti)
      <a href="{{ route('transfer.bukti', $bukti->id_bukti) }}" target="_blank" rel="noopener" class="block">
        <img src="{{ route('transfer.bukti', $bukti->id_bukti) }}" alt="Bukti transfer" class="mx-auto max-h-[28rem] rounded-xl border border-warm object-contain bg-stone-50">
      </a>
      <p class="text-center text-[11px] text-stone-400">Diunggah {{ $bukti->diunggah_pada->format('d M Y, H:i') }} &middot; klik gambar untuk memperbesar</p>
    @else
      <p class="text-sm text-stone-500 bg-stone-50 border border-stone-100 rounded-xl px-4 py-6 text-center">Pembeli belum mengunggah bukti transfer.</p>
    @endif

    @if ($bisaVerifikasi)
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
        <form method="POST" action="{{ route('admin.transfer.terima', $bukti->id_bukti) }}" onsubmit="return confirm('Terima pembayaran ini? Status pesanan menjadi LUNAS.')" class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50 space-y-3">
          @csrf
          <h4 class="text-sm font-bold text-emerald-800">Terima pembayaran</h4>
          <p class="text-xs text-stone-600">Pastikan bukti terbaca jelas dan nominal sesuai (Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}).</p>
          <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition">Terima &amp; Tandai LUNAS</button>
        </form>

        <form method="POST" action="{{ route('admin.transfer.tolak', $bukti->id_bukti) }}" class="p-4 rounded-xl border border-red-200 bg-red-50/50 space-y-3">
          @csrf
          <h4 class="text-sm font-bold text-red-700">Tolak pembayaran</h4>
          <textarea name="catatan_admin" rows="2" required minlength="5" maxlength="255" placeholder="Alasan penolakan (wajib), contoh: nominal tidak sesuai / gambar buram"
                    class="w-full text-xs border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon">{{ old('catatan_admin') }}</textarea>
          <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">Tolak Bukti</button>
        </form>
      </div>
    @elseif ($sb === 'LUNAS')
      <p class="text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">Pembayaran sudah LUNAS{{ $pesanan->waktu_bayar ? ' (' . $pesanan->waktu_bayar->format('d M Y, H:i') . ')' : '' }}. Lanjutkan di <a href="{{ route('admin.pesanan') }}" class="underline font-semibold">Pesanan Masuk</a>.</p>
    @elseif ($sb === 'DITOLAK')
      <p class="text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-3">Bukti ditolak. Menunggu pembeli mengunggah ulang.</p>
    @elseif ($pesanan->status_pesanan === 'DIBATALKAN')
      <p class="text-sm text-stone-600 bg-stone-100 border border-stone-200 rounded-xl px-4 py-3">Pesanan dibatalkan, tidak bisa diverifikasi.</p>
    @endif

    {{-- Riwayat --}}
    @if ($riwayat->count())
      <div class="pt-2 border-t border-stone-100">
        <h4 class="text-sm font-bold text-stone-800 mb-2">Riwayat verifikasi</h4>
        <ul class="space-y-2 text-xs">
          @foreach ($riwayat as $r)
            <li class="flex items-start justify-between gap-3">
              <div>
                <span class="text-stone-700">{{ $r->diunggah_pada->format('d M Y, H:i') }}</span>
                @if ($r->diverifikasi_pada)<span class="text-stone-400"> &middot; diverifikasi {{ $r->diverifikasi_pada->format('d M Y, H:i') }}@if ($r->verifikator) oleh {{ $r->verifikator->nama }}@endif</span>@endif
                @if ($r->catatan_admin)<span class="block text-red-600">Alasan: {{ $r->catatan_admin }}</span>@endif
              </div>
              <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $r->status === 'DITERIMA' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($r->status === 'DITOLAK' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-800 border-amber-200') }}">{{ $r->status }}</span>
            </li>
          @endforeach
        </ul>
      </div>
    @endif
  </section>
</div>
@endsection
