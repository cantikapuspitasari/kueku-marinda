<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Masuk - Kueku Marinda | Bakery &amp; Cake Shop</title>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&amp;family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS CDN with forms -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            maroon: {
              DEFAULT: '#993A3C',
              50: '#FBF5F5',
              100: '#F5E8E8',
              200: '#E7C8C9',
              600: '#993A3C',
              700: '#842F31',
              800: '#6C2527',
              900: '#521B1C'
            },
            warm: {
              cream: '#FAF7F2',
              ivory: '#FFFDF9',
              accent: '#F3EDE2',
              border: '#E8DFD3'
            }
          },
          fontFamily: {
            serif: ['"Playfair Display"', 'Georgia', 'serif'],
            sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif']
          },
          boxShadow: {
            'warm-card': '0 20px 40px -15px rgba(153, 58, 60, 0.08), 0 0 1px 1px rgba(153, 58, 60, 0.05)',
            'btn-hover': '0 10px 20px -5px rgba(153, 58, 60, 0.35)'
          }
        }
      }
    }
  </script>
<style data-purpose="custom-decorations">
    /* Subtle decorative bakery background motif */
    .pattern-bg {
      background-color: #FAF7F2;
      background-image: radial-gradient(#993A3C 0.65px, transparent 0.65px), radial-gradient(#993A3C 0.65px, #FAF7F2 0.65px);
      background-size: 26px 26px;
      background-position: 0 0, 13px 13px;
      opacity: 0.96;
    }

    .floating-accent {
      filter: drop-shadow(0 12px 24px rgba(108, 37, 39, 0.12));
    }
  </style>
</head>
<body class="bg-[#FAF7F2] font-sans antialiased text-stone-800 min-h-screen flex items-center justify-center p-0 md:p-6 lg:p-10">
<!-- BEGIN: MainContainer -->
<main class="w-full max-w-7xl min-h-screen md:min-h-[820px] bg-white md:rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row border border-warm-border/60">
<!-- BEGIN: LeftBrandingShowcase -->
<section class="lg:w-1/2 relative bg-[#F7F2EA] flex flex-col justify-between p-8 sm:p-12 lg:p-14 overflow-hidden border-b lg:border-b-0 lg:border-r border-warm-border/50" data-purpose="brand-showcase">
<!-- Ambient Decorative Circles -->
<div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-[#EFE4D2]/60 blur-3xl pointer-events-none"></div>
<div class="absolute -bottom-28 -right-20 w-96 h-96 rounded-full bg-maroon-100/40 blur-3xl pointer-events-none"></div>
<!-- Top Header Row on Brand Side -->
<header class="relative z-10 flex items-center justify-between">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-stone-600 hover:text-maroon transition-colors group">
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path></svg><span class="">Kembali ke Beranda</span></a>

<span class="inline-block px-3 py-1 bg-maroon-50 text-maroon-700 text-xs font-medium rounded-full border border-maroon-200/60">
          Artisanal Bakery &amp; Pastry
        </span>
</header>
<!-- Center Logo & Brand Identity (Recreation of Wireframe Style) -->
<div class="relative z-10 my-auto py-10 flex flex-col items-center text-center">
<!-- Logo Emblem -->
<div class="relative group cursor-pointer mb-6">
<div class="absolute -inset-1.5 rounded-full bg-maroon-600/20 blur-md transition duration-500 group-hover:blur-lg"></div>
<img alt="Logo Kueku Marinda" class="relative w-44 h-44 sm:w-56 sm:h-56 rounded-full object-contain shadow-lg border-4 border-white transition transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzdK8ohjAmwrOhFUynR6q3jc4RRE6P7AJuKFeOnmNlvm000kTKGxl7xVu_peCWjyp-iR3Gkr13greWPJ-WsZ20UxroY8uAqicp279sXGLOnIz2QNp7e4dDJXZUB3ykNV3iSWfVqlZKW3IfmEWX07E95C1ImJsrvn9fB8rrFNZnr9wxOa9QktZiATjOIWEeDxnmj1vEullNdDw_WVDj5Xh8eaLcdT0_vvY4Xpq80IwkAZ6XU-uHhf9xFg">
</div>
<!-- Typography Heritage -->
<h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-maroon tracking-tight mb-3">
          Kueku Marinda
        </h1>
<p class="text-stone-600 text-sm sm:text-base max-w-sm leading-relaxed font-normal">
          Menghadirkan kehangatan cita rasa bolu, kue tart, dan pastry istimewa sejak 2018 dengan resep asli penuh cinta.
        </p>
<!-- Artisanal Badges -->
<div class="flex items-center justify-center gap-6 mt-6 pt-6 border-t border-stone-200/70 w-full max-w-xs">
<div class="text-center">
<span class="block text-maroon font-bold text-sm tracking-wide">100% Halal</span>
<span class="text-[11px] text-stone-500">Bahan Pilihan</span>
</div>
<div class="h-6 w-px bg-stone-300"></div>
<div class="text-center">
<span class="block text-maroon font-bold text-sm tracking-wide">Fresh Baked</span>
<span class="text-[11px] text-stone-500">Setiap Hari</span>
</div>
<div class="h-6 w-px bg-stone-300"></div>
<div class="text-center">
<span class="block text-maroon font-bold text-sm tracking-wide">Est. 2018</span>
<span class="text-[11px] text-stone-500">Kualitas Teruji</span>
</div>
</div>
</div>
<!-- Bottom Trust Note -->
<footer class="relative z-10 text-center lg:text-left text-xs text-stone-500 flex items-center justify-center lg:justify-between">
<span class="">© 2018 - 2026 Kueku Marinda. Seluruh Hak Cipta.</span>
<span class="hidden lg:inline text-maroon-700/80 font-medium">Bake with Passion&nbsp;</span>
</footer>
</section>
<!-- END: LeftBrandingShowcase -->
<!-- BEGIN: RightLoginForm -->
<section class="lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12 lg:py-16 bg-[#FFFDFB]" data-purpose="login-form-container">
<div class="w-full max-w-md mx-auto">
<!-- Header / Salutation -->
<div class="mb-8 text-center lg:text-left">
<span class="text-xs uppercase tracking-widest font-semibold text-maroon-700 bg-maroon-50 px-3 py-1 rounded-md inline-block mb-3">
            Portal Pelanggan
          </span>
<h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
            Selamat Datang Kembali
          </h2>
<p class="mt-2 text-stone-600 text-sm sm:text-base">
            Silahkan masuk untuk menikmati promo manis dan melanjutkan pesanan Anda.
          </p>
</div>
<!-- Form Elements -->
<form class="space-y-5" data-purpose="credentials-form" method="POST" action="{{ url('/login') }}">
@csrf
<input type="hidden" name="portal" value="pelanggan">
@if ($errors->any())
<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>
@endif
<!-- Email Input Field -->
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="email">
              Email
            </label>
<div class="relative rounded-xl shadow-sm">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<input autocomplete="email" class="block w-full pl-11 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required="" type="email">
</div>
</div>
<!-- Password Input Field -->
<div class="space-y-1.5">
<div class="flex items-center justify-between">
<label class="block text-sm font-semibold text-stone-700" for="password">
                Password
              </label>
<a class="text-xs font-semibold text-maroon hover:text-maroon-800 transition-colors" href="#">
                Lupa Password?
              </a>
</div>
<div class="relative rounded-xl shadow-sm">
<div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
</svg>
</div>
<input autocomplete="current-password" class="block w-full pl-11 pr-11 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="password" name="password" placeholder="Masukkan kata sandi Anda" required="" type="password">
<!-- Toggle Visibility Button -->
<button aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-stone-400 hover:text-stone-600 focus:outline-none" id="togglePassword" type="button">
<svg class="h-5 w-5" fill="none" id="eyeIcon" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
</button>
</div>
</div>
<!-- Remember Me Checkbox -->

<!-- Primary Submit Button -->
<div class="pt-2">
<button class="w-full flex justify-center items-center py-3.5 px-6 rounded-xl shadow-md text-sm sm:text-base font-semibold text-white bg-maroon hover:bg-maroon-700 active:bg-maroon-800 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-btn-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon" type="submit">
              Masuk
            </button>
</div>
</form>
<!-- Divider with Text -->
<div class="mt-8 relative" data-purpose="divider">
<div class="absolute inset-0 flex items-center">
<div class="w-full border-t border-stone-200"></div>
</div>
<div class="relative flex justify-center text-xs tracking-wider uppercase">
<span class="bg-[#FFFDFB] px-4 text-stone-500 font-medium">atau masuk dengan</span>
</div>
</div>
<!-- Social Login Button: Google -->
<div class="mt-6" data-purpose="social-login">
<a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-stone-200 rounded-xl bg-white text-stone-700 text-sm font-semibold hover:bg-stone-50 hover:border-stone-300 active:bg-stone-100 transition duration-150 shadow-sm">
<!-- Google Official Logo SVG -->
<svg class="w-5 h-5" viewBox="0 0 24 24">
<path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z" fill="#4285F4"></path>
<path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z" fill="#34A853"></path>
<path d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z" fill="#FBBC05"></path>
<path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z" fill="#EA4335"></path>
</svg>
<span class="">Google</span>
</a>
</div>
<!-- Registration Footer Link -->
<p class="mt-8 text-center text-sm text-stone-600">
          Tidak punya Akun?
          <a class="font-bold text-maroon hover:text-maroon-800 transition-colors ml-1 underline-offset-2 hover:underline" href="{{ route('register') }}">
            Daftar Sekarang
          </a>
</p>
</div>
</section>
<!-- END: RightLoginForm -->
</main>
<!-- END: MainContainer -->
<!-- BEGIN: InteractiveScripts -->
<script data-purpose="password-visibility-toggle">
    const toggleButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (toggleButton && passwordInput) {
      toggleButton.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        // Update SVG icon state
        if (isPassword) {
          eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
          `;
        } else {
          eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          `;
        }
      });
    }
  </script>
<!-- END: InteractiveScripts -->


<div id="snapdom-sandbox" data-snapdom-sandbox="true" aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px; width: 0px; height: 0px; overflow: hidden;"></div></body></html>
