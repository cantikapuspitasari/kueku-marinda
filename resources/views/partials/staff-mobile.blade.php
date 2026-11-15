{{--
  Tampilan HP untuk panel Admin / Kurir / Owner (hanya tampil di bawah 768px).
  Dipanggil dari _sidebar tiap peran:
  @include('partials.staff-mobile', ['menu' => $menu, 'aktif' => $aktif, 'peran' => 'Admin Panel', 'nama' => $adminNama])
--}}
@php
  $jml = max(count($menu), 1);
  $logo = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCgqqVp-lHgwPosX2ygScCFlmg2GflarJWp5uC6n5mlCvQcw0W0FIT9v3QeYPqHDtIyKo9tmuQgqLpKpXEDvTPkZaUIToNJQVkDaf04ncQvkBdrPQTZTvl4qzPlaVeswhBl39jIOaFgNcrJA6k8PgrZVaYg_u2vJIXEVRMFS-BO7uhENpuaQ3XEJIlRN3oUyVOdOpxSuiFvFJngTuLr729-Rd7id9JXU1RiVm_nHr4VJi_0U84PWZUyFYNf6T2cScB5MOU';
@endphp

{{-- Top bar HP --}}
<header class="md:hidden sticky top-0 z-40 bg-maroon text-white shadow-md">
  <div class="h-14 px-3 flex items-center gap-3">
    <img src="{{ $logo }}" alt="Logo" class="w-9 h-9 rounded-full object-cover bg-white border border-white/30">
    <div class="flex-1 min-w-0 leading-tight">
      <span class="block text-[10px] uppercase tracking-wider font-semibold text-rose-200">{{ $peran }}</span>
      <span class="block font-serif font-bold text-base truncate">Kueku Marinda</span>
    </div>
    <a href="{{ route('akun.saya') }}" title="Akun Saya" aria-label="Akun Saya" class="w-9 h-9 rounded-full bg-white text-maroon flex items-center justify-center font-bold text-xs shadow-sm">{{ strtoupper(mb_substr($nama, 0, 2)) }}</a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" aria-label="Keluar" title="Keluar" class="w-9 h-9 rounded-full bg-white/10 active:bg-white/20 border border-white/20 flex items-center justify-center">
        <svg class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
      </button>
    </form>
  </div>
</header>

{{-- Bottom navigation HP --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-200 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]" style="padding-bottom:env(safe-area-inset-bottom)" aria-label="Menu utama">
  <ul class="grid" style="grid-template-columns:repeat({{ $jml }},minmax(0,1fr))">
    @foreach ($menu as [$kode, $label, $url, $ikon])
      @php $on = $aktif === $kode; @endphp
      <li>
        <a href="{{ $url }}" class="relative flex flex-col items-center justify-center gap-0.5 px-1 py-2 text-[10px] leading-tight text-center {{ $on ? 'text-maroon font-bold' : 'text-gray-500 font-medium' }}">
          @if ($on)<span class="absolute top-0 inset-x-6 h-0.5 rounded-full bg-maroon"></span>@endif
          <span class="relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $ikon }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $on ? 2.2 : 1.8 }}"/></svg>
            @if ($kode === 'pesanan' && isset($pesananBaru) && $pesananBaru > 0)
              <span class="absolute -top-1.5 -right-2.5 bg-maroon text-white text-[9px] font-bold min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center">{{ $pesananBaru }}</span>
            @endif
          </span>
          <span class="truncate max-w-full">{{ $label }}</span>
        </a>
      </li>
    @endforeach
  </ul>
</nav>
