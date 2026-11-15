{{--
  Tampilan HP untuk pelanggan (hanya tampil di bawah lebar 768px / md).
  Pemakaian:  @include('partials.pelanggan-mobile', ['aktif' => 'pesanan', 'judul' => 'Pesanan Saya', 'kembali' => null])
  $aktif   : beranda | keranjang | favorit | pesanan | profil
  $judul   : judul di tengah top bar (kosong = logo + nama toko)
  $kembali : URL tombol panah kembali (kosong = tanpa panah)
--}}
@php
  $aktif   = $aktif   ?? '';
  $judul   = $judul   ?? '';
  $kembali = $kembali ?? null;
  $tab = [
    'beranda'   => ['Beranda',   url('/produk'),         'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10'],
    'keranjang' => ['Keranjang', url('/keranjang'),      'M3 3h2l.4 2M7 13h10l3-8H5.4M7 13L5.4 5M7 13l-2 4h13M9 21a1 1 0 100-2 1 1 0 000 2zm9 0a1 1 0 100-2 1 1 0 000 2z'],
    'favorit'   => ['Favorite',  route('favorit'),       'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
    'pesanan'   => ['Pesanan',   route('pesanan.saya'),  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h6'],
    'profil'    => ['Profil',    route('profil'),        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
  ];
@endphp

{{-- Top bar HP --}}
<header class="md:hidden sticky top-0 z-50 bg-[#FCF9F4] border-b border-[#993A3C]/15">
  <div class="h-14 px-3 flex items-center justify-between gap-2">
    <div class="w-10 flex items-center">
      @if ($kembali)
        <a href="{{ $kembali }}" aria-label="Kembali" class="w-10 h-10 -ml-1 rounded-full flex items-center justify-center text-[#993A3C] active:bg-[#993A3C]/10">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"/></svg>
        </a>
      @else
        <a href="{{ url('/produk') }}" aria-label="Beranda"><img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDp7AJEQ76C6MOzJmciFpvV6P9aqIdHHv4Vd9QVKOPW98sClvZ0GNR2RU8Mc3Ughath9lIYw-AHOAXYaIq_eKKmsooyGXm2VMbD0JmmXqj-1HISrnb6TkNyAh4F8MSHx-4-JK9KpteTUjfo_toYq9Pr5HPV8vzUYMtgjLs-MgTPRCw2AL_axsEFbWFdHIdXnw4_DVQYUebd14bVErpTKQwR_jMJ1QogQFrk1UA2KjmskKDrDPquRfE2ByUvMCV_pRDaPgM" alt="Logo" class="w-9 h-9 rounded-full object-cover ring-2 ring-[#993A3C]/20"></a>
      @endif
    </div>
    <div class="flex-1 text-center min-w-0">
      @if ($judul)
        <h1 class="font-serif text-lg font-bold text-[#993A3C] truncate">{{ $judul }}</h1>
      @else
        <span class="font-serif text-lg font-bold text-[#993A3C]">Kueku Marinda</span>
      @endif
    </div>
    <div class="w-10 flex justify-end">
      <a href="{{ route('profil') }}" aria-label="Profil" class="w-9 h-9 rounded-full bg-[#993A3C] text-white flex items-center justify-center text-sm font-bold font-serif">
        {{ auth()->check() ? strtoupper(mb_substr(auth()->user()->nama ?? auth()->user()->name ?? 'K', 0, 1)) : 'K' }}
      </a>
    </div>
  </div>
</header>

{{-- Bottom navigation HP --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-gray-200 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]" style="padding-bottom:env(safe-area-inset-bottom)" aria-label="Menu utama">
  <ul class="grid grid-cols-5">
    @foreach ($tab as $key => [$label, $href, $path])
      @php $on = $aktif === $key; @endphp
      <li>
        <a href="{{ $href }}" class="relative flex flex-col items-center justify-center gap-0.5 py-2 text-[11px] font-medium {{ $on ? 'text-[#993A3C]' : 'text-gray-500' }}">
          <span class="relative">
            <svg class="w-6 h-6" fill="{{ $on && $key === 'favorit' ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}" stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $on ? 2.2 : 1.8 }}"/></svg>
            @if ($key === 'keranjang')
              <span id="cart-badge-mobile" class="hidden absolute -top-1.5 -right-2.5 bg-[#993A3C] text-white text-[10px] font-bold min-w-[16px] h-4 px-1 rounded-full items-center justify-center">0</span>
            @endif
          </span>
          <span class="{{ $on ? 'font-bold' : '' }}">{{ $label }}</span>
          @if ($on)<span class="absolute top-0 inset-x-6 h-0.5 rounded-full bg-[#993A3C]"></span>@endif
        </a>
      </li>
    @endforeach
  </ul>
</nav>
<script>
(function () {
  try {
    var c = JSON.parse(localStorage.getItem("kueku_cart") || "{}");
    var n = Object.values(c).reduce(function (a, x) { return a + x; }, 0);
    var b = document.getElementById("cart-badge-mobile");
    if (b && n > 0) { b.textContent = n; b.classList.remove("hidden"); b.classList.add("flex"); }
  } catch (e) {}
})();
</script>
