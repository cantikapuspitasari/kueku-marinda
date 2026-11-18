<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Bayar dengan QRIS - Kueku Marinda</title>
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
<header class="hidden md:block w-full bg-[#FCF9F4] border-b border-[#993A3C]/15 sticky top-0 z-50">
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

@include('partials.pelanggan-mobile', ['aktif' => 'pesanan', 'judul' => 'Bayar QRIS', 'kembali' => route('pesanan.saya')])

<main class="max-w-xl mx-auto px-4 sm:px-6 py-6 md:py-10 pb-28 md:pb-10 space-y-5">
  <div class="hidden md:block">
    <h1 class="font-serif text-3xl font-bold text-gray-900">Bayar dengan QRIS</h1>
    <p class="text-sm text-gray-500 mt-1">Scan QR Code di bawah menggunakan kamera HP Anda.</p>
  </div>

  @if (session('error'))<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">{{ session('error') }}</div>@endif

  <div class="bg-white rounded-2xl border border-gray-200 p-6 product-shadow text-center">
    <p class="text-xs text-gray-400">Kode pesanan</p>
    <p class="font-mono font-bold text-gray-900">{{ $pesanan->kode_pesanan }}</p>
    <p class="mt-3 text-xs text-gray-400">Total pembayaran</p>
    <p class="text-3xl font-bold text-maroon">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>

    {{-- Belum dibayar: tampilkan QR Code --}}
    <div id="area-qr" class="{{ $lunas ? 'hidden' : '' }}">
      <div id="qr-kotak" class="mx-auto mt-5 w-64 h-64 sm:w-72 sm:h-72 rounded-xl border border-gray-200 bg-white p-2 flex items-center justify-center text-sm text-gray-400">Membuat QR Code...</div>

      <div class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-full px-4 py-1.5">
        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        <span id="teks-status">Menunggu pembayaran</span>
      </div>

      <ol class="mt-5 text-left text-sm text-gray-600 space-y-1.5 list-decimal list-inside">
        <li>Buka kamera HP, arahkan ke QR Code di atas.</li>
        <li>Buka tautan yang muncul (halaman pembayaran Kueku Marinda).</li>
        <li>Periksa nominal dan kode pesanan, lalu tekan <b>Bayar Sekarang</b>.</li>
        <li>Halaman ini otomatis berubah menjadi <b>LUNAS</b>.</li>
      </ol>

      @if ($hostLokal)
        <p class="mt-4 text-xs text-left text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
          Alamat QR masih <b>localhost</b>, sehingga tidak bisa dibuka dari HP. Isi <code>QRIS_BASE_URL</code> di file <code>.env</code> dengan alamat laptop di WiFi (contoh <code>http://192.168.1.10:8000</code>) dan jalankan server dengan <code>php artisan serve --host=0.0.0.0</code>.
        </p>
      @endif

      <details class="mt-4 text-left text-xs text-gray-500">
        <summary class="cursor-pointer font-semibold text-gray-600">Tidak bisa scan? Buka tautan pembayaran</summary>
        <p class="mt-2 break-all select-all font-mono text-[11px] bg-gray-50 border border-gray-200 rounded-lg p-2">{{ $urlQr }}</p>
        <a href="{{ $urlQr }}" target="_blank" rel="noopener" class="inline-block mt-2 px-4 py-2 rounded-lg border border-maroon/40 text-maroon font-semibold hover:bg-maroon-50">Buka halaman pembayaran</a>
      </details>

      <p class="mt-4 text-[11px] text-gray-400">QR Code berlaku {{ $menit }} menit sejak halaman ini dibuka. Muat ulang halaman untuk membuat yang baru.</p>
    </div>

    {{-- Sudah dibayar --}}
    <div id="area-lunas" class="{{ $lunas ? '' : 'hidden' }} mt-6">
      <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
      </div>
      <p class="mt-3 text-xl font-bold text-emerald-700">Pembayaran LUNAS</p>
      <p class="text-sm text-gray-500 mt-1">Pesanan Anda diteruskan ke admin dengan catatan pembayaran QRIS lunas.</p>
      <div class="mt-5 flex flex-wrap justify-center gap-2">
        <a href="{{ route('pesanan.saya') }}" class="px-5 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition">Lihat Pesanan Saya</a>
        <a href="{{ route('pesanan.struk', $pesanan->id_pesanan) }}" target="_blank" class="px-5 py-2.5 bg-white hover:bg-maroon-50 text-maroon border border-maroon/40 rounded-xl text-sm font-semibold transition">Lihat Struk</a>
      </div>
    </div>
  </div>

  <a href="{{ route('pesanan.saya') }}" class="block text-center text-sm text-gray-500 hover:text-maroon">Bayar nanti, kembali ke Pesanan Saya</a>
</main>

<script src="{{ asset('js/qrcode-kueku.js') }}"></script>
<script>
(function () {
  const urlQr = @json($urlQr);
  const sudahLunas = @json($lunas);
  const urlStatus = @json(route('qris.status', $pesanan->id_pesanan));

  function tampilkanLunas() {
    document.getElementById("area-qr").classList.add("hidden");
    document.getElementById("area-lunas").classList.remove("hidden");
    try { localStorage.removeItem("kueku_last_order"); } catch (e) {}
  }

  if (!sudahLunas && urlQr) {
    const kotak = document.getElementById("qr-kotak");
    try {
      kotak.innerHTML = window.KuekuQR.svg(urlQr, { ecc: "M" });
      const svg = kotak.querySelector("svg");
      svg.style.width = "100%"; svg.style.height = "100%";
    } catch (e) {
      kotak.textContent = "QR Code gagal dibuat. Gunakan tautan pembayaran di bawah.";
    }

    // Cek status pembayaran tiap 3 detik sampai LUNAS
    const timer = setInterval(async function () {
      try {
        const res = await fetch(urlStatus, { headers: { "Accept": "application/json" }, credentials: "same-origin" });
        if (!res.ok) return;
        const d = await res.json();
        if (d.lunas) { clearInterval(timer); tampilkanLunas(); }
      } catch (e) { /* coba lagi pada putaran berikutnya */ }
    }, 3000);
  }

  const b = document.getElementById("cart-badge-count");
  if (b) { try { b.textContent = Object.values(JSON.parse(localStorage.getItem("kueku_cart") || "{}")).reduce((a, x) => a + x, 0); } catch (e) {} }
})();
</script>

</body>
</html>
