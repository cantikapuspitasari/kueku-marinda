<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Kueku Marinda - Toko Kue &amp; Roti Artisanal</title>
<!-- Google Fonts for typography -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS CDN with forms and container queries -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Config -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            maroon: {
              DEFAULT: '#993A3C',
              50: '#FDF7F7',
              100: '#F9ECEC',
              600: '#8A3234',
              700: '#752829',
              800: '#5F1D1E',
            },
            cream: '#FAF7F2',
          },
          fontFamily: {
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
            sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
<style data-purpose="custom-styling">
    .product-shadow {
      box-shadow: 0 4px 20px -2px rgba(153, 58, 60, 0.08), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
    }
    .product-shadow:hover {
      box-shadow: 0 12px 28px -4px rgba(153, 58, 60, 0.16), 0 4px 10px -2px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen">
<!-- BEGIN: MainHeader -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
<!-- Brand Logo & Name -->
<div class="flex items-center gap-3 shrink-0">
<img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDp7AJEQ76C6MOzJmciFpvV6P9aqIdHHv4Vd9QVKOPW98sClvZ0GNR2RU8Mc3Ughath9lIYw-AHOAXYaIq_eKKmsooyGXm2VMbD0JmmXqj-1HISrnb6TkNyAh4F8MSHx-4-JK9KpteTUjfo_toYq9Pr5HPV8vzUYMtgjLs-MgTPRCw2AL_axsEFbWFdHIdXnw4_DVQYUebd14bVErpTKQwR_jMJ1QogQFrk1UA2KjmskKDrDPquRfE2ByUvMCV_pRDaPgM" alt="Kueku Marinda Logo" class="w-14 h-14 rounded-full object-cover shadow-sm">
<span class="font-serif text-2xl font-bold text-gray-900 tracking-tight">Kueku Marinda</span>
</div>
<!-- Search Input Bar -->
<div class="flex-1 max-w-md mx-4">
<div class="relative">
<input class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-full text-sm focus:ring-2 focus:ring-maroon text-gray-800 placeholder-gray-400 transition" placeholder="Cari..." type="text">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
</div>
<!-- Navigation Links & User Profile -->
<nav class="flex items-center space-x-6 shrink-0">
<a class="text-maroon font-semibold text-sm hover:text-maroon-700 transition" href="#beranda">Beranda</a>
<a class="text-gray-600 hover:text-maroon text-sm font-medium transition flex items-center gap-1" href="#keranjang">
<span class="">keranjang</span>
<span class="inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-maroon rounded-full">2</span>
</a>
<a class="text-gray-600 hover:text-maroon text-sm font-medium transition" href="#favorite">Favorite</a>
<a class="text-gray-600 hover:text-maroon text-sm font-medium transition" href="#pesanan">Pesanan</a>
<!-- User Profile Button -->
<a class="ml-2 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium transition" href="#profil">
<svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
<path clip-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" fill-rule="evenodd"></path>
</svg>
<span class="">Profil pelanggan</span>
</a>
</nav>
</div>
</header>
<!-- END: MainHeader -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
<!-- BEGIN: HeroSection -->
<section class="bg-gradient-to-br from-cream to-white rounded-3xl p-8 sm:p-12 border border-orange-100/60 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
<!-- Left Content: Headline & Description -->
<div class="md:w-1/2 space-y-5">

<h1 class="font-serif text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
          Cita Rasa Manis &amp; Lembut Istimewa
        </h1>
<p class="text-gray-600 text-base sm:text-lg leading-relaxed">
          Nikmati variasi roti lembut, cake ulang tahun elegan, bento cake estetik, dan minuman segar buatan tangan kami dengan bahan premium terbaik setiap hari.
        </p>
<div class="pt-2 flex items-center gap-4">
<a class="inline-flex items-center justify-center px-6 py-3 bg-maroon text-white text-sm font-semibold rounded-xl hover:bg-maroon-600 transition shadow-sm" href="#pilihan-menu">
            Pesan Sekarang
          </a>
<a class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border border-gray-200 text-sm font-semibold rounded-xl hover:bg-gray-50 transition" href="#pilihan-menu">
            Lihat Menu
          </a>
</div>
</div>
<!-- Right Content: Hero Showcase Image -->
<div class="md:w-1/2 w-full flex justify-center">
<div class="relative w-full max-w-lg aspect-[4/3] rounded-2xl overflow-hidden shadow-lg border border-white">
<img alt="Showcase Produk Kueku Marinda" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsTFZWv5Euy_IFZEFiz77jJcue5p3U_t9d4hS1aJFCsWqRTLrEV-JhR3rBAHFja57sNyBNMK_GiIS4eJZoH0_C3oKksXwcQBhx8wFMx1ksS7K-y3MD3B54RIOzXHHnIJtunFKTGGDj6x-7Rd57g-SY4ik8lzK2bMTpnJiIcTZcr0CDQl260nHz8Tu9Fq6WOQaAFS4fDw4pqBeKqIJAKWipfJrZfkmf1B_YuAH9RR-07WTV88ge4WJYzA">
</div>
</div>
</section>
<!-- END: HeroSection -->
<!-- BEGIN: PaymentInfoBanner -->
<section class="bg-gray-100/90 rounded-2xl p-5 border border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-maroon shadow-xs shrink-0">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
<div>
<h2 class="font-semibold text-gray-800 text-sm sm:text-base">Deskripsi mengenai pembayaran</h2>
<p class="text-xs text-gray-500"></p><p class="">Pembayaran dilakukan secara tunai (cash). Untuk pesanan <strong>Ambil di Toko</strong>, pembayaran dilakukan saat pelanggan mengambil pesanan di toko Kueku Marinda, sedangkan untuk pesanan <strong>Diantar</strong>, pembayaran dilakukan secara tunai kepada kurir saat pesanan diterima.</p><p class=""></p>
</div>
</div>
<div class="flex items-center gap-2 text-xs font-medium text-maroon bg-white px-3 py-1.5 rounded-lg border border-gray-200 shrink-0">
<span class="">Pengiriman Cepat &amp; Higienis</span>
</div>
</section>
<!-- END: PaymentInfoBanner -->
<!-- BEGIN: MenuNavigationTabs -->
<section class="space-y-4 pt-2" id="pilihan-menu">
<h2 class="font-serif text-2xl font-bold text-gray-900">Pilihan menu</h2>
<!-- Horizontal Navigation Tabs -->
<div class="flex items-center gap-6 sm:gap-8 border-b border-gray-200 overflow-x-auto pb-3 text-sm font-semibold whitespace-nowrap">
<a class="text-maroon border-b-2 border-maroon pb-3 -mb-3 transition" href="#best-seller">Best Seller</a>
<a class="text-gray-500 hover:text-maroon transition pb-3" href="#roti">Roti</a>
<a class="text-gray-500 hover:text-maroon transition pb-3" href="#cake-medium">cake medium 15cm</a>
<a class="text-gray-500 hover:text-maroon transition pb-3" href="#bento-cake">bento cake 10cm</a>
<a class="text-gray-500 hover:text-maroon transition pb-3" href="#minuman">Minuman</a>
</div>
</section>
<!-- END: MenuNavigationTabs -->
<!-- BEGIN: BestSellerSection -->
<section class="space-y-6 pt-4" id="best-seller">
<div class="flex items-baseline justify-between">
<h3 class="font-serif text-2xl font-bold text-gray-900">Best Seller</h3>

</div>
<!-- 3 Products Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
<!-- Product Card 1 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Red Velvet Delight" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAB6twuMx6X5vLSGThOpa6CdYxNcfNexgJ8NKCMJif6OyJ0d-nHV86Hgwt8yO6EDr6cY5HqS00Jqg9Ghic9XqT88cXZSnoRS6afnNG_Ez149xYx8EkQ3BjxeewQR3R_D8qZFnoHsZumWpfHUc3FevHxcBtcumLiP5aew2HR1oNC9kcKFsIt9e_QJSO87HqapdJ1ggQsK4SXWxjFqDngFZoiYN6Ws6hVh_2ipRIkd-HpghlfV6K91_a13Q">
<!-- Favorite Love Button -->
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Red Velvet Classic</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Sponge cake lembut berlapis cream cheese istimewa dengan crumble manis renyah.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Roti</span>
<span class="text-sm font-bold text-maroon">Rp 145.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Product Card 2 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Belgian Chocolate Fudge" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD6tRjJXfNhjvB7KMfuPaSY-SgFkCrARIFDtNe6gwqbKzvWkUDyP91MfHopgb-_xsMeHebdyiMWxFYtn9S7SZ2o2nPqA-f17QnL-utN3Cn-oq-MnDNaZOpU19UITmaLv3Yqpf1ghx7AFwVbsen0r_n-Kj-hyt7uGihym5Mht9AH1izw66kGhWlvrhcJ8RlERefNndm_8iLg9SMWCbfj_xypHxYY_6r5YNEQrjuuQcRwMC3AnCHrt5V0gQ">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Belgian Chocolate Fudge</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Cokelat murni Belgia 70% kaya rasa dengan tekstur lumer di lidah.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Bento Cake 10cm</span>
<span class="text-sm font-bold text-maroon">Rp 160.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Product Card 3 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Japanese Cheesecake" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbGXwM96TYStri23ldB197nRxDjpHpVjdF3dBPtRnztpH5zME7eynN6jxAaGD17j33-O3qqcllYy3Frf2H33M0zkL5cxkZE2Fki4jfKy6669FB2U6jZ7TNahuvG-giKuo0445k-377PpzTKIMVkaUwzQuqjyNg0wex-_2Sw1ZXKpDqUPGUTvo52rPtPomSD8MHg2tjlrStNg-L7yREpRvdIbnTOioevv6syb71xP5gUQumvQxcDcyk3g">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Japanese Cotton Cheese</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Cheesecake lembut selembut awan beraroma susu murni dan vanila bourbon.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Minuman</span>
<span class="text-sm font-bold text-maroon">Rp 125.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
</div>
</section>
<!-- END: BestSellerSection -->
<!-- BEGIN: RotiSection -->
<section class="space-y-6 pt-4" id="roti">
<div class="flex items-baseline justify-between">
<h3 class="font-serif text-2xl font-bold text-gray-900">Roti</h3>

</div>
<!-- 3 Products Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
<!-- Roti 1 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Roti Sisir Butter" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDcro1GjZV4Kgh0491hD4yl2Hx7IX2Ma-NeY6sKmFTV2SEMkIAYWxzno421ORQ4QQnW4cPwNgXmFmDzen7mMs5u0LIXK1zXZ6R1VXHcTjfdS-i2rkG-mrXAtDWHuueLAX64Ntm9kNp6a72z8rTol4NhKfzOFO56QBDAjU0ggiiME8rpEsmSS0Pvns-JfYHwXMcpdpPY_aNgL-kzDLVSO9A42I_7EaACdDta1aivoER9d1L_20KRVK2PwA">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Roti Sisir Classic Butter</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Roti sisir jadul ultra empuk dengan lelehan mentega New Zealand harum.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Roti</span>
<span class="text-sm font-bold text-maroon">Rp 28.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Roti 2 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Korean Garlic Cheese Bread" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmYsjolomW7IRkOD5nsoTZ1nWZw3vY6euuCVILNWUIbAqJsuS9cHgpCaJbK_u3Sv2EA4Z1KSLHNOWOPXsQBeoAL6aZ8R9DSy3uNUT-idJn2dD2Z-QCvTLTKQ9Gur9K_M-CjRljA9i_DGSzKR-FJEriwzaoW10oHj61ujZ2BTtfi0WiexbtsUUQX17cTKTIfJhbKQ8VhJrxeTZYnBFyCXqgz7Q1xTnYMtX50yESvlOzYMiM9kCSizPhhQ">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-maroon text-white flex items-center justify-center shadow transition">
<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Korean Garlic Cream Cheese</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Bun gurih renyah di luar, berpadu cream cheese creamy dan saus garlic gurih.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Roti</span>
<span class="text-sm font-bold text-maroon">Rp 35.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Roti 3 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Artisan Butter Croissant" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDxYLojQ_8SE_R50m8iPFu8zteN5a_Nxh19z1KajebujS9lFIU0Imz_VT1tSsf7mMW13nr6uJPhu0AV_ePmSlwYGwCAkH0SN25uA-hj8vYnATBlekCWYtcsEmALhmMbxXoNCgV_giWesCNy8MPipFqAyP_vR5UtdVewRP_IDHp57Frc_tWbTc9qG5XS_4MieNvQVscEPhsj71dahMl_nGUl17etnAIKiUQzwB8dMoKucASFTYLzmLlidQ">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Butter French Croissant</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Pastry berlapis renyah keemasan, aroma mentega harum dengan tekstur berongga sempurna.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Roti</span>
<span class="text-sm font-bold text-maroon">Rp 26.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
</div>
</section>
<!-- END: RotiSection -->
<!-- BEGIN: CakeMediumSection -->
<section class="space-y-6 pt-4" id="cake-medium">
<div class="flex items-baseline justify-between">
<h3 class="font-serif text-2xl font-bold text-gray-900">Cake medium 15cm</h3>

</div>
<!-- 3 Products Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
<!-- Cake 1 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Strawberry Shortcake 15cm" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfIl7ZYm7Tsg_t80JpM7O9teXIn5UgqEFCtEUsRUN4mt7CRnLzDnZB2xurkUtyUJ5oBOgM-dv7aSIzw0jyJlAYSMjjccaZlH_tl8mOu5QpEvtk_wA6eXMDFnOsxVyIp25Kq7PQ7H4qdPNsh3ch0BACb8MtoNVwuSN4eDULxf9He2GpLKdgd_wywSW5ICq90x9OqieoOTI5MiqwTjQZothIH4XguGOyoaTVuosYqNHBO2eVMgL7TCGBeA">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Strawberry Bloom Cake 15cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Sponge cake lembut dengan buah strawberry segar pilihan dan fresh chantilly cream.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Cake Medium 15cm</span>
<span class="text-sm font-bold text-maroon">Rp 185.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Cake 2 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Tiramisu Classico 15cm" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPtKGIRpu8PKthviTqGhzE0ED6IUivILmOVE_i2AvEsPUl5Bfn2Kgbs0MaOkKqDpRK4PEZvjnyKIhVyHZBnpcI_xUygz2nJTZvioMnIhgYo6M5bL4G0mjdUa7r3JdWcGwKewsP9_BSblPfjhiztZfq_y27cT3agZbBNLAeqf9RpMojQYiUbuxXjhshcorOHtDKbf8owOy1ifc3qD5Mig7AW482o5VEFLJoIeuYuFoRDpfVNlMAEfDt2w">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Tiramisu Classico 15cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Resep Italia autentik dengan mascarpone keju halus dan celupan espresso premium.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Cake Medium 15cm</span>
<span class="text-sm font-bold text-maroon">Rp 195.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Cake 3 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Lotus Biscoff Cake 15cm" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARz3lNO-a8uYInK5RD1CnAIVugZhD1asLyft6SQ-3V7VYB3anpu58WG3Vf8O7qK6dJVRDBVqfMzrwj1ARXLaIQ-4uOvalJ3scmiqf1JuDzAKO-NRq-Ih1P3v5KrfwbDzbcx7JR-WX29lCK4RTboi9Pd7t2EMT1GibzAdZP1h7yF1Gxfupm5lF6tBmDGkk7dYuPe6UtA6cQQAL-9QYU9BVDV5XGcJ5Q1BI8Fy7NxbYAdsZneumkaHhM4Q">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Lotus Biscoff Delight 15cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Perpaduan selai karamel manis speculoos dan taburan biskuit lotus crunchy.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Cake Medium 15cm</span>
<span class="text-sm font-bold text-maroon">Rp 190.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
</div>
</section>
<!-- END: CakeMediumSection -->
<!-- BEGIN: BentoCakeSection -->
<section class="space-y-6 pt-4" id="bento-cake">
<div class="flex items-baseline justify-between">
<h3 class="font-serif text-2xl font-bold text-gray-900">Bento cake 10cm</h3>

</div>
<!-- 3 Products Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
<!-- Bento 1 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Cute Korean Bento Cake" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwJMjqlBn8jQkKQsSLLJvdjXCXC3YEmdDV5e5RvcWhJi40hQ5DOQLraAMGYI_bkXZkgdYR3V9bEODtgwYYR85tccmeT7uFmZhHtKkK0QHI8-0Qu_OI-cPuVYbVZOKbvm5lQQ8MvqBgsXI5q-93wSLGJCf87Pw8r2YVgvHWCjrGXluoNvxh5b-TB0zhUBslnA4bU7RVYSCSafwhPW3OZwLCUStWnoFN3s-MAp1izQwLLWnGYyPRD-k5yA">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Korean Pastel Bento 10cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Bento cake estetik minimalis dengan butter cream lembut, bisa custom ucapan mini.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Bento Cake 10cm</span>
<span class="text-sm font-bold text-maroon">Rp 55.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Bento 2 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Daisy Yellow Bento" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDt-VjMY-arUOx5iWB6fkfaqvmSvFOr11wImxlNBmFDtJyimfPt-PdHUM8QpEFS3y_QdRRMwSYqfN-EtvLC147Ix7QReKmxq5iYmfxvc6Mq0qDFnOeWWof8G4uL5Frm5Fb4FW7PGRMEjURYVz5EtB3GYoHNKrtEd3Xx4qGRa1qFYy82DftrCzPgd9dvFvI9mXScmD7lRhOOoBK7Cwr4o0KEasm1vwRnLJijBODT8AyfW4I6ZRpWLdG9oQ">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Daisy Garden Bento 10cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Kue mini motif bunga daisy ceria rasa vanilla bean dengan filling selai berry.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Bento Cake 10cm</span>
<span class="text-sm font-bold text-maroon">Rp 55.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Bento 3 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Choco Mood Bento" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyelsru0GyqpO-7HnnSGDWC29Mswtrs5uRAqo1wpl20YcgGP9UCHxDZGDpd523IHS0n-GH_XZEU6H0u2bfwjBiHvYpEXYWYkJrdOdubzwOoMKO7ppR8FYSWmsg9HE9UB-7u-4R20irDxbJajHLjvKH14itjpFnMUOfS3TUZv727iolQtSIMQ-sHCA4RILlwgWh8q0WjnB_ZZoB7GjQ2oKtUzACenDc5zLCtoS6KpoYRzq5HsQSm8k4lw">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Dark Choco Bento 10cm</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Sponge cokelat pekat nikmat dengan ganache silky, pas untuk hadiah personal.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Bento Cake 10cm</span>
<span class="text-sm font-bold text-maroon">Rp 58.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
</div>
</section>
<!-- END: BentoCakeSection -->
<!-- BEGIN: MinumanSection -->
<section class="space-y-6 pt-4 pb-12" id="minuman">
<div class="flex items-baseline justify-between">
<h3 class="font-serif text-2xl font-bold text-gray-900">Minuman</h3>

</div>
<!-- 2 Products Grid according to wireframe -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
<!-- Minuman 1 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Iced Matcha Latte" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDY7IZjb42P-nqSAmW0o9dniw85BLUXDDvxnyB6dHpM-FOXIuy5zWx5w3HCkH2IkPz88YUZxHCAaOEIem042UzA1-sR6rGhMP_Rvcm8H3zobliO6us8LbBmJw_94ekRLpeED-5OgE97WNafH44rdUbJyCOBI9DOVcO-FobcXRHvMCIvhpBXQTfR1zbh-IQyIt-WSn_DQ6cn68OCT0aHytCW8MPy4BxC7rKxKGNHpOKYvqUqbJnVH_FQOw">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Uji Matcha Iced Latte</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Bubuk matcha murni asal Kyoto dengan fresh milk creamy yang menyegarkan.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Minuman</span>
<span class="text-sm font-bold text-maroon">Rp 32.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
<!-- Minuman 2 -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition">
<div class="relative w-full aspect-[4/3] bg-gray-100">
<img alt="Caramel Macchiato" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuACIV1HJYmk7yevd4eJZz3U1_vOhJgGELa1vphfJZfXcYU88NA24uQ34eAJz6o2ybQIvwHm4vz3xsHNxbZzI5RCnjPdpjktIo6vyOpMQWuw7Y4VnfkMT1bHthLDHyrJOpMH8Xv9mxXI_MM2RjA38DpKhHStoukcMr8m3N6frzX0eANWB4wAqL8voMHk2I7qdccUUE08uHHtZlcZ0DONrOdeCIiiO9Rc30ylZTAn7pZQ97YN9IchSyYubA">
<button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-maroon text-white flex items-center justify-center shadow transition">
<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
</svg>
</button>
</div>
<div class="p-4 flex-1 flex flex-col justify-between space-y-3">
<div>
<h4 class="font-semibold text-gray-900 text-base">Salted Caramel Macchiato</h4>
<p class="text-xs text-gray-500 mt-1 line-clamp-2">Espresso house blend arabika dengan sirup vanilla dan saus salted caramel gurih.</p>
</div>
<div class="pt-2 border-t border-gray-100 flex items-center justify-between">
<span class="text-xs text-gray-400 font-medium">Minuman</span>
<span class="text-sm font-bold text-maroon">Rp 34.000</span>
</div>
<button class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
<span class="">tambah ke keranjang</span>
</button>
</div>
</div>
</div>
</section>
<!-- END: MinumanSection -->
</main>
<!-- BEGIN: Footer -->
<footer class="bg-white border-t border-gray-200 py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
<div class="flex items-center gap-2">
<span class="font-serif font-bold text-gray-900 text-sm">Kueku Marinda</span>
<span class="">© 2025. Seluruh hak cipta dilindungi.</span>
</div>
<div class="flex items-center space-x-6">
<a class="hover:text-maroon" href="#">Syarat &amp; Ketentuan</a>
<a class="hover:text-maroon" href="#">Kebijakan Privasi</a>
<a class="hover:text-maroon" href="#">Bantuan &amp; FAQ</a>
</div>
</div>
</footer>
<!-- END: Footer -->




</body></html>
