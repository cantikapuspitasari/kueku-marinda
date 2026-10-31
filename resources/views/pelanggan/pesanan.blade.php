<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pesanan Saya - Kueku Marinda</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          maroon: { DEFAULT:'#993A3C', 50:'#FDF7F7', 100:'#F9ECEC', 600:'#8A3234', 700:'#752829', 800:'#5F1D1E' },
          cream: '#FAF7F2',
        },
        fontFamily: {
          serif: ['"Playfair Display"','Georgia','serif'],
          sans: ['"Plus Jakarta Sans"','system-ui','sans-serif'],
        }
      }
    }
  }
</script>
<style>
  .product-shadow { box-shadow: 0 4px 20px -2px rgba(153,58,60,0.08), 0 2px 6px -1px rgba(0,0,0,0.04); }
  .product-shadow:hover { box-shadow: 0 12px 28px -4px rgba(153,58,60,0.16), 0 4px 10px -2px rgba(0,0,0,0.06); }
</style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen">

<!-- Header -->
<header class="w-full bg-[#FCF9F4] border-b border-[#993A3C]/15 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
  <a class="flex items-center gap-3 group shrink-0" href="{{ url('/produk') }}">
    <img alt="Logo Kueku Marinda" class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-[#993A3C]/20 transition-transform group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDp7AJEQ76C6MOzJmciFpvV6P9aqIdHHv4Vd9QVKOPW98sClvZ0GNR2RU8Mc3Ughath9lIYw-AHOAXYaIq_eKKmsooyGXm2VMbD0JmmXqj-1HISrnb6TkNyAh4F8MSHx-4-JK9KpteTUjfo_toYq9Pr5HPV8vzUYMtgjLs-MgTPRCw2AL_axsEFbWFdHIdXnw4_DVQYUebd14bVErpTKQwR_jMJ1QogQFrk1UA2KjmskKDrDPquRfE2ByUvMCV_pRDaPgM">
    <span class="text-2xl font-bold tracking-tight text-[#993A3C] font-serif">Kueku Marinda</span>
  </a>
  <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-sm font-medium">
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ url('/produk') }}">Beranda</a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1 inline-flex items-center gap-1.5" href="{{ url('/keranjang') }}">keranjang
      <span id="cart-badge-count" class="bg-[#993A3C] text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">0</span>
    </a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ route('favorit') }}">Favorite</a>
    <a class="text-[#993A3C] font-semibold border-b-2 border-[#993A3C] pb-0.5 tracking-wide" href="{{ route('pesanan.saya') }}">Pesanan</a>
  </nav>
  <div class="flex items-center">
    <a class="inline-flex items-center gap-2 bg-[#F6F3EE] hover:bg-[#EFECE6] border border-[#993A3C]/20 text-[#2D2828] px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all shadow-sm" href="{{ route('profil') }}">
      <svg class="w-4 h-4 text-[#993A3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span class="whitespace-nowrap">Profil pelanggan</span>
    </a>
  </div>
</div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
  <div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-gray-900">Pesanan Saya</h1>
    <p class="text-sm text-gray-500 mt-1">Semua pesanan Anda beserta status terbarunya.</p>
  </div>

  @if (session('success'))<div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">{{ session('success') }}</div>@endif

  @php
    $warna = ['menunggu' => 'bg-amber-100 text-amber-800', 'batal' => 'bg-red-100 text-red-700', 'ok' => 'bg-emerald-100 text-emerald-800'];
  @endphp

  @forelse ($daftar as $o)
    <div class="bg-white rounded-2xl border border-gray-200 p-5 product-shadow">
      <div class="flex flex-wrap items-start justify-between gap-3 pb-4 border-b border-gray-100">
        <div>
          <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Nomor Pesanan</p>
          <p class="font-bold text-gray-900">#{{ $o->kode }}</p>
          <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($o->tanggal)->translatedFormat('d M Y, H:i') }} · {{ $o->jenis === 'DELIVERY' ? 'Diantar Kurir' : 'Ambil di Toko' }}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $warna[$o->state] ?? $warna['ok'] }}">{{ $o->label }}</span>
      </div>

      @if ($o->state === 'ok')
        <div class="flex items-center gap-1 pt-4">
          @for ($i = 0; $i < 5; $i++)
            <div class="flex-1 h-1.5 rounded-full {{ $i <= $o->tahap ? 'bg-maroon' : 'bg-gray-200' }}"></div>
          @endfor
        </div>
      @endif

      <ul class="py-4 space-y-3">
        @foreach ($o->item as $it)
          <li class="flex items-center gap-3">
            @if ($it->foto_produk)
              <img src="{{ asset('images/produk/' . $it->foto_produk) }}" alt="{{ $it->nama_produk }}" class="w-12 h-12 rounded-lg object-cover border border-gray-100">
            @else
              <div class="w-12 h-12 rounded-lg bg-gray-100"></div>
            @endif
            <span class="flex-1 text-sm font-medium text-gray-800">{{ $it->nama_produk }}</span>
            <span class="text-xs font-semibold bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ $it->jumlah }}x</span>
          </li>
        @endforeach
      </ul>

      <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
        <div>
          <span class="text-xs text-gray-400 block">Total</span>
          <span class="text-lg font-bold text-maroon">Rp {{ number_format($o->total, 0, ',', '.') }}</span>
        </div>
        <a href="{{ url('/pesanan/detail') }}?kode={{ urlencode($o->kode) }}" class="px-5 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition shadow-sm">Lihat Detail &amp; Lacak</a>
      </div>
    </div>
  @empty
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-200">
      <h2 class="font-serif text-xl font-bold text-gray-900">Belum ada pesanan</h2>
      <p class="text-sm text-gray-500 mt-1 mb-6">Pesanan yang Anda buat akan muncul di sini.</p>
      <a href="{{ url('/produk') }}" class="inline-flex px-6 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition">Mulai Belanja</a>
    </div>
  @endforelse
</main>
<script>
(function () {
  const c = JSON.parse(localStorage.getItem("kueku_cart") || "{}");
  const b = document.getElementById("cart-badge-count");
  if (b) b.textContent = Object.values(c).reduce((a, x) => a + x, 0);
})();
</script>

</body>
</html>
