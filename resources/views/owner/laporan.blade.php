@extends('owner.layout', ['aktif' => 'laporan'])

@section('title', 'Laporan Penjualan')
@section('judul', 'Laporan Penjualan')
@section('subjudul', 'Analisis tren pendapatan, produk terlaris, dan rekap transaksi toko. Pesanan yang dibatalkan tidak dihitung.')

@section('aksi')
  <a href="{{ route('owner.laporan.unduh', ['periode' => $p]) }}" class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon-dark text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
    Unduh Laporan
  </a>
@endsection

@section('isi')
<section class="bg-white rounded-2xl p-6 border border-warm shadow-sm">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 gap-3 border-b border-[#F5F2ED]">
    <div>
      <span class="text-xs uppercase tracking-wider font-bold text-maroon">Tren Pendapatan • {{ $label }}</span>
      <h3 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Grafik Penjualan {{ ['minggu' => 'Mingguan', 'bulan' => 'Bulanan', 'tahun' => 'Tahunan'][$p] }}</h3>
    </div>
    <div class="inline-flex rounded-lg p-1 bg-[#F6F3EE] border border-warm">
      @foreach (['minggu' => 'Minggu Ini', 'bulan' => 'Bulan Ini', 'tahun' => 'Tahun Ini'] as $k => $nm)
        <a href="{{ route('owner.laporan', ['periode' => $k]) }}" class="px-3.5 py-1 text-xs rounded-md {{ $p === $k ? 'bg-white text-maroon font-bold shadow-sm' : 'text-stone-500 font-semibold hover:text-stone-900' }}">{{ $nm }}</a>
      @endforeach
    </div>
  </div>

  @php $tinggi = 190; @endphp
  <div class="pt-8 pb-4">
    <div class="relative h-64 flex items-end justify-between gap-2 sm:gap-5 px-4">
      <div class="absolute inset-x-0 top-0 border-b border-dashed border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">Rp {{ number_format($maks, 0, ',', '.') }}</span></div>
      <div class="absolute inset-x-0 top-1/2 border-b border-dashed border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">Rp {{ number_format($maks / 2, 0, ',', '.') }}</span></div>
      <div class="absolute inset-x-0 bottom-8 border-b border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">Rp 0</span></div>

      @foreach ($batang as $i => $b)
        @php
          $h = $b['nilai'] > 0 ? max(6, round($b['nilai'] / $maks * $tinggi)) : 2;
          $tertinggi = $puncak === $i;
        @endphp
        <div class="relative flex-1 flex flex-col items-center group z-10">
          @if ($tertinggi)
            <div class="mb-1.5 flex flex-col items-center">
              <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-rose-50 text-maroon border border-rose-100">Puncak</span>
              <span class="text-[11px] font-extrabold text-maroon mt-0.5 whitespace-nowrap">Rp {{ number_format($b['nilai'], 0, ',', '.') }}</span>
            </div>
          @elseif ($b['nilai'] > 0)
            <span class="text-[11px] font-bold text-stone-600 mb-2 group-hover:text-maroon whitespace-nowrap">Rp {{ number_format($b['nilai'], 0, ',', '.') }}</span>
          @endif
          <div class="w-full max-w-[56px] rounded-t-lg transition-all duration-200 {{ $tertinggi ? 'bg-maroon shadow-md ring-2 ring-maroon/20' : 'bg-[#E8C4C4] group-hover:bg-maroon' }}" style="height: {{ $h }}px;"></div>
          <span class="text-xs mt-3 {{ $tertinggi ? 'font-bold text-maroon' : 'font-semibold text-stone-500' }}">{{ $b['label'] }}</span>
        </div>
      @endforeach
    </div>
    @if ($ringkas['jumlah'] === 0)
      <p class="text-center text-sm text-stone-500 mt-4">Belum ada penjualan pada periode ini.</p>
    @endif
  </div>
</section>

<div>
  <div class="mb-4">
    <h3 class="font-serif text-2xl font-bold text-stone-900">Ringkasan</h3>
    <p class="text-sm text-stone-500">Parameter utama kinerja penjualan {{ $namaIni }}</p>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Pendapatan</span>
          <span class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center text-maroon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
        </div>
        <p class="font-serif text-3xl font-bold text-stone-900 mt-3">Rp {{ number_format($ringkas['total'], 0, ',', '.') }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-semibold {{ is_null($ringkas['pertumbuhan']) ? 'text-stone-500' : ($ringkas['pertumbuhan'] >= 0 ? 'text-emerald-700' : 'text-rose-600') }}">
        @if (is_null($ringkas['pertumbuhan']))
          Belum ada pembanding {{ $namaLalu }}
        @else
          {{ $ringkas['pertumbuhan'] >= 0 ? '+' : '' }}{{ $ringkas['pertumbuhan'] }}% dibanding {{ $namaLalu }}
        @endif
      </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Produk Terlaris</span>
          <span class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg></span>
        </div>
        <p class="font-serif text-xl font-bold text-stone-900 mt-3 leading-snug">{{ $terlaris->first()->nama_produk ?? 'Belum ada' }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] flex items-center text-xs font-medium text-stone-500">
        <span class="w-2 h-2 rounded-full bg-maroon mr-2"></span>
        <span>{{ $terlaris->first() ? (int) $terlaris->first()->terjual . ' porsi terjual' : 'Belum ada penjualan' }}</span>
      </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Rata-rata Transaksi</span>
          <span class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center text-sky-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></span>
        </div>
        <div class="flex items-baseline gap-1 mt-3"><p class="font-serif text-3xl font-bold text-stone-900">Rp {{ number_format($ringkas['rata'], 0, ',', '.') }}</p><span class="text-xs text-stone-500 font-medium">/pesanan</span></div>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] flex items-center text-xs font-medium text-stone-500">
        <span class="w-2 h-2 rounded-full bg-emerald-600 mr-2"></span>
        <span>Dari {{ $ringkas['jumlah'] }} pesanan {{ $namaIni }}</span>
      </div>
    </div>
  </div>
</div>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100">
    <h3 class="text-lg font-bold font-serif text-stone-900">5 Produk Terlaris</h3>
    <p class="text-xs text-stone-500">Berdasarkan jumlah terjual pada {{ $namaIni }}</p>
  </div>
  <ul class="divide-y divide-stone-100">
    @forelse ($terlaris as $i => $t)
      <li class="px-6 py-3.5 flex items-center gap-4">
        <span class="w-7 h-7 rounded-full {{ $i === 0 ? 'bg-maroon text-white' : 'bg-stone-100 text-stone-600' }} text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
        <div class="w-11 h-11 rounded-lg bg-stone-100 overflow-hidden border border-stone-200 flex-shrink-0">@if ($t->foto_produk)<img src="{{ asset('images/produk/' . $t->foto_produk) }}" alt="" class="w-full h-full object-cover">@endif</div>
        <p class="flex-1 min-w-0 text-sm font-semibold text-stone-800 truncate">{{ $t->nama_produk }}</p>
        <span class="text-xs text-stone-500">{{ (int) $t->terjual }} porsi</span>
        <span class="text-sm font-bold text-stone-900 w-32 text-right">Rp {{ number_format($t->omzet, 0, ',', '.') }}</span>
      </li>
    @empty
      <li class="px-6 py-10 text-center text-sm text-stone-500">Belum ada penjualan pada periode ini.</li>
    @endforelse
  </ul>
</section>
@endsection
