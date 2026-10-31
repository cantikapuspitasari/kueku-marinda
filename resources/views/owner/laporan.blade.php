@extends('owner.layout', ['aktif' => 'laporan'])

@section('title', 'Laporan Penjualan')
@section('judul', 'Laporan Penjualan')
@section('subjudul', 'Pilih periode, lihat tren pendapatan, produk terlaris, dan rincian pesanan. Hanya pesanan berstatus Selesai yang dihitung.')

@php
  $qs = ['dari' => $dari, 'sampai' => $sampai, 'top' => $top];
@endphp

@section('aksi')
  <a href="{{ route('owner.laporan.unduh', $qs + ['format' => 'cetak']) }}" target="_blank" class="inline-flex items-center gap-2 bg-white hover:bg-stone-50 text-maroon border border-maroon px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
    Cetak
  </a>
  <a href="{{ route('owner.laporan.unduh', $qs + ['format' => 'excel']) }}" class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon-dark text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
    Export Excel
  </a>
@endsection

@section('isi')
{{-- ========== Filter periode ========== --}}
<section class="bg-white rounded-2xl p-6 border border-warm shadow-sm">
  <form method="GET" action="{{ route('owner.laporan') }}" id="form-filter" class="flex flex-col lg:flex-row lg:items-end gap-4">
    <div class="flex flex-col sm:flex-row gap-4 flex-1">
      <label class="flex-1">
        <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Dari Tanggal</span>
        <input type="date" name="dari" id="dari" value="{{ $dari }}" required class="w-full rounded-xl border-warm text-sm focus:border-maroon focus:ring-maroon">
      </label>
      <label class="flex-1">
        <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Sampai Tanggal</span>
        <input type="date" name="sampai" id="sampai" value="{{ $sampai }}" min="{{ $dari }}" required class="w-full rounded-xl border-warm text-sm focus:border-maroon focus:ring-maroon">
      </label>
      <label class="sm:w-40">
        <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 mb-1.5">Produk Terlaris</span>
        <select name="top" class="w-full rounded-xl border-warm text-sm focus:border-maroon focus:ring-maroon">
          @foreach ([5, 10, 20, 50] as $n)
            <option value="{{ $n }}" @selected($top === $n)>Top {{ $n }}</option>
          @endforeach
          @if (!in_array($top, [5, 10, 20, 50], true))
            <option value="{{ $top }}" selected>Top {{ $top }}</option>
          @endif
        </select>
      </label>
    </div>
    <button type="submit" class="bg-maroon hover:bg-maroon-dark text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">Tampilkan</button>
  </form>
  <div class="mt-4 pt-4 border-t border-[#F5F2ED] flex flex-wrap items-center gap-2">
    <span class="text-xs font-semibold text-stone-500 mr-1">Tombol cepat:</span>
    @foreach ($cepat as $nama => [$d, $s])
      @php $aktifCepat = $d === $dari && $s === $sampai; @endphp
      <a href="{{ route('owner.laporan', ['dari' => $d, 'sampai' => $s, 'top' => $top]) }}"
         class="px-3.5 py-1.5 text-xs rounded-lg border {{ $aktifCepat ? 'bg-maroon text-white border-maroon font-bold' : 'bg-[#F6F3EE] border-warm text-stone-600 font-semibold hover:text-stone-900' }}">{{ $nama }}</a>
    @endforeach
  </div>
</section>

{{-- ========== Grafik tren (hanya di layar) ========== --}}
<section class="bg-white rounded-2xl p-6 border border-warm shadow-sm">
  <div class="pb-6 border-b border-[#F5F2ED]">
    <span class="text-xs uppercase tracking-wider font-bold text-maroon">Tren Pendapatan</span>
    <h3 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Laporan Pesanan, {{ $label }}</h3>
  </div>

  @php $tinggi = 190; $banyak = count($batang); @endphp
  <div class="pt-8 pb-4 overflow-x-auto custom-scrollbar">
    <div class="relative h-64 flex items-end justify-between gap-2 sm:gap-4 px-4" style="min-width: {{ max(0, $banyak * 44) }}px">
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
          @elseif ($b['nilai'] > 0 && $banyak <= 10)
            <span class="text-[11px] font-bold text-stone-600 mb-2 group-hover:text-maroon whitespace-nowrap">Rp {{ number_format($b['nilai'], 0, ',', '.') }}</span>
          @endif
          <div title="Rp {{ number_format($b['nilai'], 0, ',', '.') }}" class="w-full max-w-[56px] rounded-t-lg transition-all duration-200 {{ $tertinggi ? 'bg-maroon shadow-md ring-2 ring-maroon/20' : 'bg-[#E8C4C4] group-hover:bg-maroon' }}" style="height: {{ $h }}px;"></div>
          <span class="text-xs mt-3 whitespace-nowrap {{ $tertinggi ? 'font-bold text-maroon' : 'font-semibold text-stone-500' }}">{{ $b['label'] }}</span>
        </div>
      @endforeach
    </div>
    @if ($ringkas['pesanan'] === 0)
      <p class="text-center text-sm text-stone-500 mt-4">Belum ada penjualan selesai pada periode ini.</p>
    @endif
  </div>
</section>

{{-- ========== Ringkasan ========== --}}
<div>
  <div class="mb-4">
    <h3 class="font-serif text-2xl font-bold text-stone-900">Ringkasan</h3>
    <p class="text-sm text-stone-500">{{ $label }}</p>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Pendapatan</span>
        <p class="font-serif text-3xl font-bold text-stone-900 mt-3">Rp {{ number_format($ringkas['pendapatan'], 0, ',', '.') }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-semibold {{ is_null($ringkas['pertumbuhan']) ? 'text-stone-500' : ($ringkas['pertumbuhan'] >= 0 ? 'text-emerald-700' : 'text-rose-600') }}">
        @if (is_null($ringkas['pertumbuhan']))
          Belum ada pembanding periode sebelumnya
        @else
          {{ $ringkas['pertumbuhan'] >= 0 ? '+' : '' }}{{ $ringkas['pertumbuhan'] }}% dibanding periode sebelumnya
        @endif
      </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Pesanan</span>
        <p class="font-serif text-3xl font-bold text-stone-900 mt-3">{{ number_format($ringkas['pesanan'], 0, ',', '.') }}</p>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-medium text-stone-500">Pesanan berstatus Selesai</div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm flex flex-col justify-between">
      <div>
        <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Rata-rata Transaksi</span>
        <div class="flex items-baseline gap-1 mt-3"><p class="font-serif text-3xl font-bold text-stone-900">Rp {{ number_format($ringkas['rata'], 0, ',', '.') }}</p><span class="text-xs text-stone-500 font-medium">/pesanan</span></div>
      </div>
      <div class="mt-4 pt-4 border-t border-[#F5F2ED] text-xs font-medium text-stone-500">Dari {{ $ringkas['pesanan'] }} pesanan selesai</div>
    </div>
  </div>
</div>

{{-- ========== Produk terlaris (tabel) ========== --}}
<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100">
    <h3 class="text-lg font-bold font-serif text-stone-900">Produk Terlaris, {{ $label }}</h3>
    <p class="text-xs text-stone-500">Top {{ $top }} berdasarkan jumlah terjual. Jika sama, diurutkan dari pendapatan tertinggi.</p>
  </div>
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-sm">
      <thead class="bg-[#F6F3EE] text-xs uppercase tracking-wider text-stone-500">
        <tr>
          <th class="px-6 py-3 text-center w-24">Peringkat</th>
          <th class="px-6 py-3 text-left">Nama Produk</th>
          <th class="px-6 py-3 text-left">Kategori</th>
          <th class="px-6 py-3 text-right">Jumlah Terjual</th>
          <th class="px-6 py-3 text-right">Total Pendapatan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($terlaris as $i => $t)
          <tr>
            <td class="px-6 py-3 text-center"><span class="inline-flex w-7 h-7 rounded-full {{ $i === 0 ? 'bg-maroon text-white' : 'bg-stone-100 text-stone-600' }} text-xs font-bold items-center justify-center">{{ $i + 1 }}</span></td>
            <td class="px-6 py-3 font-semibold text-stone-800">{{ $t->nama_produk }}</td>
            <td class="px-6 py-3 text-stone-600">{{ $t->nama_kategori ?? '-' }}</td>
            <td class="px-6 py-3 text-right text-stone-700">{{ number_format($t->terjual, 0, ',', '.') }}</td>
            <td class="px-6 py-3 text-right font-bold text-stone-900">Rp {{ number_format($t->pendapatan, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-6 py-10 text-center text-sm text-stone-500">Tidak ada data penjualan pada periode ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

{{-- ========== Rincian pesanan (tabel) ========== --}}
<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100">
    <h3 class="text-lg font-bold font-serif text-stone-900">Rincian Pesanan</h3>
    <p class="text-xs text-stone-500">{{ $rincian->count() }} pesanan pada periode ini (semua status). Hanya yang Selesai masuk ke ringkasan dan produk terlaris.</p>
  </div>
  <div class="overflow-x-auto max-h-[28rem] custom-scrollbar">
    <table class="w-full text-sm">
      <thead class="bg-[#F6F3EE] text-xs uppercase tracking-wider text-stone-500 sticky top-0">
        <tr>
          <th class="px-6 py-3 text-left">Tanggal</th>
          <th class="px-6 py-3 text-left">No. Pesanan</th>
          <th class="px-6 py-3 text-left">Pelanggan</th>
          <th class="px-6 py-3 text-left">Jenis</th>
          <th class="px-6 py-3 text-left">Status</th>
          <th class="px-6 py-3 text-right">Total</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($rincian as $r)
          <tr>
            <td class="px-6 py-3 text-stone-600 whitespace-nowrap">{{ $r->tanggal_fmt }}</td>
            <td class="px-6 py-3 font-mono text-xs text-stone-700">{{ $r->kode_pesanan }}</td>
            <td class="px-6 py-3 text-stone-800">{{ $r->pelanggan ?? '-' }}</td>
            <td class="px-6 py-3 text-stone-600">{{ $r->jenis_fmt }}</td>
            <td class="px-6 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $r->status_pesanan === 'SELESAI' ? 'bg-emerald-50 text-emerald-700' : ($r->status_pesanan === 'DIBATALKAN' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-700') }}">{{ $r->status_fmt }}</span></td>
            <td class="px-6 py-3 text-right font-bold text-stone-900 whitespace-nowrap">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-6 py-10 text-center text-sm text-stone-500">Tidak ada pesanan pada periode ini.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Tanggal akhir tidak boleh lebih kecil dari tanggal awal
  (function () {
    const dari = document.getElementById('dari'), sampai = document.getElementById('sampai');
    function sinkron() {
      sampai.min = dari.value || '';
      if (dari.value && sampai.value && sampai.value < dari.value) sampai.value = dari.value;
    }
    dari.addEventListener('change', sinkron);
    sinkron();
  })();
</script>
@endpush
