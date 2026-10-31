<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Akun Saya - Kueku Marinda</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = { theme: { extend: {
    colors: { maroon: { DEFAULT:'#993A3C', dark:'#7e2e30', light:'#FBF2F2' }, warm:'#EAE5DC', canvas:'#FCF9F4' },
    fontFamily: { serif: ['"Playfair Display"','Georgia','serif'], sans: ['"Plus Jakarta Sans"','system-ui','sans-serif'] }
  } } }
</script>
<style>body { background-color:#FCF9F4; font-family:'Plus Jakarta Sans',sans-serif; }</style>
</head>
<body class="min-h-screen text-stone-800 antialiased">
<main class="max-w-2xl mx-auto px-4 sm:px-6 py-10 space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ $kembali }}" class="w-10 h-10 rounded-full bg-white border border-warm flex items-center justify-center text-maroon hover:bg-maroon hover:text-white transition shadow-sm" aria-label="Kembali">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </a>
    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 flex-1 text-center pr-10">Akun Saya</h1>
  </div>

  @if (session('success'))<div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">{{ session('success') }}</div>@endif
  @if ($errors->any())<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm font-medium text-red-700">{{ $errors->first() }}</div>@endif

  <section class="bg-white rounded-2xl border border-warm p-6 flex items-center gap-5 shadow-sm">
    <div class="w-16 h-16 rounded-full bg-maroon text-white flex items-center justify-center text-2xl font-bold font-serif shrink-0">{{ strtoupper(mb_substr($user->nama, 0, 1)) }}</div>
    <div class="min-w-0">
      <h2 class="font-serif text-xl font-bold text-stone-900 truncate">{{ $user->nama }}</h2>
      <span class="inline-block text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-maroon-light text-maroon-dark mt-1">{{ $peran }}</span>
      <p class="text-sm text-stone-500 mt-1 truncate">{{ $user->email }}</p>
    </div>
  </section>

  <section class="bg-white rounded-2xl border border-warm p-6 shadow-sm">
    <h3 class="font-serif text-lg font-bold text-stone-900 mb-4">Informasi Pribadi</h3>
    <form method="POST" action="{{ route('akun.saya.update') }}" class="space-y-4">
      @csrf @method('PUT')
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="nama">Nama Lengkap</label>
        <input id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="no_telepon">No. Hp</label>
        <input id="no_telepon" name="no_telepon" type="tel" value="{{ old('no_telepon', $user->no_telepon) }}" class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div class="text-center pt-1"><button type="submit" class="px-10 py-2.5 bg-maroon hover:bg-maroon-dark text-white rounded-xl text-sm font-semibold transition shadow-sm">Simpan</button></div>
    </form>
  </section>

  <section class="bg-white rounded-2xl border border-warm p-6 shadow-sm">
    <h3 class="font-serif text-lg font-bold text-stone-900 mb-4">Ganti Kata Sandi</h3>
    <form method="POST" action="{{ route('akun.saya.password') }}" class="space-y-4">
      @csrf @method('PUT')
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="password_lama">Kata sandi lama</label>
        <input id="password_lama" name="password_lama" type="password" required class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="password_baru">Kata sandi baru (min. 6 karakter)</label>
        <input id="password_baru" name="password_baru" type="password" required minlength="6" class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div><label class="block text-sm font-semibold text-stone-700 mb-1.5" for="password_baru_confirmation">Ulangi kata sandi baru</label>
        <input id="password_baru_confirmation" name="password_baru_confirmation" type="password" required class="block w-full py-3 px-4 bg-white border border-stone-300 rounded-xl text-stone-900 text-sm focus:outline-none focus:ring-2 focus:ring-maroon focus:border-maroon transition"></div>
      <div class="text-center pt-1"><button type="submit" class="px-8 py-2.5 border border-maroon text-maroon hover:bg-maroon-light rounded-xl text-sm font-semibold transition">Ganti Kata Sandi</button></div>
    </form>
  </section>

  <form method="POST" action="{{ route('logout') }}" class="pt-2 text-center">
    @csrf
    <button type="submit" class="px-10 py-3 bg-stone-900 hover:bg-black text-white rounded-xl text-sm font-semibold transition">Logout</button>
  </form>
</main>
</body>
</html>
