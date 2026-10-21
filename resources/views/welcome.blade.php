<!DOCTYPE html>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pilih Jenis Akun - Kueku Marinda</title>
<!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;family=Playfair+Display:ital,wght@0,600;0,700;1,600&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            maroon: {
              50: '#fdf4f4',
              100: '#fae8e9',
              200: '#f6d5d7',
              600: '#b14548',
              700: '#993A3C', // Brand Primary Maroon
              800: '#812f31',
              900: '#682628',
            },
            cream: {
              50: '#FDFCF7',
              100: '#FAF7EE',
              200: '#F3EDE0',
              300: '#EADFCB'
            }
          },
          fontFamily: {
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
            sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
          },
          boxShadow: {
            'warm': '0 20px 40px -15px rgba(153, 58, 60, 0.08), 0 8px 16px -6px rgba(45, 27, 24, 0.04)',
            'warm-hover': '0 25px 50px -12px rgba(153, 58, 60, 0.16), 0 12px 24px -6px rgba(45, 27, 24, 0.08)'
          }
        }
      }
    }
  </script>
<!-- BEGIN: Custom CSS -->
<style data-purpose="background-pattern">
    /* Subtle decorative background pattern reflecting warm bakery flour dust and pastry texture */
    body {
      background-color: #FAF7EE;
      background-image:
        radial-gradient(circle at 15% 20%, rgba(153, 58, 60, 0.035) 0%, transparent 40%),
        radial-gradient(circle at 85% 80%, rgba(153, 58, 60, 0.04) 0%, transparent 45%);
      min-height: 100vh;
    }
  </style>
<style data-purpose="transitions-and-animations">
    .card-transition {
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .card-transition:hover {
      transform: translateY(-4px);
    }
  </style>
<!-- END: Custom CSS -->
</head>
<body class="font-sans text-stone-800 antialiased flex flex-col justify-between min-h-screen">
<!-- BEGIN: TopNavigation -->
<header class="w-full max-w-7xl mx-auto px-6 py-6 sm:px-8 flex items-center justify-end" data-purpose="top-navigation-bar">
<!-- Back to Home Button -->

<!-- Support / Help badge -->
<div class="hidden sm:flex items-center text-xs tracking-wider uppercase font-semibold text-stone-400 bg-stone-100/80 px-3.5 py-1.5 rounded-full border border-stone-200">
      Portal Akses Resmi
    </div>
</header>
<!-- END: TopNavigation -->
<!-- BEGIN: MainContent -->
<main class="flex-grow flex items-center justify-center px-4 py-8 sm:py-12" data-purpose="account-selection-container">
<div class="w-full max-w-xl mx-auto">
<!-- Header & Brand Presentation -->
<section class="text-center mb-8 sm:mb-10" data-purpose="brand-header">
<!-- Logo Image -->
<div class="inline-block relative mb-4">
<img alt="Logo Kueku Marinda" class="w-24 h-24 sm:w-28 sm:h-28 mx-auto rounded-full object-cover shadow-md border-2 border-white ring-4 ring-maroon-700/10" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDrLyF8gPpCaMd_JrG-ImE25g_1GZ3cekpIKAmbHu10wsVvbhMtisrLqrX5UYfa6-ziXoh4Q8-8aZSh_4Ip2mMNAPodJmpVtVWajs4e-ruCqLBJneqY2AML8IhPFmEXksqiqANODkEXqffspP_bpZaBMQ6zGQJZPkttoEqNTpQyShyjp1rOhXBA2olZ_wsqnlDanhHuIIUWbILcBxP8xsGLQYgmBYzkdz5QuOseBKJ7w8fHMbTWwzuc2w">
</div>
<p class="text-xs sm:text-sm font-bold uppercase tracking-widest text-maroon-700 mb-1">
          Kueku Marinda
        </p>
<h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-stone-900 tracking-tight mb-2">
          Selamat Datang <span class="inline-block transform hover:rotate-12 transition-transform cursor-default" title="Halo!">👋</span>
</h1>
<p class="text-stone-600 text-sm sm:text-base max-w-sm mx-auto">
          Silakan pilih jenis akun untuk melanjutkan
        </p>
</section>
<!-- Account Selection Cards Grid -->
<section aria-label="Pilihan Jenis Akun" class="space-y-4" data-purpose="role-selection-options">
<!-- BEGIN: OptionPelanggan -->
<a class="card-transition group block bg-white rounded-2xl p-5 sm:p-6 border-2 border-stone-200/90 hover:border-maroon-700 shadow-warm hover:shadow-warm-hover cursor-pointer relative overflow-hidden" data-purpose="customer-option-card" href="{{ route('login') }}" id="role-customer-link">
<!-- Subtle Accent Stripe on Left -->
<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-transparent group-hover:bg-maroon-700 transition-colors"></div>
<div class="flex items-center justify-between gap-4">
<div class="flex items-center gap-4 sm:gap-5">
<!-- Customer Avatar Icon Container -->
<div class="w-14 h-14 rounded-2xl bg-maroon-50 text-maroon-700 flex items-center justify-center shrink-0 group-hover:bg-maroon-700 group-hover:text-white transition-colors duration-200">
<svg aria-hidden="true" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<!-- Content Text -->
<div class="text-left">
<div class="flex items-center gap-2 mb-1">
<span class="text-base sm:text-lg font-bold text-stone-900 group-hover:text-maroon-700 transition-colors tracking-wide">
                    PELANGGAN
                  </span>
<span class="text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80 px-2 py-0.5 rounded-full">
                    Publik
                  </span>
</div>
<p class="text-stone-500 text-xs sm:text-sm font-medium">
                  Pesan kue &amp; lihat pesanan Anda
                </p>
</div>
</div>
<!-- Action Arrow Indicator -->
<div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-stone-50 text-stone-400 group-hover:bg-maroon-700 group-hover:text-white transition-all">
<svg aria-hidden="true" class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
</a>
<!-- END: OptionPelanggan -->
<!-- BEGIN: OptionPengelola -->
<a class="card-transition group block bg-white rounded-2xl p-5 sm:p-6 border-2 border-stone-200/90 hover:border-maroon-700 shadow-warm hover:shadow-warm-hover cursor-pointer relative overflow-hidden" data-purpose="manager-option-card" href="{{ route('staff.login') }}" id="role-manager-link">
<!-- Subtle Accent Stripe on Left -->
<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-transparent group-hover:bg-maroon-700 transition-colors"></div>
<div class="flex items-center justify-between gap-4">
<div class="flex items-center gap-4 sm:gap-5">
<!-- Internal Staff / Gear Icon Container -->
<div class="w-14 h-14 rounded-2xl bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 group-hover:bg-maroon-700 group-hover:text-white transition-colors duration-200">
<svg aria-hidden="true" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<!-- Content Text -->
<div class="text-left">
<div class="flex items-center gap-2 mb-1">
<span class="text-base sm:text-lg font-bold text-stone-900 group-hover:text-maroon-700 transition-colors tracking-wide">
                    PENGELOLA
                  </span>
<span class="text-[11px] font-semibold bg-stone-100 text-stone-600 border border-stone-200 px-2 py-0.5 rounded-full">
                    Internal
                  </span>
</div>
<p class="text-stone-500 text-xs sm:text-sm font-medium">
                  Admin, Owner, Kurir
                </p>
</div>
</div>
<!-- Action Arrow Indicator -->
<div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-stone-50 text-stone-400 group-hover:bg-maroon-700 group-hover:text-white transition-all">
<svg aria-hidden="true" class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
</svg>
</div>
</div>
</a>
<!-- END: OptionPengelola -->
</section>
<!-- Bottom Assistance Note -->
<aside class="mt-8 text-center text-xs text-stone-500" data-purpose="security-note">
<p class="">Memerlukan bantuan teknis? Hubungi <a class="font-semibold text-maroon-700 hover:underline" href="#">Tim Dukungan Toko</a></p>
</aside>
</div>
</main>
<!-- END: MainContent -->
<!-- BEGIN: MainFooter -->
<footer class="w-full py-6 text-center text-xs text-stone-500 border-t border-stone-200/60 bg-cream-50/50 backdrop-blur-sm" data-purpose="footer-section">
<div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
<p class="font-medium text-stone-600">
        © 2018 - 2026 <span class="font-semibold text-maroon-700">Kueku Marinda</span>. Seluruh Hak Cipta.
      </p>
<span class="hidden sm:inline text-stone-300">•</span>
<p class="italic text-stone-400 font-serif">
        "Bake with Passion"
      </p>
</div>
</footer>
<!-- END: MainFooter -->


<div id="snapdom-sandbox" data-snapdom-sandbox="true" aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px; width: 0px; height: 0px; overflow: hidden;"></div></body></html>
