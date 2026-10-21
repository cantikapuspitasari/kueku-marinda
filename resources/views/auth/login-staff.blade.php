<!DOCTYPE html><html lang="id" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Masuk Pengelola - Sistem Internal | Kueku Marinda</title>
<!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Tailwind CSS v3 -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Tailwind Configuration -->
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              maroon: '#993A3C',
              'maroon-hover': '#7f2f31',
              'maroon-dark': '#632123',
              'maroon-light': '#faecec',
              cream: '#FAF7F2',
              'cream-dark': '#F0ECE1',
              charcoal: '#2D2926',
              muted: '#716B67'
            }
          },
          fontFamily: {
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
            sans: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          boxShadow: {
            'soft': '0 20px 40px -15px rgba(153, 58, 60, 0.08), 0 0 15px rgba(0, 0, 0, 0.03)',
            'card': '0 25px 50px -12px rgba(99, 33, 35, 0.1), 0 0 25px rgba(0, 0, 0, 0.03)'
          }
        }
      }
    }
  </script>
<!-- Custom CSS for specialized patterns and transitions -->
<style data-purpose="custom-styling">
    .bg-pattern {
      background-color: #FAF7F2;
      background-image: radial-gradient(#e5ddd2 1px, transparent 1px);
      background-size: 24px 24px;
    }

    .border-warm {
      border-color: #EDE8DE;
    }

    .focus-ring-maroon:focus-within {
      border-color: #993A3C;
      box-shadow: 0 0 0 3px rgba(153, 58, 60, 0.15);
    }
  </style>
</head>
<body class="bg-pattern text-brand-charcoal font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-brand-maroon selection:text-white">
<!-- BEGIN: TopHeaderNav -->
<header class="w-full px-6 py-5 max-w-7xl mx-auto flex items-center justify-between">
<a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-stone-600 hover:text-brand-maroon transition-colors group">
    <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
    <span class="">Kembali ke Beranda</span></a>
<!-- Badge Portal Internal -->
<div class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-maroon-light border border-red-100 text-brand-maroon text-xs font-semibold tracking-wide uppercase">
<span class="w-2 h-2 rounded-full bg-brand-maroon animate-pulse"></span>
      Sistem Pengelola Internal
</div>
</header>
<!-- END: TopHeaderNav -->
<!-- BEGIN: MainContent -->
<!-- Kontainer utama layout card split dua kolom -->
<main class="flex-grow flex items-center justify-center px-4 py-6 sm:py-10">
<div class="w-full max-w-5xl">
<!-- Card Box Utama Split Landscape Layout -->
<div class="bg-white rounded-3xl shadow-card border border-warm overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[620px]">
<!-- Kolom Kiri: Brand Showcase & Role Information -->
<div class="lg:col-span-5 bg-[#FBF8F3] border-b lg:border-b-0 lg:border-r border-warm p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
<!-- Ornamen visual halus -->
<div class="absolute -top-12 -left-12 w-48 h-48 bg-brand-maroon/5 rounded-full blur-2xl pointer-events-none"></div>
<div class="absolute -bottom-12 -right-12 w-48 h-48 bg-amber-100/40 rounded-full blur-2xl pointer-events-none"></div>
<!-- Header Badge Kiri -->
<div class="relative z-10">
<div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-stone-200/80 shadow-xs text-[11px] font-bold text-brand-maroon tracking-wider uppercase">
<span class="w-2 h-2 rounded-full bg-brand-maroon"></span>
        Portal Internal &amp; Operasional
      </div>
</div>
<!-- Logo & Deskripsi Tengah -->
<div class="my-8 text-center relative z-10 flex flex-col items-center">
<div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full p-1 bg-white shadow-md border border-warm/80 mb-5 transition-transform hover:scale-105 duration-300">
<img alt="Logo Resmi Kueku Marinda" class="w-full h-full object-contain rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAC9sVRvC7EhftMio22BPy5WEzUbg-lZ6Q0iQowPkAFoN5SQ-Q4CBXV1Hg26F4ejbQQTfotuC9juLlKlE9V40K23mAIyjsf_Q7neF4ipxAhrUZspD2oggWaKHS4KEoQtYMH27rPv9Idz-wb4RW3YCGOZcsDfVmVByGerSq21VqN4pzq3bCzfrCpWB9AsZtLy61JGa9-NeLJsVnTV6gvyJZRmkGKSsUK9vSpj0Tpt1tj421PBWBObT0Jog">
</div>
<h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal tracking-tight">
        Kueku Marinda
      </h2>
<p class="text-xs sm:text-sm text-brand-muted mt-3 leading-relaxed max-w-sm px-2">
        Akses khusus staf internal untuk mengelola pesanan, inventaris kue, katalog menu, dan pengiriman Kueku Marinda.
      </p>
</div>
<!-- Highlight Role Internal di Bawah -->
<div class="relative z-10 pt-4 border-t border-stone-200/60">
<p class="text-[11px] font-bold uppercase tracking-wider text-stone-500 mb-2.5 text-center">Hak Akses Tersedia</p>
<div class="flex flex-wrap items-center justify-center gap-2">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-warm text-xs font-semibold text-brand-charcoal shadow-2xs">
<i class="fa-solid fa-clipboard-check text-[10px] text-brand-maroon"></i>
          Admin <span class="text-stone-400 font-normal">(Kelola Pesanan)</span>
</span>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-warm text-xs font-semibold text-brand-charcoal shadow-2xs">
<i class="fa-solid fa-chart-line text-[10px] text-brand-maroon"></i>
          Owner <span class="text-stone-400 font-normal">(Laporan &amp; Bisnis)</span>
</span>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-warm text-xs font-semibold text-brand-charcoal shadow-2xs">
<i class="fa-solid fa-truck-fast text-[10px] text-brand-maroon"></i>
          Kurir <span class="text-stone-400 font-normal">(Pengiriman)</span>
</span>
</div>
</div>
</div>
<!-- Kolom Kanan: Formulir Login Pengelola -->
<div class="lg:col-span-7 bg-white p-8 sm:p-12 flex flex-col justify-between">
<div>
<!-- Badge dan Heading -->
<div class="mb-6 sm:mb-8">
<div class="inline-block px-3 py-1 rounded-md bg-stone-100 text-stone-600 font-bold text-[11px] tracking-widest uppercase mb-2.5">
          PORTAL PENGELOLA
        </div>
<h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal tracking-tight">
          Masuk Pengelola
        </h1>
<p class="text-brand-muted text-sm mt-1.5 leading-relaxed">
          Silakan masuk dengan kredensial akun internal Anda untuk mulai mengelola sistem.
        </p>
</div>
<!-- BEGIN: LoginForm -->
<form class="space-y-5" data-purpose="auth-form" method="POST" action="{{ url('/login') }}">
@csrf
@if ($errors->any())
<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>
@endif
<!-- Field 1: Username / Email -->
<div>
<label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2" for="username">
            Username / Email
          </label>
<div class="relative flex items-center rounded-xl border border-stone-200 bg-stone-50/50 transition-all focus-ring-maroon">
<span class="pl-4 text-stone-400">
<i class="fa-regular fa-user text-sm"></i>
</span>
<input class="w-full bg-transparent border-0 py-3.5 pl-3 pr-4 text-sm text-brand-charcoal placeholder:text-stone-400 focus:ring-0" id="username" name="email" value="{{ old('email') }}" placeholder="Masukkan email staf" required="" type="email">
</div>
</div>
<!-- Field 2: Password -->
<div>
<div class="flex items-center justify-between mb-2">
<label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal" for="password">
              Password
            </label>
</div>
<div class="relative flex items-center rounded-xl border border-stone-200 bg-stone-50/50 transition-all focus-ring-maroon">
<span class="pl-4 text-stone-400">
<i class="fa-solid fa-lock text-sm"></i>
</span>
<input class="w-full bg-transparent border-0 py-3.5 pl-3 pr-11 text-sm text-brand-charcoal placeholder:text-stone-400 focus:ring-0" id="password" name="password" placeholder="Masukkan password" required="" type="password">
<!-- Toggle Mata Password -->
<button aria-label="Tampilkan atau sembunyikan password" class="absolute right-3.5 text-stone-400 hover:text-brand-charcoal transition-colors p-1" data-purpose="password-toggle" id="togglePassword" type="button">
<i class="fa-regular fa-eye text-sm" id="toggleIcon"></i>
</button>
</div>
</div>
<!-- Pilihan Opsi: Ingat Saya & Lupa Sandi -->
<div class="flex items-center justify-between text-xs pt-1">
<label class="flex items-center cursor-pointer select-none">


</label>
<a class="font-semibold text-brand-maroon hover:text-brand-maroon-hover transition-colors" href="#">
            Lupa Password?
          </a>
</div>
<!-- Tombol Aksi Submit: Masuk -->
<div class="pt-2">
<button class="w-full py-3.5 px-6 rounded-xl bg-brand-maroon hover:bg-brand-maroon-hover active:bg-brand-maroon-dark text-white font-bold tracking-wider text-sm transition-all shadow-md shadow-brand-maroon/20 hover:shadow-lg hover:shadow-brand-maroon/30 flex items-center justify-center gap-2 group cursor-pointer" type="submit">
<span class="">[ MASUK ]</span>
<i class="fa-solid fa-arrow-right-to-bracket text-xs transition-transform group-hover:translate-x-1"></i>
</button>
</div>
</form>
<!-- END: LoginForm -->
</div>
<!-- Catatan Footer Form & Navigasi -->

</div>
</div>
<!-- Navigasi Bawah Kembali ke Pilihan Akun -->
<div class="mt-6 text-center">

</div>
</div>
</main>
<!-- END: MainContent -->
<!-- BEGIN: PageFooter -->
<footer class="w-full py-5 text-center text-xs text-stone-400">
<div class="max-w-7xl mx-auto px-4">
<p class="">© 2018 – 2026 <span class="font-semibold text-stone-500">Kueku Marinda</span>. Seluruh Hak Cipta. • Bake with Passion</p>
</div>
</footer>
<!-- END: PageFooter -->
<!-- BEGIN: InteractiveScripts -->
<script data-purpose="password-visibility-toggle">
    // Menghandle interaktivitas tampilan toggle password (show/hide)
    const toggleButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (toggleButton && passwordInput && toggleIcon) {
      toggleButton.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        // Ubah ikon mata
        if (isPassword) {
          toggleIcon.classList.remove('fa-eye');
          toggleIcon.classList.add('fa-eye-slash');
        } else {
          toggleIcon.classList.remove('fa-eye-slash');
          toggleIcon.classList.add('fa-eye');
        }
      });
    }
  </script>
<!-- END: InteractiveScripts -->






</body></html>
