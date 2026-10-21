@extends('owner.layout', ['aktif' => 'dashboard'])

@section('title', 'Dashboard Owner')
@section('judul', 'Dashboard Owner')
@section('subjudul', 'Ringkasan performa bisnis, pantauan penjualan, dan akses cepat toko.')

@php
  $statusMap = [
    'PENDING'      => ['Menunggu Verifikasi', 'bg-amber-50 text-amber-800 border-amber-200'],
    'DIKONFIRMASI' => ['Terverifikasi',       'bg-indigo-50 text-indigo-800 border-indigo-200'],
    'DIPROSES'     => ['Diproses',            'bg-blue-50 text-blue-800 border-blue-200'],
    'SELESAI'      => ['Selesai',             'bg-emerald-50 text-emerald-800 border-emerald-200'],
    'DIBATALKAN'   => ['Dibatalkan',          'bg-rose-50 text-rose-800 border-rose-200'],
  ];
@endphp

@section('isi')
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Penjualan Hari Ini</span>
      <span class="w-9 h-9 rounded-xl bg-maroon/10 flex items-center justify-center text-maroon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </span>
    </div>
    <p class="text-3xl font-extrabold text-stone-900 tracking-tight mt-4">Rp {{ number_format($stat['omzet'], 0, ',', '.') }}</p>
    <p class="mt-2.5 text-xs text-stone-500">
      @if (!is_null($stat['selisih']))
        <span class="font-bold px-2 py-0.5 rounded-md {{ $stat['selisih'] >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' }}">{{ $stat['selisih'] >= 0 ? '↑ +' : '↓ ' }}{{ $stat['selisih'] }}%</span> dari kemarin
      @else
        Pesanan yang tidak dibatalkan
      @endif
    </p>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Pesanan Masuk</span>
      <span class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </span>
    </div>
    <p class="text-3xl font-extrabold text-stone-900 tracking-tight mt-4">{{ $stat['masuk'] }} Pesanan</p>
    <p class="mt-2.5 text-xs text-stone-500 flex items-center gap-2">
      <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>{{ $stat['selesai'] }} Selesai
      <span class="text-stone-300">•</span>
      <span class="font-semibold text-amber-700">{{ $stat['proses'] }} Dalam Proses</span>
    </p>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <span class="text-xs font-bold uppercase tracking-wider text-stone-400">Akun Aktif</span>
      <span class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </span>
    </div>
    <p class="text-3xl font-extrabold text-stone-900 tracking-tight mt-4">{{ $stat['admin'] + $stat['kurir'] }} Staf Aktif</p>
    <p class="mt-2.5 text-xs text-stone-500">{{ $stat['admin'] }} Admin • {{ $stat['kurir'] }} Kurir</p>
  </div>
</section>

<section>
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-xl font-bold font-serif text-stone-900">Menu Cepat</h3>
    <span class="text-xs text-stone-400">Pintasan kontrol operasional utama</span>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @php
      $pintas = [
        ['Monitoring Toko', 'Pantau antrean pesanan harian dan status pesanan pelanggan secara langsung.', 'Buka Monitoring Toko', route('owner.monitoring'), 'bg-orange-50 border-orange-100 text-orange-700',
          'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
        ['Laporan Penjualan', 'Lihat grafik omzet, produk terlaris, dan unduh rekap penjualan.', 'Buka Laporan Penjualan', route('owner.laporan'), 'bg-emerald-50 border-emerald-100 text-emerald-700',
          'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
        ['Kelola Akun', 'Buat akun Admin dan Kurir, reset kata sandi, aktifkan atau nonaktifkan akun.', 'Buka Kelola Akun', route('owner.akun'), 'bg-purple-50 border-purple-100 text-purple-700',
          'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
      ];
    @endphp
    @foreach ($pintas as [$judul, $isi, $tombol, $url, $warna, $ikon])
      <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between hover:border-maroon/30 hover:shadow-md transition group">
        <div>
          <div class="w-11 h-11 rounded-xl border {{ $warna }} flex items-center justify-center mb-4 group-hover:bg-maroon group-hover:text-white group-hover:border-maroon transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $ikon }}" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          </div>
          <h4 class="text-base font-bold text-stone-900 group-hover:text-maroon transition-colors">{{ $judul }}</h4>
          <p class="text-xs text-stone-500 mt-2 leading-relaxed">{{ $isi }}</p>
        </div>
        <div class="mt-6 pt-4 border-t border-stone-100">
          <a href="{{ $url }}" class="inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl bg-stone-100 group-hover:bg-maroon text-stone-700 group-hover:text-white text-xs font-bold transition-colors">{{ $tombol }} <span class="ml-1.5">→</span></a>
        </div>
      </div>
    @endforeach
  </div>
</section>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100 flex flex-wrap items-center justify-between gap-2">
    <div>
      <h3 class="text-lg font-bold font-serif text-stone-900">Ringkasan Aktivitas &amp; Pesanan Terkini</h3>
      <p class="text-xs text-stone-500 mt-0.5">5 transaksi terbaru dari pelanggan</p>
    </div>
    <a class="text-xs font-bold text-maroon hover:underline" href="{{ route('owner.monitoring') }}">Lihat Semua Pesanan →</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead>
        <tr class="bg-stone-50/70 border-b border-stone-200/80 text-[11px] uppercase tracking-wider font-bold text-stone-500">
          <th class="py-3.5 px-6">No. Pesanan</th>
          <th class="py-3.5 px-6">Pelanggan</th>
          <th class="py-3.5 px-6">Detail Kue</th>
          <th class="py-3.5 px-6">Total Tagihan</th>
          <th class="py-3.5 px-6">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100 font-medium text-stone-700">
        @forelse ($pesananTerbaru as $p)
          @php
            $nama = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
            $first = $p->detailPesanan->first();
            $extra = $p->detailPesanan->count() - 1;
            $st = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700 border-stone-200'];
          @endphp
          <tr class="hover:bg-stone-50/50 transition-colors">
            <td class="py-4 px-6 font-bold text-stone-900 whitespace-nowrap">{{ $p->kode_pesanan }}</td>
            <td class="py-4 px-6">
              <p class="font-bold text-stone-900">{{ $nama }}</p>
              <p class="text-[11px] text-stone-400">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</p>
            </td>
            <td class="py-4 px-6">
              <p class="font-semibold text-stone-800">{{ $first->produk->nama_produk ?? '-' }}</p>
              <p class="text-[11px] text-stone-400">{{ $extra > 0 ? '+ ' . $extra . ' produk lainnya' : $p->detailPesanan->sum('jumlah') . ' item' }} • {{ $p->jenis_pengambilan === 'DELIVERY' ? 'Diantar' : 'Ambil di toko' }}</p>
            </td>
            <td class="py-4 px-6 font-bold text-stone-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            <td class="py-4 px-6"><span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $st[1] }}">{{ $st[0] }}</span></td>
          </tr>
        @empty
          <tr><td colspan="5" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada pesanan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
