<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Daftar - Kueku Marinda | Bakery &amp; Cake Shop</title>
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
<!-- BEGIN: RightRegisterForm -->
<section class="lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12 lg:py-16 bg-[#FFFDFB]" data-purpose="register-form-container">
<div class="w-full max-w-md mx-auto">
<div class="mb-8 text-center lg:text-left">
<span class="text-xs uppercase tracking-widest font-semibold text-maroon-700 bg-maroon-50 px-3 py-1 rounded-md inline-block mb-3">Portal Pelanggan</span>
<h2 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">Buat Akun Baru</h2>
<p class="mt-2 text-stone-600 text-sm sm:text-base">Daftar sekali, lalu pesan kue favorit Anda kapan saja.</p>
</div>
<form class="space-y-5" method="POST" action="{{ url('/register') }}">
@csrf
@if ($errors->any())
<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>
@endif
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="nama">Nama Lengkap</label>
<input autocomplete="name" class="block w-full pl-4 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama Anda" required type="text">
</div>
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="email">Email</label>
<input autocomplete="email" class="block w-full pl-4 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required type="email">
</div>
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="no_telepon">No. Telepon (opsional)</label>
<input autocomplete="tel" class="block w-full pl-4 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="0812-3456-7890"  type="tel">
</div>
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="password">Password</label>
<input autocomplete="new-password" class="block w-full pl-4 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="password" name="password" placeholder="Minimal 6 karakter" required type="password" minlength="6">
</div>
<div class="space-y-1.5">
<label class="block text-sm font-semibold text-stone-700" for="password_confirmation">Konfirmasi Password</label>
<input autocomplete="new-password" class="block w-full pl-4 pr-4 py-3.5 bg-stone-50/80 border border-stone-300 rounded-xl text-stone-900 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon focus:bg-white transition duration-200" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required type="password">
</div>
<div class="pt-2">
<button class="w-full flex justify-center items-center py-3.5 px-6 rounded-xl shadow-md text-sm sm:text-base font-semibold text-white bg-maroon hover:bg-maroon-700 active:bg-maroon-800 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-btn-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon" type="submit">Daftar</button>
</div>
</form>
<p class="mt-8 text-center text-sm text-stone-600">
Sudah punya akun?
<a class="font-bold text-maroon hover:text-maroon-800 transition-colors ml-1 underline-offset-2 hover:underline" href="{{ route('login') }}">Masuk</a>
</p>
</div>
</section>
<!-- END: RightRegisterForm -->
</main>
<!-- END: MainContainer -->
</body></html>
