<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pesanan Terkonfirmasi - Kueku Marinda</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            maroon: '#993A3C',
            'maroon-hover': '#7f2d2f',
            'maroon-light': '#FDF4F4',
            cream: '#FCF9F4',
            card: '#FFFFFF',
            sand: '#F6F3EE',
            textPrimary: '#2D2926',
            textMuted: '#68625D'
          }
        },
        fontFamily: {
          serif: ['"Playfair Display"', 'Georgia', 'serif'],
          sans: ['"Plus Jakarta Sans"', 'sans-serif']
        },
        boxShadow: {
          subtle: '0 4px 20px -2px rgba(153, 58, 60, 0.06), 0 2px 6px -1px rgba(0, 0, 0, 0.04)',
          float: '0 20px 35px -10px rgba(153, 58, 60, 0.12)'
        }
      }
    }
  }
</script>
</head>
<body class="bg-brand-cream text-brand-textPrimary font-sans min-h-screen flex flex-col justify-between antialiased selection:bg-[#993A3C]/10 selection:text-[#993A3C]">

<!-- Header -->
<header class="w-full max-w-7xl mx-auto px-6 py-6 md:px-12 flex items-center justify-between">
  <a aria-label="Kembali ke beranda" class="inline-flex items-center gap-3 text-brand-textPrimary hover:text-brand-maroon transition-all group px-5 py-2.5 -ml-2 rounded-xl bg-brand-sand/60 hover:bg-brand-sand border border-brand-maroon/10 shadow-sm" href="{{ url('/produk') }}">
    <svg class="w-6 h-6 stroke-current transition-transform group-hover:-translate-x-1" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" x2="5" y1="12" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    <span class="text-lg font-semibold tracking-tight">Kembali</span>
  </a>
</header>

<!-- Konten -->
<main class="w-full flex-grow flex items-center justify-center px-4 py-8 md:py-12">
  <div class="w-full max-w-2xl text-center flex flex-col items-center">

    <!-- Ilustrasi -->
    <div class="relative mb-8 flex items-center justify-center">
      <div class="relative flex items-center justify-center w-52 h-52 sm:w-60 sm:h-60 mx-auto">
        <div class="absolute inset-0 rounded-full bg-brand-maroon/5 animate-pulse"></div>
        <svg class="w-48 h-48 sm:w-56 sm:h-56 relative z-10 transition-transform duration-700 hover:scale-105" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
          <style>@keyframes gentleFloat{0%,100%{transform:translateY(0px) rotate(0deg);}50%{transform:translateY(-8px) rotate(1deg);}}@keyframes checkPop{0%{stroke-dashoffset:40;opacity:0;}50%{opacity:1;}100%{stroke-dashoffset:0;opacity:1;}}@keyframes pulseRing{0%,100%{transform:scale(1);opacity:0.6;}50%{transform:scale(1.08);opacity:0.2;}}.box-float{animation:gentleFloat 3.5s ease-in-out infinite;transform-origin:center;}.check-anim{stroke-dasharray:40;animation:checkPop 1.2s cubic-bezier(0.65,0,0.45,1) forwards;}.ring-anim{animation:pulseRing 2.8s ease-in-out infinite;transform-origin:center;}</style>
          <circle class="ring-anim" cx="100" cy="108" r="70" fill="#993A3C" fill-opacity="0.06"></circle>
          <g class="box-float">
            <rect x="46" y="88" width="108" height="72" rx="14" fill="#FCF9F4" stroke="#993A3C" stroke-width="4"></rect>
            <rect x="40" y="72" width="120" height="22" rx="7" fill="#993A3C"></rect>
            <rect x="92" y="72" width="16" height="88" fill="#7f2d2f"></rect>
            <path d="M100 72 C88 50 64 54 70 70 C74 76 88 74 100 72 Z" fill="#993A3C"></path>
            <path d="M100 72 C112 50 136 54 130 70 C126 76 112 74 100 72 Z" fill="#993A3C"></path>
            <circle cx="100" cy="72" r="7" fill="#F6F3EE" stroke="#993A3C" stroke-width="3"></circle>
            <circle cx="100" cy="124" r="26" fill="#993A3C"></circle>
            <circle cx="100" cy="124" r="22" fill="#993A3C" stroke="#FCF9F4" stroke-width="2"></circle>
            <path class="check-anim" d="M91 124 L97 130 L110 117" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
            <circle cx="42" cy="56" r="3" fill="#993A3C" fill-opacity="0.4"></circle>
            <circle cx="162" cy="64" r="4" fill="#993A3C" fill-opacity="0.4"></circle>
            <path d="M154 134 L158 138 M158 134 L154 138" stroke="#993A3C" stroke-opacity="0.5" stroke-width="2" stroke-linecap="round"></path>
            <path d="M38 116 L42 120 M42 116 L38 120" stroke="#993A3C" stroke-opacity="0.5" stroke-width="2" stroke-linecap="round"></path>
          </g>
        </svg>
      </div>
    </div>

    <h1 class="text-3xl md:text-4xl lg:text-[42px] font-serif font-bold text-brand-textPrimary tracking-tight mb-3">Pesanan Terkonfirmasi !</h1>

    <p id="subtitle" class="text-brand-textMuted text-base md:text-lg max-w-lg mx-auto font-normal leading-relaxed mb-6">
      Tunggu Pesananmu Terkirim dan Bayar di Tempat setelah Pesananmu Sampai
    </p>

    <!-- Detail singkat -->
    <div class="inline-flex flex-wrap items-center justify-center gap-3 md:gap-6 bg-brand-sand/60 border border-brand-maroon/10 rounded-xl px-5 py-3 mb-10 text-xs md:text-sm font-medium text-brand-textPrimary shadow-sm">
      <div class="flex items-center gap-2">
        <span class="text-brand-textMuted font-normal">Nomor Pesanan:</span>
        <span id="order-no" class="font-semibold text-brand-maroon tracking-wide">-</span>
      </div>
      <div class="hidden sm:inline-block w-1.5 h-1.5 rounded-full bg-brand-maroon/30"></div>
      <div class="flex items-center gap-2">
        <span class="text-brand-textMuted font-normal">Metode Bayar:</span>
        <span id="order-method" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-maroon/10 text-brand-maroon font-semibold">COD (Bayar di Tempat)</span>
      </div>
    </div>

    <!-- Tombol -->
    <div class="w-full max-w-md flex flex-col items-center gap-3.5 px-4">
      <a class="w-full py-3.5 px-6 rounded-xl bg-brand-maroon hover:bg-brand-maroon-hover active:scale-[0.99] text-white font-medium text-base tracking-wide transition-all shadow-md hover:shadow-lg text-center flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-brand-maroon/20" href="{{ url('/produk') }}">
        Kembali ke Beranda
      </a>
      <a class="w-full py-3.5 px-6 rounded-xl bg-brand-sand hover:bg-[#ece7e0] active:scale-[0.99] text-brand-maroon font-medium text-base tracking-wide border border-brand-maroon/20 hover:border-brand-maroon/40 transition-all text-center flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-brand-maroon/15" href="{{ url('/pesanan/detail') }}">
        Cek Detail Pesanan
      </a>
    </div>
  </div>
</main>

<footer class="w-full py-6 text-center text-xs text-brand-textMuted border-t border-brand-maroon/5 mt-auto">
  <p>© 2018 - 2026 Kueku Marinda. Seluruh Hak Cipta.</p>
</footer>

<script>
/* Isi halaman dari data pesanan terakhir (disimpan oleh halaman checkout) */
(function () {
  let o = null;
  try { o = JSON.parse(localStorage.getItem("kueku_last_order")); } catch (e) {}
  if (!o) return;

  document.getElementById("order-no").textContent = o.no || "-";

  if (o.mode === "toko") {
    document.getElementById("subtitle").textContent = "Datang ke toko sesuai jadwal pengambilan dan bayar saat Pesananmu diambil";
    document.getElementById("order-method").textContent = "Bayar di Toko (Tunai)";
  } else {
    document.getElementById("subtitle").textContent = "Tunggu Pesananmu Terkirim dan Bayar di Tempat setelah Pesananmu Sampai";
    document.getElementById("order-method").textContent = "COD (Bayar di Tempat)";
  }
})();
</script>
</body>
</html>
