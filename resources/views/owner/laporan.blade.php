@extends('owner.layout', ['aktif' => 'laporan'])

@section('title', 'Laporan Penjualan')
@section('judul', 'Laporan Penjualan')
@section('subjudul', 'Jumlah pesanan, produk terlaris, dan rekap penjualan toko per hari, minggu, dan bulan. Pesanan yang dibatalkan tidak dihitung.')

@section('aksi')
  <a href="{{ route('owner.laporan.unduh', array_filter(['periode' => $p, 'tanggal' => $tanggalDiminta])) }}" class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon-dark text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
    Unduh Excel (.xlsx)
  </a>
@endsection

@section('isi')
@php
  $jumlahBatang = count($batang);
  $gapBatang = $jumlahBatang > 14 ? 'gap-0.5 sm:gap-1' : ($jumlahBatang > 7 ? 'gap-1 sm:gap-2' : 'gap-2 sm:gap-5');
  $tinggi = 190;
@endphp

<section class="bg-white rounded-2xl p-6 border border-warm shadow-sm">
  <div class="flex flex-col lg:flex-row lg:items-center justify-between pb-6 gap-4 border-b border-[#F5F2ED]">
    <div>
      <span class="text-xs uppercase tracking-wider font-bold text-maroon">Total Pesanan • {{ $label }}</span>
      <h3 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Grafik Jumlah Pesanan {{ $namaPeriode }}</h3>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      {{-- Pilih periode: Harian / Mingguan / Bulanan --}}
      <div class="inline-flex rounded-lg p-1 bg-[#F6F3EE] border border-warm">
        @foreach (['hari' => 'Harian', 'minggu' => 'Mingguan', 'bulan' => 'Bulanan'] as $k => $nm)
          <a href="{{ route('owner.laporan', array_filter(['periode' => $k, 'tanggal' => $tanggalDiminta])) }}" class="px-3.5 py-1 text-xs rounded-md {{ $p === $k ? 'bg-white text-maroon font-bold shadow-sm' : 'text-stone-500 font-semibold hover:text-stone-900' }}">{{ $nm }}</a>
        @endforeach
      </div>

      {{-- Pindah periode / pilih tanggal --}}
      <form method="GET" action="{{ route('owner.laporan') }}" class="inline-flex items-center gap-1">
        <input type="hidden" name="periode" value="{{ $p }}">
        <a href="{{ route('owner.laporan', ['periode' => $p, 'tanggal' => $tanggalPrev]) }}" class="w-8 h-8 rounded-lg border border-warm bg-[#F6F3EE] flex items-center justify-center text-stone-600 hover:text-maroon" title="Sebelumnya">&lsaquo;</a>
        <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="h-8 rounded-lg border border-warm bg-[#F6F3EE] text-xs font-semibold text-stone-700 py-0 px-2">
        <a href="{{ route('owner.laporan', ['periode' => $p, 'tanggal' => $tanggalNext]) }}" class="w-8 h-8 rounded-lg border border-warm bg-[#F6F3EE] flex items-center justify-center text-stone-600 hover:text-maroon" title="Berikutnya">&rsaquo;</a>
      </form>
    </div>
  </div>

  <div class="pt-8 pb-4">
    <div class="relative h-64 flex items-end justify-between {{ $gapBatang }} px-4 pr-12">
      <div class="absolute inset-x-0 top-0 border-b border-dashed border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">{{ $skala }} pesanan</span></div>
      <div class="absolute inset-x-0 top-1/2 border-b border-dashed border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">{{ $skala / 2 }}</span></div>
      <div class="absolute inset-x-0 bottom-8 border-b border-warm flex justify-end"><span class="text-[11px] text-stone-400 -mt-2.5 bg-white px-1">0</span></div>

      @foreach ($batang as $i => $b)
        @php
          $h = $b['nilai'] > 0 ? max(6, round($b['nilai'] / $skala * $tinggi)) : 2;
          $tertinggi = $puncak === $i;
        @endphp
        <div class="relative flex-1 flex flex-col items-center group z-10 min-w-0" title="{{ $b['label'] }}: {{ $b['nilai'] }} pesanan">
          @if ($tertinggi)
            <div class="mb-1.5 flex flex-col items-center">
              @if ($jumlahBatang <= 14)<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-rose-50 text-maroon border border-rose-100">Puncak</span>@endif
              <span class="text-[11px] font-extrabold text-maroon mt-0.5 whitespace-nowrap">{{ $b['nilai'] }}</span>
            </div>
          @elseif ($b['nilai'] > 0)
            <span class="text-[11px] font-bold text-stone-600 mb-2 group-hover:text-maroon whitespace-nowrap">{{ $b['nilai'] }}</span>
          @endif
          <div class="w-full max-w-[56px] rounded-t-lg transition-all duration-200 {{ $tertinggi ? 'bg-maroon shadow-md ring-2 ring-maroon/20' : 'bg-[#E8C4C4] group-hover:bg-maroon' }}" style="height: {{ $h }}px;"></div>
          <span class="text-[10px] sm:text-xs mt-3 whitespace-nowrap {{ $tertinggi ? 'font-bold text-maroon' : 'font-semibold text-stone-500' }}">{{ $b['label'] }}</span>
        </div>
      @endforeach
    </div>
    @if ($ringkas['jumlah'] === 0)
      <p class="text-center text-sm text-stone-500 mt-4">Belum ada pesanan pada periode ini.</p>
    @endif
  </div>
</section>

<div>
  <div class="mb-4">
    <h3 class="font-serif text-2xl font-bold text-stone-900">Ringkasan</h3>
    <p class="text-sm text-stone-500">Parameter utama kinerja penjualan {{ $label }}</p>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Total pesanan --}}
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Pesanan</span>
          <span class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center text-maroon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></span>
        </div>
        <div class="flex items-baseline gap-1 mt-3"><p class="font-serif text-3xl font-bold text-stone-900">{{ number_format($ringkas['jumlah'], 0, ',', '.') }}</p><span class="text-xs text-stone-500 font-medium">pesanan</span></div>
        <p class="text-xs text-stone-500 mt-1">{{ number_format($ringkas['item'], 0, ',', '.') }} porsi produk terjual</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-semibold {{ is_null($ringkas['pertumbuhan_pesanan']) ? 'text-stone-500' : ($ringkas['pertumbuhan_pesanan'] >= 0 ? 'text-emerald-700' : 'text-rose-600') }}">
        @if (is_null($ringkas['pertumbuhan_pesanan']))
          Belum ada pembanding {{ $namaLalu }}
        @else
          {{ $ringkas['pertumbuhan_pesanan'] >= 0 ? '+' : '' }}{{ $ringkas['pertumbuhan_pesanan'] }}% dibanding {{ $namaLalu }} ({{ $ringkas['jumlah_lalu'] }} pesanan)
        @endif
      </div>
    </div>

    {{-- Produk terlaris --}}
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Produk Terlaris</span>
          <span class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg></span>
        </div>
        <p class="font-serif text-xl font-bold text-stone-900 mt-3 leading-snug">{{ $terlaris[0]['nama'] ?? 'Belum ada' }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] flex items-center text-xs font-medium text-stone-500">
        <span class="w-2 h-2 rounded-full bg-maroon mr-2"></span>
        <span>{{ isset($terlaris[0]) ? $terlaris[0]['terjual'] . ' porsi terjual dalam ' . $terlaris[0]['pesanan'] . ' pesanan' : 'Belum ada penjualan' }}</span>
      </div>
    </div>

    {{-- Total pendapatan --}}
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Pendapatan</span>
          <span class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
        </div>
        <p class="font-serif text-3xl font-bold text-stone-900 mt-3">Rp {{ number_format($ringkas['total'], 0, ',', '.') }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-semibold {{ is_null($ringkas['pertumbuhan_omzet']) ? 'text-stone-500' : ($ringkas['pertumbuhan_omzet'] >= 0 ? 'text-emerald-700' : 'text-rose-600') }}">
        @if (is_null($ringkas['pertumbuhan_omzet']))
          Belum ada pembanding {{ $namaLalu }}
        @else
          {{ $ringkas['pertumbuhan_omzet'] >= 0 ? '+' : '' }}{{ $ringkas['pertumbuhan_omzet'] }}% dibanding {{ $namaLalu }}
        @endif
      </div>
    </div>

    {{-- Rata-rata transaksi --}}
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
        <span>Dari {{ $ringkas['jumlah'] }} pesanan</span>
      </div>
    </div>
  </div>
</div>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100">
    <h3 class="text-lg font-bold font-serif text-stone-900">Grafik 5 Produk Terlaris</h3>
    <p class="text-xs text-stone-500">Berdasarkan jumlah porsi terjual • {{ $label }}</p>
  </div>
  <ul class="divide-y divide-stone-100">
    @forelse ($terlaris as $i => $t)
      <li class="px-6 py-4 flex items-center gap-4">
        <span class="w-7 h-7 rounded-full {{ $i === 0 ? 'bg-maroon text-white' : 'bg-stone-100 text-stone-600' }} text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $i + 1 }}</span>
        <div class="w-11 h-11 rounded-lg bg-stone-100 overflow-hidden border border-stone-200 flex-shrink-0">@if ($t['foto'])<img src="{{ asset('images/produk/' . $t['foto']) }}" alt="" class="w-full h-full object-cover">@endif</div>
        <div class="flex-1 min-w-0">
          <div class="flex items-baseline justify-between gap-3">
            <p class="text-sm font-semibold text-stone-800 truncate">{{ $t['nama'] }}</p>
            <span class="text-sm font-bold text-stone-900 whitespace-nowrap">{{ $t['terjual'] }} porsi</span>
          </div>
          <div class="mt-2 h-2.5 rounded-full bg-stone-100 overflow-hidden">
            <div class="h-full rounded-full {{ $i === 0 ? 'bg-maroon' : 'bg-[#E8C4C4]' }}" style="width: {{ round($t['terjual'] / $terlarisMaks * 100) }}%"></div>
          </div>
          <p class="text-[11px] text-stone-500 mt-1">Dipesan dalam {{ $t['pesanan'] }} pesanan</p>
        </div>
      </li>
    @empty
      <li class="px-6 py-10 text-center text-sm text-stone-500">Belum ada penjualan pada periode ini.</li>
    @endforelse
  </ul>
</section>
@endsection
