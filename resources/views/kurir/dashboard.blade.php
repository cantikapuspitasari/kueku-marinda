@extends('kurir.layout', ['aktif' => 'dashboard'])

@section('title', 'Dashboard Kurir')
@section('judul', 'Dashboard Kurir')
@section('subjudul', 'Pantau tugas antar Anda hari ini dan lanjutkan ke daftar pesanan yang harus diantar.')

@section('isi')
<!-- Statistik -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Pesanan Hari Ini</span>
      <span class="p-2 bg-amber-50 text-amber-700 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
    </div>
    <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stat['hari_ini'] }} Pesanan</div>
    <div class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500">Tugas aktif + yang sudah selesai hari ini</div>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Sedang Diantar</span>
      <span class="p-2 text-maroon rounded-lg bg-maroon/10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m10 0H3m10 0h2m4 0h1a1 1 0 001-1v-3.65a1 1 0 00-.22-.624l-3.48-4.35A1 1 0 0017.52 8H14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
    </div>
    <div class="text-3xl font-bold text-maroon tracking-tight">{{ $stat['diantar'] }} Pesanan</div>
    <div class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500">Berstatus "Di Perjalanan"</div>
  </div>

  <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:shadow-md transition">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Selesai Hari Ini</span>
      <span class="p-2 bg-emerald-50 text-emerald-700 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
    </div>
    <div class="text-3xl font-bold text-stone-800 tracking-tight">{{ $stat['selesai'] }} Pesanan</div>
    <div class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500">Sudah diserahkan &amp; dibayar</div>
  </div>
</section>

<!-- Menu Cepat -->
<section class="space-y-4">
  <div class="flex items-end justify-between">
    <h3 class="text-xl font-bold font-serif text-stone-800">Menu Cepat</h3>
    <span class="text-xs text-stone-500 font-medium">Akses operasional instan</span>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-warm shadow-sm hover:border-maroon/40 hover:shadow-md transition group flex flex-col justify-between">
      <div>
        <div class="w-12 h-12 rounded-xl bg-maroon-light text-maroon flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m10 0H3m10 0h2m4 0h1a1 1 0 001-1v-3.65a1 1 0 00-.22-.624l-3.48-4.35A1 1 0 0017.52 8H14m5 8a2 2 0 11-4 0m4 0a2 2 0 10-4 0M9 16a2 2 0 11-4 0m4 0a2 2 0 10-4 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </div>
        <h4 class="text-lg font-bold text-stone-800 group-hover:text-maroon transition-colors">Pesanan yang Diantar</h4>
        <p class="text-xs text-stone-500 mt-1 leading-relaxed">Lihat daftar pesanan yang diserahkan admin kepada Anda, hubungi pelanggan, dan perbarui status pengantaran.</p>
      </div>
      <a href="{{ route('kurir.pesanan') }}" class="mt-6 inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-maroon bg-maroon-light hover:bg-maroon hover:text-white transition">
        Lihat Pesanan yang Diantar
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>
  </div>
</section>
@endsection
