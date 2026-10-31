@php
  $aktif = $aktif ?? '';
  $kurirUser = auth()->user();
  $adminNama = $kurirUser->nama ?? 'Kurir';
  $tugasAktif = \Illuminate\Support\Facades\DB::table('pengiriman')
      ->where('id_user', $kurirUser->id_user)
      ->whereIn('status_pengiriman', ['MENUNGGU', 'DIAMBIL_KURIR', 'DIANTAR'])->count();

  $menu = [
    ['dashboard', 'Dashboard', route('kurir.dashboard'), 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
    ['pesanan', 'Pesanan Diantar', route('kurir.pesanan'), 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m10 0H3m10 0h2m4 0h1a1 1 0 001-1v-3.65a1 1 0 00-.22-.624l-3.48-4.35A1 1 0 0017.52 8H14m5 8a2 2 0 11-4 0m4 0a2 2 0 10-4 0M9 16a2 2 0 11-4 0m4 0a2 2 0 10-4 0'],
  ];
@endphp
<!-- Sidebar kurir: dipakai semua halaman admin supaya tampilannya sama -->
<aside class="w-full md:w-72 bg-maroon text-white flex-shrink-0 flex flex-col justify-between md:sticky md:top-0 md:h-screen shadow-lg z-30">
  <div>
    <div class="p-6 border-b border-white/10 flex items-center gap-3.5">
      <img alt="Logo Kueku Marinda" class="w-12 h-12 rounded-full border border-white/20 shadow-sm object-cover bg-white" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCgqqVp-lHgwPosX2ygScCFlmg2GflarJWp5uC6n5mlCvQcw0W0FIT9v3QeYPqHDtIyKo9tmuQgqLpKpXEDvTPkZaUIToNJQVkDaf04ncQvkBdrPQTZTvl4qzPlaVeswhBl39jIOaFgNcrJA6k8PgrZVaYg_u2vJIXEVRMFS-BO7uhENpuaQ3XEJIlRN3oUyVOdOpxSuiFvFJngTuLr729-Rd7id9JXU1RiVm_nHr4VJi_0U84PWZUyFYNf6T2cScB5MOU">
      <div class="leading-tight">
        <span class="text-[11px] uppercase tracking-wider font-semibold text-rose-200 block">Kurir Panel</span>
        <h1 class="text-lg font-bold font-serif tracking-wide text-white">Kueku Marinda</h1>
      </div>
    </div>
    <nav aria-label="Menu Utama" class="p-4 space-y-1.5">
      @foreach ($menu as [$kode, $label, $url, $ikon])
        @php $on = $aktif === $kode; @endphp
        <a href="{{ $url }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition duration-150 {{ $on ? 'bg-white text-maroon font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-medium' }}">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 {{ $on ? 'text-maroon' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $ikon }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            <span class="tracking-wide text-sm">{{ $label }}</span>
          </div>
          @if ($kode === 'pesanan' && $tugasAktif > 0)
            <span class="bg-white text-xs font-bold px-2.5 py-0.5 rounded-full text-maroon shadow-sm">{{ $tugasAktif }} Aktif</span>
          @elseif ($on)
            <span class="w-2 h-2 rounded-full bg-maroon"></span>
          @endif
        </a>
      @endforeach
    </nav>
  </div>
  <div class="p-4 border-t border-white/10 bg-black/10">
    <a href="{{ route('akun.saya') }}" title="Akun Saya" class="flex items-center gap-3 p-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 mb-3 transition">
      <div class="w-9 h-9 rounded-full bg-white text-maroon flex items-center justify-center font-bold text-xs shadow-sm">{{ strtoupper(mb_substr($adminNama, 0, 2)) }}</div>
      <div class="min-w-0">
        <p class="text-xs font-semibold text-white truncate">{{ $adminNama }}</p>
        <p class="text-[11px] text-rose-200/80">Kurir</p>
      </div>
    </a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 text-xs font-medium text-white/90 hover:text-white bg-white/10 hover:bg-white/20 border border-white/15 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span>Keluar Portal</span>
      </button>
    </form>
  </div>
</aside>
