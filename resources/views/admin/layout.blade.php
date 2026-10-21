<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin') - Kueku Marinda</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          maroon: { DEFAULT: '#993A3C', dark: '#7e2e30', light: '#FBF2F2' },
          warm: '#EAE5DC',
          canvas: '#FCF9F4'
        },
        fontFamily: {
          serif: ['"Playfair Display"', 'Georgia', 'serif'],
          sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif']
        }
      }
    }
  }
</script>
<style>
  body { background-color: #FCF9F4; font-family: 'Plus Jakarta Sans', sans-serif; }
  .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
  .custom-scrollbar::-webkit-scrollbar-thumb { background: #EAE5DC; border-radius: 9999px; }
</style>
@stack('head')
</head>
<body class="min-h-screen text-stone-800 antialiased flex flex-col md:flex-row">

@include('admin._sidebar', ['aktif' => $aktif ?? ''])

<main class="flex-1 min-w-0 flex flex-col min-h-screen">
  <header class="bg-white/80 backdrop-blur-md sticky top-0 z-20 border-b border-warm px-6 lg:px-10 py-5 flex flex-wrap items-center justify-between gap-4">
    <div>
      <div class="flex flex-wrap items-center gap-3">
        <h2 class="text-2xl lg:text-3xl font-bold font-serif text-stone-900">@yield('judul')</h2>
        <span class="hidden sm:inline-flex items-center text-xs font-medium text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200/60">
          <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>Toko Buka • Siap Pesan Antar
        </span>
      </div>
      <p class="text-xs text-stone-500 mt-1">@yield('subjudul')</p>
    </div>
    <div class="flex items-center gap-3">
      <div class="hidden md:flex items-center gap-2 text-xs font-medium text-stone-700 bg-[#F6F3EE] px-3.5 py-2.5 rounded-xl border border-warm">
        <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        <span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
      </div>
      @yield('aksi')
    </div>
  </header>

  <div class="p-6 lg:p-10 space-y-8 flex-1">
    @if (session('success'))
      <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error') || $errors->any())
      <div class="px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-sm font-medium text-rose-800 space-y-1">
        @if (session('error')) <div>{{ session('error') }}</div> @endif
        @foreach ($errors->all() as $pesan) <div>{{ $pesan }}</div> @endforeach
      </div>
    @endif

    @yield('isi')
  </div>

  <footer class="px-6 lg:px-10 py-4 border-t border-warm flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-2 bg-canvas">
    <span><strong class="font-semibold text-maroon">Kueku Marinda</strong> • Artisanal Bakery Management Portal</span>
    <span>© 2026 Kueku Marinda. Seluruh hak cipta dilindungi.</span>
  </footer>
</main>

@stack('scripts')
</body>
</html>
