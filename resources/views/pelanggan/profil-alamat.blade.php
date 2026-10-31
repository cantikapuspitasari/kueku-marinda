<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Edit Alamat - Kueku Marinda</title>
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
    <a href="{{ route('profil') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-maroon hover:bg-maroon hover:text-white transition shadow-sm" aria-label="Kembali">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900 flex-1 text-center pr-10">Edit Alamat</h1>
  </div>
  @if (session('success'))<div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">{{ session('success') }}</div>@endif
  @if ($errors->any())<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>@endif

  @forelse ($alamat as $a)
    <section class="space-y-2">
      <h3 class="text-sm font-semibold text-gray-700 px-1">{{ $loop->first ? 'Alamat Utama' : 'Alamat Lainnya' }}</h3>
      <div class="bg-white rounded-2xl border border-gray-200 p-5 product-shadow">
        <p class="font-semibold text-gray-900">{{ $user->nama }}</p>
        <p class="text-sm text-gray-600">{{ $user->no_telepon ?: '-' }}</p>
        <p class="text-sm text-gray-800 mt-3">{{ $a->alamat_lengkap }}</p>
        <p class="text-[11px] uppercase tracking-wider font-semibold text-maroon mt-2">{{ $a->label_alamat }}</p>

        <details class="mt-4">
          <summary class="cursor-pointer text-xs font-semibold text-maroon hover:underline">Edit alamat ini</summary>
          <form method="POST" action="{{ route('profil.alamat.update', $a->id_alamat) }}" class="mt-3 space-y-3">
            @csrf @method('PUT')
            <input name="label_alamat" value="{{ $a->label_alamat }}" placeholder="Label (Rumah, Kantor...)" class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition">
            <textarea name="alamat_lengkap" rows="3" required class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition">{{ $a->alamat_lengkap }}</textarea>
            <button type="submit" class="px-6 py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition">Simpan Perubahan</button>
          </form>
          <form method="POST" action="{{ route('profil.alamat.destroy', $a->id_alamat) }}" class="mt-2" onsubmit="return confirm('Hapus alamat ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Hapus alamat</button>
          </form>
        </details>
      </div>
    </section>
  @empty
    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 text-sm text-gray-500">Belum ada alamat tersimpan.</div>
  @endforelse

  <details class="bg-white rounded-2xl border border-gray-200 product-shadow" {{ $errors->has('alamat_lengkap') ? 'open' : '' }}>
    <summary class="cursor-pointer list-none text-center px-6 py-4 text-sm font-semibold text-gray-800 hover:bg-maroon-50 rounded-2xl">+ Tambah Alamat</summary>
    <form method="POST" action="{{ route('profil.alamat.store') }}" class="px-6 pb-6 space-y-3">
      @csrf
      <input name="label_alamat" value="{{ old('label_alamat') }}" placeholder="Label (Rumah, Kantor...)" class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition">
      <textarea name="alamat_lengkap" rows="3" required placeholder="Nama jalan, no. rumah, RT/RW, kelurahan, kota" class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition">{{ old('alamat_lengkap') }}</textarea>
      <div class="text-center"><button type="submit" class="px-10 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition shadow-sm">Simpan Alamat</button></div>
    </form>
  </details>
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
</body>
</html>
