<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Profil Saya - Kueku Marinda</title>
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
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ route('pesanan.saya') }}">Pesanan</a>
  </nav>
  <div class="flex items-center">
    <a class="inline-flex items-center gap-2 bg-[#F6F3EE] hover:bg-[#EFECE6] border border-[#993A3C]/20 text-[#2D2828] px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all shadow-sm" href="{{ route('profil') }}">
      <svg class="w-4 h-4 text-[#993A3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span class="whitespace-nowrap">Profil pelanggan</span>
    </a>
  </div>
</div>
</header>

<main class="max-w-2xl mx-auto px-4 sm:px-6 py-10 space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ url('/produk') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-maroon hover:bg-maroon hover:text-white transition shadow-sm" aria-label="Kembali">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900 flex-1 text-center pr-10">Profil Saya</h1>
  </div>

  @if (session('success'))<div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">{{ session('success') }}</div>@endif
  @if ($errors->any())<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>@endif

  <section class="bg-white rounded-2xl border border-gray-200 p-6 product-shadow flex items-center gap-5">
    <div class="w-16 h-16 rounded-full bg-maroon text-white flex items-center justify-center text-2xl font-bold font-serif shrink-0">{{ strtoupper(mb_substr($user->nama, 0, 1)) }}</div>
    <div class="min-w-0">
      <h2 class="font-serif text-xl font-bold text-gray-900 truncate">{{ $user->nama }}</h2>
      <p class="text-sm text-gray-500 mt-1">@if ($user->no_telepon){{ $user->no_telepon }}@else<a href="{{ route('profil.info') }}" class="text-maroon font-semibold hover:underline">Isi No. HP</a>@endif</p>
      <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
    </div>
  </section>

  <section class="grid grid-cols-3 gap-3 text-center">
    <div class="bg-white rounded-2xl border border-gray-200 py-4 product-shadow"><div class="text-2xl font-bold text-maroon">{{ $stat['selesai'] }}</div><div class="text-xs text-gray-600 mt-1">Pesanan selesai</div></div>
    <div class="bg-white rounded-2xl border border-gray-200 py-4 product-shadow"><div id="stat-fav" class="text-2xl font-bold text-maroon">0</div><div class="text-xs text-gray-600 mt-1">Favorite</div></div>
    <div class="bg-white rounded-2xl border border-gray-200 py-4 product-shadow"><div class="text-2xl font-bold text-maroon">{{ $stat['proses'] }}</div><div class="text-xs text-gray-600 mt-1">Pesanan di proses</div></div>
  </section>

  <section class="space-y-2">
    <h3 class="text-xs font-bold tracking-widest text-gray-500 px-1">AKUN</h3>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden product-shadow">
      <a href="{{ route('profil.info') }}"  class="flex items-center gap-4 px-5 py-4 hover:bg-maroon-50 transition border-b border-gray-100 last:border-0">
        <span class="w-11 h-11 rounded-full bg-maroon-100 text-maroon flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg></span>
        <span class="flex-1"><span class="block text-sm font-semibold text-gray-900">Edit Informasi Pribadi</span><span class="block text-xs text-gray-500">Nama, No. Hp, Email</span></span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
      <a href="{{ route('profil.alamat') }}"  class="flex items-center gap-4 px-5 py-4 hover:bg-maroon-50 transition border-b border-gray-100 last:border-0">
        <span class="w-11 h-11 rounded-full bg-maroon-100 text-maroon flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg></span>
        <span class="flex-1"><span class="block text-sm font-semibold text-gray-900">Edit Alamat</span><span class="block text-xs text-gray-500">Alamat Pengantaran</span></span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>
  </section>

  <section class="space-y-2">
    <h3 class="text-xs font-bold tracking-widest text-gray-500 px-1">PESANAN &amp; AKTIVITAS</h3>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden product-shadow">
      <a href="{{ route('pesanan.saya') }}"  class="flex items-center gap-4 px-5 py-4 hover:bg-maroon-50 transition border-b border-gray-100 last:border-0">
        <span class="w-11 h-11 rounded-full bg-maroon-100 text-maroon flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg></span>
        <span class="flex-1"><span class="block text-sm font-semibold text-gray-900">Pesanan Saya</span><span class="block text-xs text-gray-500">Lihat Status &amp; Riwayat Pesanan</span></span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
      <a href="{{ route('favorit') }}"  class="flex items-center gap-4 px-5 py-4 hover:bg-maroon-50 transition border-b border-gray-100 last:border-0">
        <span class="w-11 h-11 rounded-full bg-maroon-100 text-maroon flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg></span>
        <span class="flex-1"><span class="block text-sm font-semibold text-gray-900">Produk Favorite</span><span class="block text-xs text-gray-500">Produk yang kamu Simpan</span></span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>
  </section>

  <section class="space-y-2">
    <h3 class="text-xs font-bold tracking-widest text-gray-500 px-1">BANTUAN</h3>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden product-shadow">
      <a href="{{ $waAdmin ? 'https://wa.me/' . $waAdmin : '#' }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 px-5 py-4 hover:bg-maroon-50 transition border-b border-gray-100 last:border-0">
        <span class="w-11 h-11 rounded-full bg-maroon-100 text-maroon flex items-center justify-center shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg></span>
        <span class="flex-1"><span class="block text-sm font-semibold text-gray-900">Hubungi Admin</span><span class="block text-xs text-gray-500">Bantuan Melalui WhatsApp</span></span>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
    </div>
    @unless ($waAdmin)<p class="text-[11px] text-gray-400 px-1">Nomor WhatsApp admin belum diisi.</p>@endunless
  </section>

  <form method="POST" action="{{ route('logout') }}" class="pt-4 text-center">
    @csrf
    <button type="submit" class="px-10 py-3 bg-stone-900 hover:bg-black text-white rounded-xl text-sm font-semibold transition">Logout</button>
  </form>
</main>
<script>
(function () {
  try {
    const c = JSON.parse(localStorage.getItem("kueku_cart") || "{}");
    const b = document.getElementById("cart-badge-count");
    if (b) b.textContent = Object.values(c).reduce((a, x) => a + x, 0);
  } catch (e) {}
})();
</script>
<script>
try { document.getElementById("stat-fav").textContent = (JSON.parse(localStorage.getItem("kueku_fav") || "[]")).length; } catch (e) {}
</script>
</body>
</html>
