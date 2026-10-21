@extends('admin.layout', ['aktif' => 'dashboard'])

@section('title', 'Dashboard Admin')
@section('judul', 'Dashboard Admin')
@section('subjudul', 'Pantau ringkasan penjualan, aktivitas pesanan hari ini, dan jalan pintas manajemen toko.')

@php
  $statusMap = [
    'PENDING'      => ['Perlu Konfirmasi', 'bg-rose-100 text-maroon'],
    'DIKONFIRMASI' => ['Terverifikasi',    'bg-indigo-100 text-indigo-800'],
    'DIPROSES'     => ['Diproses',         'bg-amber-100 text-amber-800'],
    'SELESAI'      => ['Selesai',          'bg-emerald-100 text-emerald-800'],
    'DIBATALKAN'   => ['Dibatalkan',       'bg-stone-200 text-stone-700'],
  ];
@endphp

@section('isi')
<!-- Statistik -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Pesanan Hari Ini</span>
        <span class="p-2 bg-amber-50 text-amber-700 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </span>
      </div>
      <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stat['hari_ini'] }} Pesanan</div>
    </div>
    <div class="mt-4 pt-3 border-t border-stone-100 text-xs font-medium {{ ($stat['selisih'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
      @if (!is_null($stat['selisih']))
        {{ $stat['selisih'] >= 0 ? '+' : '' }}{{ $stat['selisih'] }}% dari kemarin •
      @endif
      <span class="text-stone-500 font-normal">{{ $stat['menunggu'] }} menunggu verifikasi</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Pendapatan</span>
        <span class="p-2 text-maroon rounded-lg bg-maroon/10">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </span>
      </div>
      <div class="text-3xl font-bold text-maroon tracking-tight">Rp {{ number_format($stat['pendapatan'], 0, ',', '.') }}</div>
    </div>
    <div class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500">Dari pesanan hari ini (tidak termasuk yang dibatalkan)</div>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Produk Tersedia</span>
        <span class="p-2 bg-stone-100 text-stone-700 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </span>
      </div>
      <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stat['produk_aktif'] }} Menu</div>
    </div>
    <div class="mt-4 pt-3 border-t border-stone-100 flex items-center text-xs text-stone-500">
      <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
      Tampil di toko pelanggan • {{ $stat['stok_habis'] }} stok habis
    </div>
  </div>
</section>

<!-- Menu cepat -->
<section>
  <div class="mb-4 flex items-center justify-between">
    <h3 class="text-xl font-bold font-serif text-stone-800">Menu Cepat</h3>
    <span class="text-xs text-stone-500 font-medium">Akses operasional instan</span>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:border-maroon/40 hover:shadow-md transition group flex flex-col justify-between">
      <div>
        <div class="w-12 h-12 rounded-xl bg-maroon-light text-maroon flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </div>
        <h4 class="text-lg font-bold text-stone-800 group-hover:text-maroon transition-colors">Kelola Produk</h4>
        <p class="text-xs text-stone-500 mt-1 leading-relaxed">Tambah produk baru, ubah harga dan foto, atur stok persediaan, atau sembunyikan menu.</p>
      </div>
      <a href="{{ route('admin.produk.index') }}" class="mt-6 inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-maroon bg-maroon-light hover:bg-maroon hover:text-white transition shadow-sm">Buka Kelola Produk
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:border-maroon/40 hover:shadow-md transition group flex flex-col justify-between">
      <div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 10h16M4 14h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </div>
        <h4 class="text-lg font-bold text-stone-800 group-hover:text-maroon transition-colors">Kelola Kategori</h4>
        <p class="text-xs text-stone-500 mt-1 leading-relaxed">Atur kategori etalase. Kategori menjadi tab menu di beranda pelanggan.</p>
      </div>
      <a href="{{ route('admin.kategori.index') }}" class="mt-6 inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-stone-700 bg-stone-100 hover:bg-stone-800 hover:text-white transition shadow-sm">Buka Kelola Kategori
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:border-maroon/40 hover:shadow-md transition group flex flex-col justify-between">
      <div>
        <div class="w-12 h-12 rounded-xl text-maroon flex items-center justify-center mb-4 group-hover:scale-105 transition-transform relative bg-maroon/10">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          @if ($stat['menunggu'] > 0)<span class="absolute -top-1 -right-1 w-3 h-3 bg-maroon rounded-full border-2 border-white"></span>@endif
        </div>
        <h4 class="text-lg font-bold text-stone-800 group-hover:text-maroon transition-colors">Pesanan Masuk</h4>
        <p class="text-xs text-stone-500 mt-1 leading-relaxed">Tinjau pesanan pelanggan, verifikasi, dan perbarui status pesanan.</p>
      </div>
      <a href="{{ route('admin.pesanan') }}" class="mt-6 inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-maroon hover:bg-maroon-dark transition shadow-sm">Buka Pesanan Masuk
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>
  </div>
</section>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
  <!-- Pesanan terbaru -->
  <section class="xl:col-span-2 bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
    <div class="p-6 border-b border-stone-100 flex flex-wrap items-center justify-between gap-2">
      <div>
        <h3 class="text-lg font-bold font-serif text-stone-800">Ringkasan Pesanan Terbaru</h3>
        <p class="text-xs text-stone-500">5 pesanan terakhir dari pelanggan</p>
      </div>
      <a class="text-xs font-semibold text-maroon hover:text-maroon-dark transition" href="{{ route('admin.pesanan') }}">Lihat Semua Pesanan →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-100">
          <tr>
            <th class="py-3.5 px-6">No. Pesanan</th>
            <th class="py-3.5 px-6">Pelanggan</th>
            <th class="py-3.5 px-6">Detail Kue</th>
            <th class="py-3.5 px-6">Total</th>
            <th class="py-3.5 px-6">Status</th>
            <th class="py-3.5 px-6 text-right">Tindakan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-stone-100 text-stone-700">
          @forelse ($pesananTerbaru as $p)
            @php
              $nama  = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
              $first = $p->detailPesanan->first();
              $extra = $p->detailPesanan->count() - 1;
              $st    = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700'];
            @endphp
            <tr class="hover:bg-stone-50/70 transition">
              <td class="py-4 px-6 font-semibold text-stone-900 whitespace-nowrap">{{ $p->kode_pesanan }}</td>
              <td class="py-4 px-6">
                <div class="font-medium text-stone-800">{{ $nama }}</div>
                <div class="text-[11px] text-stone-400">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</div>
              </td>
              <td class="py-4 px-6">
                <span>{{ $first->produk->nama_produk ?? '-' }}</span>
                <span class="block text-[11px] text-stone-400">{{ $extra > 0 ? '+ ' . $extra . ' produk lainnya' : $p->detailPesanan->sum('jumlah') . ' item' }} • {{ $p->jenis_pengambilan === 'DELIVERY' ? 'Diantar' : 'Ambil di toko' }}</span>
              </td>
              <td class="py-4 px-6 font-semibold text-stone-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
              <td class="py-4 px-6"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium {{ $st[1] }}">{{ $st[0] }}</span></td>
              <td class="py-4 px-6 text-right">
                <a href="{{ route('admin.pesanan', ['id' => $p->id_pesanan]) }}" class="inline-block px-3 py-1.5 rounded-lg text-[11px] font-semibold transition {{ $p->status_pesanan === 'PENDING' ? 'bg-maroon text-white hover:bg-maroon-dark' : 'bg-stone-100 text-stone-700 hover:bg-maroon hover:text-white' }}">{{ $p->status_pesanan === 'PENDING' ? 'Verifikasi' : 'Detail' }}</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada pesanan masuk.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <!-- Stok menipis -->
  <section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
    <div class="p-6 border-b border-stone-100">
      <h3 class="text-lg font-bold font-serif text-stone-800">Stok Menipis</h3>
      <p class="text-xs text-stone-500">Produk dengan stok 5 atau kurang</p>
    </div>
    <ul class="divide-y divide-stone-100">
      @forelse ($stokMenipis as $m)
        <li class="px-6 py-3.5 flex items-center gap-3">
          <div class="w-11 h-11 rounded-lg bg-stone-100 overflow-hidden flex-shrink-0 border border-stone-200">
            @if ($m->foto_produk)<img src="{{ asset('images/produk/' . $m->foto_produk) }}" alt="" class="w-full h-full object-cover">@endif
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-stone-800 truncate">{{ $m->nama_produk }}</p>
            <p class="text-[11px] {{ $m->stok == 0 ? 'text-rose-600 font-semibold' : 'text-amber-700' }}">{{ $m->stok == 0 ? 'Stok habis' : 'Sisa ' . $m->stok . ' pcs' }}</p>
          </div>
          <a href="{{ route('admin.produk.edit', $m->id_produk) }}" class="text-xs font-semibold text-maroon hover:text-maroon-dark">Isi stok</a>
        </li>
      @empty
        <li class="px-6 py-10 text-center text-sm text-stone-500">Semua stok aman.</li>
      @endforelse
    </ul>
  </section>
</div>
@endsection
