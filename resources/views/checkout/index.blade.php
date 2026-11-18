<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Checkout - Kueku Marinda</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          serif: ['"Playfair Display"', 'serif'],
          sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        }
      }
    }
  }
</script>
<style>
  body { background-color: #fcf9f4; font-family: 'Plus Jakarta Sans', sans-serif; color: #2e2624; }
  .font-playfair { font-family: 'Playfair Display', serif; }
  input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; }
  input[type="date"]::-webkit-calendar-picker-indicator:hover { opacity: 1; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
  #peta { isolation: isolate; }
  #peta img { max-width: none !important; max-height: none !important; }
</style>
</head>
<body class="min-h-screen bg-[#fcf9f4] text-[#2c2221] antialiased flex flex-col justify-between">

<!-- Header -->
<header class="hidden md:block w-full bg-[#fcf9f4]/95 border-b border-[#993a3c]/15 sticky top-0 z-50 backdrop-blur-sm">
<div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between gap-6">
  <a class="flex items-center gap-3.5 group shrink-0" href="{{ url('/produk') }}">
    <img alt="Kueku Marinda Logo" class="w-11 h-11 rounded-full object-cover shadow-sm transition-transform group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG6eeiazgmo8qlAFR5LJRfMyYs7TKVW47eqGAnlvX26K1ONy1WhGCpn49V8hZFTP16aexeibqyh7VtsUaWQt6YNqQmsHdonYa6x8c4h5YbqT9MBzyjEaNabvQyXkuaDhj630BDM0d3FVu5hK1qzzEncv2CWLEBAhjY0mfVoTNZeUYeRDi6VUd6jy0PmH7Uh5zi8CpyPYHkOv57GtvYldxkZmJO08ackkYAVy6dJf58BFpDLYkFMZgiPr_ycxmGdEbT3cY">
    <span class="font-playfair text-2xl font-bold text-[#993a3c] tracking-tight">Kueku Marinda</span>
  </a>
  <div class="flex-1 max-w-md hidden md:block">
    <div class="relative">
      <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </span>
      <input class="w-full pl-11 pr-4 py-2 text-sm bg-transparent border border-stone-300 rounded-full focus:outline-none focus:border-[#993a3c] focus:ring-1 focus:ring-[#993a3c] transition-all placeholder:text-stone-400" placeholder="Cari kue, bolu, pastry..." type="search">
    </div>
  </div>
  <nav class="flex items-center gap-8 text-sm font-medium">
    <div class="hidden lg:flex items-center gap-7">
      <a class="text-stone-600 hover:text-[#993a3c] transition-colors" href="{{ url('/produk') }}">Beranda</a>
      <a class="text-[#993a3c] font-semibold border-b-2 border-[#993a3c] pb-0.5" href="{{ url('/keranjang') }}">Keranjang</a>
      <a class="text-stone-600 hover:text-[#993a3c] transition-colors" href="{{ route('favorit') }}">Favorite</a>
      <a class="text-stone-600 hover:text-[#993a3c] transition-colors" href="{{ route('pesanan.saya') }}">Pesanan</a>
    </div>
    <a href="{{ route('profil') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-stone-300 rounded-full text-stone-700 hover:border-[#993a3c] hover:text-[#993a3c] transition-all bg-white/60">
      <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span>Profil pelanggan</span>
    </a>
  </nav>
</div>
</header>

@include('partials.pelanggan-mobile', ['aktif' => 'keranjang', 'judul' => 'Checkout', 'kembali' => url('/keranjang')])

<!-- Konten -->
<main class="max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-8 pb-28 md:pb-8 flex-1 w-full">
  <div class="hidden md:block mb-8">
    <a class="inline-flex items-center gap-3 text-stone-800 hover:text-[#993a3c] transition-colors group" href="{{ url('/keranjang') }}">
      <svg class="w-6 h-6 stroke-[2.2] transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      <h1 class="font-playfair text-3xl md:text-4xl font-semibold tracking-tight">Checkout</h1>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- KIRI -->
    <section class="lg:col-span-7 flex flex-col gap-6">

      <!-- Tab metode -->
      <div class="p-1.5 bg-[#f3efe9] rounded-2xl flex items-center gap-1.5 border border-[#e5decb]">
        <button id="tab-kurir" data-mode="kurir" type="button" class="tab-btn flex-1 py-3 px-4 rounded-xl font-medium text-sm flex items-center justify-center gap-2 transition-all cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 17l4 4 4-4m-4-5v9m-7-5a4 4 0 01-1-3V7a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-1 3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          <span>Diantar Kurir</span>
        </button>
        <button id="tab-toko" data-mode="toko" type="button" class="tab-btn flex-1 py-3 px-4 rounded-xl font-medium text-sm flex items-center justify-center gap-2 transition-all cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          <span>Ambil di toko</span>
        </button>
      </div>

      <div class="bg-[#f6f3ee] border border-[#e8dfcf] rounded-2xl p-6 md:p-8 shadow-sm space-y-6">

        <!-- ====== PANEL KURIR ====== -->
        <div id="panel-kurir" class="space-y-6">
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-base font-semibold text-stone-800" for="address">Alamat Pengiriman</label>
              <span class="text-xs font-medium text-stone-500">Wajib diisi</span>
            </div>
            <textarea class="w-full bg-[#fcf9f4] border border-[#d8cdb8] rounded-xl p-3.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#993a3c]/30 focus:border-[#993a3c] transition-all resize-none shadow-inner" id="address" placeholder="Tuliskan nama jalan, no. rumah, RT/RW, kelurahan/kecamatan, dan patokan..." rows="3"></textarea>
            <div class="mt-3 flex flex-wrap gap-2">
              <button id="btn-lokasi" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#993a3c] hover:bg-[#822f31] text-white text-xs sm:text-sm font-semibold shadow-sm transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                Gunakan lokasi saya saat ini
              </button>
              <button id="btn-cari" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#993a3c] text-[#993a3c] hover:bg-[#fbf2f2] text-xs sm:text-sm font-semibold transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                Cari alamat di peta
              </button>
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="block text-base font-semibold text-stone-800">Lokasi Pengantaran</span>
              <span class="text-xs font-medium text-stone-500">Klik peta atau geser pin ke lokasimu</span>
            </div>
            <div id="peta" class="w-full h-72 rounded-xl border border-[#d8cdb8] overflow-hidden relative z-0"></div>
            <div id="ongkir-info" class="mt-3 text-sm rounded-xl px-4 py-3 border bg-[#fcf9f4] border-[#e8dfcf] text-stone-600">
              Cara termudah: klik peta tepat di rumahmu (atau geser pin). Tombol "Cari alamat di peta" memindahkan pin sesuai alamat yang kamu ketik. Ongkir dihitung otomatis dari jarak ke toko (Rp{{ number_format(\App\Support\Ongkir::TARIF[0][1], 0, ',', '.') }} - Rp{{ number_format(\App\Support\Ongkir::TARIF[array_key_last(\App\Support\Ongkir::TARIF)][1], 0, ',', '.') }}, maksimal {{ \App\Support\Ongkir::MAKS_KM }} km).
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block text-base font-semibold text-stone-800" for="phone">No. Handphone / WhatsApp</label>
              <span class="text-xs font-medium text-stone-500">Untuk konfirmasi kurir</span>
            </div>
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-500 font-medium text-sm">+62</span>
              <input class="w-full pl-14 pr-4 py-3 bg-[#fcf9f4] border border-[#d8cdb8] rounded-xl text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#993a3c]/30 focus:border-[#993a3c] transition-all shadow-inner" id="phone" placeholder="812-3456-7890" type="tel" value="{{ ltrim(preg_replace('/\D/', '', auth()->user()->no_telepon ?? ''), '0') }}">
            </div>
          </div>

          <div class="bg-[#fef7ee] border border-[#f5dfb8] rounded-xl p-4 flex items-start gap-3 text-amber-900" role="alert">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            <p class="text-xs sm:text-sm font-medium leading-relaxed">Mohon isi data di atas dengan lengkap dan sesuai agar kurir kami dapat mengantarkan kue tepat waktu dalam kondisi sempurna.</p>
          </div>
        </div>

        <!-- ====== PANEL TOKO ====== -->
        <div id="panel-toko" class="space-y-6 hidden">
          <div>
            <div class="flex items-center justify-between pb-3 border-b border-[#e8dfcf]">
              <h2 class="text-base font-bold text-stone-900 tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#993a3c] inline-block"></span>
                Lokasi Pengambilan
              </h2>
              <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Outlet Utama</span>
            </div>
            <div class="mt-4 p-4 rounded-xl bg-[#fcf9f4] border border-[#e8dfcf] flex items-start gap-3.5">
              <div class="w-10 h-10 rounded-lg bg-[#993a3c]/10 text-[#993a3c] flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
              </div>
              <div class="space-y-1">
                <h3 class="text-base font-bold text-stone-900 leading-snug">Toko Kueku Marinda</h3>
                <p class="text-sm text-stone-700 leading-relaxed">Jl. Terusan Ps. Cibeureum No.13, Ciwidey, Kec. Ciwidey, Kabupaten Bandung, Jawa Barat 40973</p>
                <div class="pt-1 text-xs text-[#993a3c] font-medium">
                  <a class="underline hover:text-[#822f31] inline-flex items-center gap-1" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=-7.0979546,107.4676555">
                    Buka Petunjuk Arah Google Maps
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="rounded-xl border border-amber-200/80 bg-amber-50/60 p-4 space-y-2">
            <div class="flex items-center gap-2 text-amber-900 font-bold text-sm">
              <svg class="w-4 h-4 text-amber-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
              <span>Jam operasional toko: <span class="text-[#993a3c] font-bold">08.00 - 15.00 WIB</span></span>
            </div>
            <p class="text-xs text-stone-700 leading-relaxed pl-6">Pesanan siap diambil sesuai jadwal yang telah ditentukan. Silakan tunjukkan <strong>Bukti Pesanan</strong> atau <strong>Nomor WhatsApp</strong> yang terdaftar saat pengambilan kepada staf kasir di toko.</p>
          </div>
        </div>

        <!-- ====== JADWAL (dipakai kedua mode) ====== -->
        <div class="pt-2 border-t border-[#e8dfcf]">
          <h2 id="jadwal-title" class="text-base font-semibold text-stone-800 mb-4">Tentukan jadwal pengantaran pesanan</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium text-stone-600 mb-1.5" for="date">Pilih Tanggal</label>
              <input class="w-full bg-[#fcf9f4] border border-[#d8cdb8] rounded-xl px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#993a3c]/30 focus:border-[#993a3c] transition-all cursor-pointer" id="date" type="date">
              <p id="date-hint" class="mt-1.5 text-xs text-stone-500">Tersedia H+0 sampai H+7</p>
            </div>
            <div>
              <label id="time-label" class="block text-xs font-medium text-stone-600 mb-1.5" for="time">Pilih Jam Antar</label>
              <select class="w-full bg-[#fcf9f4] border border-[#d8cdb8] rounded-xl px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-[#993a3c]/30 focus:border-[#993a3c] transition-all cursor-pointer" id="time"></select>
              <p id="time-hint" class="mt-1.5 text-xs text-stone-500"></p>
            </div>
          </div>
          <p class="mt-3 text-xs font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">Pesanan diproses minimal {{ \App\Http\Controllers\PesananController::MIN_JAM_PROSES }} jam sebelum diambil/dikirim.</p>
        </div>
      </div>

      {{-- Metode pembayaran (wajib dipilih) --}}
      <div class="bg-white rounded-2xl border border-[#e6dccb] shadow-sm p-6" id="metode-bayar">
        <h2 class="text-base font-semibold text-stone-800 mb-1">Pilih metode pembayaran</h2>
        <p class="text-xs text-stone-500 mb-4">Wajib dipilih sebelum pesanan dibuat.</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" role="radiogroup" aria-label="Metode pembayaran">
          @foreach (\App\Models\Pesanan::METODE_PEMBAYARAN as $kodeMetode => $labelMetode)
            <label class="relative cursor-pointer">
              <input type="radio" name="metode_pembayaran" value="{{ $kodeMetode }}" class="peer sr-only">
              <div class="rounded-xl border border-[#d8cdb8] bg-[#fcf9f4] px-4 py-3.5 text-center transition-all peer-checked:border-[#993a3c] peer-checked:bg-[#993a3c]/5 peer-checked:ring-2 peer-checked:ring-[#993a3c]/30 peer-focus-visible:ring-2 peer-focus-visible:ring-[#993a3c]/40 hover:border-[#993a3c]/60">
                <span class="block text-sm font-semibold text-stone-800">{{ $labelMetode }}</span>
                <span class="block text-[11px] text-stone-500 mt-0.5">
                  @if ($kodeMetode === 'TUNAI') Bayar langsung saat pesanan diterima/diambil
                  @elseif ($kodeMetode === 'QRIS') Scan QR Code lewat HP
                  @else Transfer ke rekening toko, unggah bukti, diverifikasi Admin
                  @endif
                </span>
              </div>
            </label>
          @endforeach
        </div>

        {{-- Bank tujuan: hanya muncul bila memilih Transfer Bank --}}
        <div id="blok-bank" class="hidden mt-4 pt-4 border-t border-[#e6dccb]">
          <h3 class="text-sm font-semibold text-stone-800 mb-1">Pilih bank tujuan</h3>
          <p class="text-xs text-stone-500 mb-3">Nomor rekening ditampilkan setelah pesanan dibuat.</p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" role="radiogroup" aria-label="Bank tujuan transfer">
            @foreach (\App\Models\Pesanan::BANK_TRANSFER as $kodeBank => $infoBank)
              <label class="relative cursor-pointer">
                <input type="radio" name="bank_tujuan" value="{{ $kodeBank }}" class="peer sr-only">
                <div class="rounded-xl border border-[#d8cdb8] bg-[#fcf9f4] px-3 py-3 text-center transition-all peer-checked:border-[#993a3c] peer-checked:bg-[#993a3c]/5 peer-checked:ring-2 peer-checked:ring-[#993a3c]/30 peer-focus-visible:ring-2 peer-focus-visible:ring-[#993a3c]/40 hover:border-[#993a3c]/60">
                  <span class="block text-sm font-bold text-stone-800">{{ $kodeBank }}</span>
                  <span class="block text-[11px] text-stone-500">{{ $infoBank['nama'] }}</span>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <p class="mt-3 text-xs text-stone-500">QRIS: QR Code ditampilkan setelah pesanan dibuat. Transfer Bank: rekening tujuan dan kolom unggah bukti ditampilkan setelah pesanan dibuat.</p>
      </div>
    </section>

    <!-- KANAN -->
    <aside class="lg:col-span-5">
      <div class="bg-[#f6f3ee] border border-[#e8dfcf] rounded-2xl p-6 md:p-8 shadow-sm sticky top-28">
        <div class="flex items-center justify-between mb-6">
          <h2 class="font-playfair text-2xl font-bold text-stone-800">Ringkasan pesanan</h2>
          <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-white text-stone-600 border border-[#e8dfcf]"><span id="sum-badge">0</span> Item</span>
        </div>

        <div class="space-y-4 mb-6" id="sum-list"></div>

        <div class="border-t border-[#e2d6c3] pt-4 space-y-2.5 text-sm">
          <div class="flex justify-between items-center text-stone-600">
            <span>Subtotal (<span id="sum-count">0</span> item)</span>
            <span class="font-medium text-stone-800" id="sum-sub">Rp 0</span>
          </div>
          <div class="flex justify-between items-center text-stone-600">
            <span>Ongkos kirim</span>
            <span id="sum-ship"></span>
          </div>
          <div class="border-t border-[#ded1be] pt-4 mt-3 flex justify-between items-baseline">
            <span class="text-xl font-bold text-stone-900">Total</span>
            <div class="text-right">
              <span class="text-2xl font-bold text-[#993a3c] tracking-tight" id="sum-total">Rp 0</span>
              <p class="text-[11px] text-stone-500 mt-0.5">Sudah termasuk pajak &amp; kemasan aman</p>
            </div>
          </div>
        </div>

        <div class="mt-8 space-y-3">
          <p id="form-error" class="hidden text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2"></p>
          <button id="btn-next" class="w-full bg-[#993a3c] hover:bg-[#822f31] text-white font-medium py-3.5 px-6 rounded-full shadow-md transition-all flex items-center justify-center gap-2 group text-base cursor-pointer" type="button">
            <span>Lanjut ke Pembayaran</span>
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          </button>
          <div class="flex items-center justify-center gap-2 text-xs text-stone-500 pt-1">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            <span>Pemesanan higienis &amp; jaminan fresh bake</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</main>

<footer class="mt-16 border-t border-[#eadfce] py-6 text-center text-xs text-stone-500">
  <div class="max-w-7xl mx-auto px-6"><p>© 2026 Kueku Marinda. Semua hak cipta dilindungi undang-undang.</p></div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ---------- Data produk (id sama dengan data-id di beranda) ---------- */
const PRODUCTS = @json($produkJs);

const CART_KEY = "kueku_cart";
const rp = n => "Rp " + n.toLocaleString("id-ID");
function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch (e) { return {}; } }
function saveCart(c) { try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {} }

let mode = "kurir";

/* ---------- Peta & ongkir otomatis (hitungan resmi dilakukan server) ---------- */
const TOKO = { lat: {{ \App\Support\Ongkir::TOKO_LAT }}, lng: {{ \App\Support\Ongkir::TOKO_LNG }} };
let peta = null, pin = null, titik = null;
let ongkir = null, ongkirToken = "", jarakKm = null;
let alamatOtomatis = true;   // true selama kolom alamat belum diketik manual
let seq = 0;

function info(pesan, tipe) {
  const el = document.getElementById("ongkir-info");
  el.textContent = pesan;
  el.className = "mt-3 text-sm rounded-xl px-4 py-3 border " +
    (tipe === "ok" ? "bg-emerald-50 border-emerald-200 text-emerald-800 font-medium"
    : tipe === "err" ? "bg-red-50 border-red-200 text-red-700 font-medium"
    : "bg-[#fcf9f4] border-[#e8dfcf] text-stone-600");
}

function initPeta() {
  if (peta || typeof L === "undefined") return;
  peta = L.map("peta").setView([TOKO.lat, TOKO.lng], 13);
  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", { maxZoom: 19, attribution: "&copy; OpenStreetMap" }).addTo(peta);
  L.circleMarker([TOKO.lat, TOKO.lng], { radius: 9, color: "#7e2e30", weight: 2, fillColor: "#993a3c", fillOpacity: 0.9 })
    .addTo(peta).bindTooltip("Toko Kueku Marinda");
  peta.on("click", e => pasangPin(e.latlng.lat, e.latlng.lng, true));
}

function pasangPin(lat, lng, isiAlamat) {
  if (!peta) return;
  titik = { lat, lng };
  if (!pin) {
    pin = L.marker([lat, lng], { draggable: true }).addTo(peta);
    pin.on("dragend", () => { const p = pin.getLatLng(); pasangPin(p.lat, p.lng, true); });
  } else {
    pin.setLatLng([lat, lng]);
  }
  peta.setView([lat, lng], Math.max(peta.getZoom(), 15));
  hitungOngkir(lat, lng);
  if (isiAlamat) isiAlamatDariTitik(lat, lng);
}

// Alamat otomatis dari titik peta (hanya bila pelanggan belum mengetik alamat sendiri)
async function isiAlamatDariTitik(lat, lng) {
  const box = document.getElementById("address");
  if (box.value.trim() && !alamatOtomatis) return;
  try {
    const res = await fetch("https://nominatim.openstreetmap.org/reverse?format=jsonv2&accept-language=id&zoom=18&lat=" + lat + "&lon=" + lng);
    const d = await res.json();
    if (d && d.display_name && (!box.value.trim() || alamatOtomatis)) {
      box.value = d.display_name;
      alamatOtomatis = true;
    }
  } catch (e) { /* abaikan: pelanggan tetap bisa mengetik alamat */ }
}

async function cariAlamat() {
  const q = document.getElementById("address").value.trim();
  if (q.length < 5) { info("Ketik alamat dulu (minimal 5 huruf), lalu klik Cari alamat di peta.", "err"); return; }
  info("Mencari alamat di peta...");
  const base = "https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=id&accept-language=id&viewbox=107.2,-6.9,107.7,-7.35&q=";
  try {
    let data = await (await fetch(base + encodeURIComponent(q))).json();
    if (!data.length) data = await (await fetch(base + encodeURIComponent(q + ", Ciwidey, Bandung"))).json();
    if (!data.length) { info("Alamat tidak ditemukan di peta. Klik langsung lokasi rumahmu di peta.", "err"); return; }
    pasangPin(parseFloat(data[0].lat), parseFloat(data[0].lon), false);
  } catch (e) {
    info("Pencarian alamat gagal. Klik langsung lokasi rumahmu di peta.", "err");
  }
}

let catatanAkurasi = "", catatanLat = null, catatanLng = null;
function lokasiSaya() {
  if (!navigator.geolocation) { info("Browser tidak mendukung lokasi. Klik lokasi rumahmu di peta.", "err"); return; }
  info("Mengambil lokasimu saat ini...");
  navigator.geolocation.getCurrentPosition(
    pos => {
      const acc = Math.round(pos.coords.accuracy || 0);
      catatanLat = pos.coords.latitude; catatanLng = pos.coords.longitude;
      catatanAkurasi = acc > 300
        ? " Perhatian: akurasi lokasi perangkat hanya sekitar " + acc + " m (laptop tanpa GPS sering meleset). Kalau pin belum pas, geser pin atau klik di peta ke rumahmu."
        : "";
      pasangPin(pos.coords.latitude, pos.coords.longitude, true);
    },
    err => info(err.code === 1
      ? "Izin lokasi ditolak. Izinkan lokasi di browser, atau klik lokasi rumahmu di peta."
      : "Lokasi tidak bisa diambil. Klik lokasi rumahmu di peta.", "err"),
    { enableHighAccuracy: true, timeout: 12000 }
  );
}

async function hitungOngkir(lat, lng) {
  const urut = ++seq;
  ongkir = null; ongkirToken = ""; jarakKm = null;
  info("Menghitung jarak dan ongkir...");
  renderSummary();
  try {
    const res = await fetch("{{ url('/pesanan') }}", {
      method: "POST", credentials: "same-origin",
      headers: { "Content-Type": "application/json", "Accept": "application/json", "X-Requested-With": "XMLHttpRequest", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      body: JSON.stringify({ mode: "hitung_ongkir", lat: lat, lng: lng })
    });
    if (res.status === 401 || res.status === 419) { window.location.href = "{{ route('login') }}"; return; }
    const d = await res.json().catch(() => ({}));
    if (urut !== seq) return;   // ada pilihan lokasi yang lebih baru
    if (!res.ok) {
      info(d.message || (d.errors ? Object.values(d.errors)[0][0] : "Ongkir tidak bisa dihitung."), "err");
      renderSummary();
      return;
    }
    ongkir = d.ongkir; ongkirToken = d.token; jarakKm = d.jarak_km;
    info("Jarak ± " + String(d.jarak_km).replace(".", ",") + " km dari toko • Ongkir " + rp(d.ongkir) +
      (d.metode === "perkiraan" ? " (jarak perkiraan, layanan peta sedang sibuk)" : "") +
      (catatanAkurasi && lat === catatanLat && lng === catatanLng ? catatanAkurasi : ""), "ok");
    renderSummary();
  } catch (e) {
    if (urut === seq) { info("Gagal menghitung ongkir. Periksa koneksi lalu pilih lokasi lagi.", "err"); renderSummary(); }
  }
}

// ---- Aturan waktu: minimal 3 jam, zona waktu WIB (sama dengan server) ----
const MIN_JAM = {{ \App\Http\Controllers\PesananController::MIN_JAM_PROSES }};
const JAM_OP = @json(\App\Http\Controllers\PesananController::JAM_OPERASIONAL);
const SERVER_NOW = {{ now('Asia/Jakarta')->getTimestamp() * 1000 }};
const LOADED_AT = Date.now();
const WIB_MS = 7 * 3600 * 1000;
const pad2 = n => String(n).padStart(2, "0");
const nowMs = () => SERVER_NOW + (Date.now() - LOADED_AT);   // "sekarang" menurut server (WIB)

function hm(str) { const p = str.split(":"); return Number(p[0]) * 60 + Number(p[1]); }
function bukaTutup(m) { const j = JAM_OP[m === "kurir" ? "DELIVERY" : "PICKUP"]; return [hm(j[0]), hm(j[1])]; }
function fmtWib(ms) {
  const d = new Date(ms + WIB_MS);
  return d.getUTCFullYear() + "-" + pad2(d.getUTCMonth() + 1) + "-" + pad2(d.getUTCDate());
}
function wibToMs(dateStr, menit) {
  const p = dateStr.split("-").map(Number);
  return Date.UTC(p[0], p[1] - 1, p[2], Math.floor(menit / 60), menit % 60) - WIB_MS;
}

// Slot 30 menit pada tanggal tertentu yang masih >= sekarang + 3 jam
function slotsFor(dateStr, m) {
  const [buka, tutup] = bukaTutup(m);
  const minimal = nowMs() + MIN_JAM * 3600 * 1000;
  const out = [];
  for (let t = buka; t < tutup; t += 30) {
    if (wibToMs(dateStr, t) >= minimal) out.push(t);
  }
  return out;
}

// Tanggal paling awal yang masih punya slot (hari ini, atau besok kalau hari ini sudah tutup)
function earliestDate(m) {
  for (let i = 0; i <= 7; i++) {
    const d = fmtWib(nowMs() + i * 86400000);
    if (slotsFor(d, m).length) return d;
  }
  return fmtWib(nowMs() + 86400000);
}

const ACTIVE = ["bg-[#993a3c]", "text-white", "shadow-sm"];
const INACTIVE = ["text-stone-600", "hover:text-stone-900", "hover:bg-stone-200/50"];

function renderDate() {
  const el = document.getElementById("date");
  const hariIni = fmtWib(nowMs());
  const awal = earliestDate(mode);
  el.min = awal;
  el.max = fmtWib(nowMs() + 7 * 86400000);
  if (!el.value || el.value < awal) el.value = awal;
  document.getElementById("date-hint").textContent = awal > hariIni
    ? "Pesanan hari ini sudah ditutup (kurang dari " + MIN_JAM + " jam sebelum jam operasional berakhir). Tanggal dimulai besok."
    : "Tersedia H+0 sampai H+7";
  renderSlots();
}

function renderSlots() {
  const sel = document.getElementById("time");
  const slots = slotsFor(document.getElementById("date").value, mode);
  const lama = sel.value;
  sel.innerHTML = slots.map(t => {
    const v = pad2(Math.floor(t / 60)) + ":" + pad2(t % 60);
    return '<option value="' + v + '">' + v + " WIB</option>";
  }).join("");
  if (!slots.length) sel.innerHTML = '<option value="">Tidak ada jam tersedia</option>';
  else if (lama && sel.querySelector('option[value="' + lama + '"]')) sel.value = lama;
}

function setMode(m) {
  mode = m;
  document.querySelectorAll(".tab-btn").forEach(b => {
    const on = b.dataset.mode === m;
    ACTIVE.forEach(c => b.classList.toggle(c, on));
    INACTIVE.forEach(c => b.classList.toggle(c, !on));
  });
  document.getElementById("panel-kurir").classList.toggle("hidden", m !== "kurir");
  document.getElementById("panel-toko").classList.toggle("hidden", m !== "toko");

  document.getElementById("jadwal-title").textContent = m === "kurir" ? "Tentukan jadwal pengantaran pesanan" : "Tentukan waktu pengambilan";
  document.getElementById("time-label").textContent = m === "kurir" ? "Pilih Jam Antar" : "Pilih Jam Ambil";
  document.getElementById("time-hint").textContent = m === "toko" ? "Harap datang tepat waktu sesuai jam yang dipilih" : "Jam antar diperkirakan, kurir akan menghubungi Anda";

  renderDate();

  document.getElementById("form-error").classList.add("hidden");
  renderSummary();
  if (m === "kurir" && peta) setTimeout(() => peta.invalidateSize(), 60);
}

function renderSummary() {
  const cart = getCart();
  let count = 0, sub = 0, html = "";

  Object.keys(cart).forEach(id => {
    const p = PRODUCTS[id], qty = cart[id];
    if (!p || qty < 1) return;
    count += qty;
    sub += p.price * qty;
    html += `
    <div class="flex items-center justify-between gap-4 bg-white/70 p-3 rounded-xl border border-stone-200/80" data-id="${id}">
      <div class="flex items-center gap-3 min-w-0">
        <img src="${p.img}" alt="${p.name}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0 border border-stone-200 shadow-sm">
        <div class="min-w-0">
          <h3 class="text-sm font-semibold text-stone-900 leading-tight">${p.name}</h3>
          <p class="text-xs text-stone-500 mt-0.5">${p.sub}</p>
          <span class="inline-block mt-1 font-medium text-xs text-[#993a3c]">${rp(p.price)}</span>
        </div>
      </div>
      <div class="flex flex-col items-end justify-between self-stretch gap-2">
        <button data-del="1" title="Hapus item" type="button" class="text-stone-400 hover:text-[#993a3c] p-0.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </button>
        <span class="text-xs font-semibold px-2.5 py-1 bg-stone-100 rounded-md text-stone-600 border border-stone-200">× ${qty}</span>
      </div>
    </div>`;
  });

  document.getElementById("sum-list").innerHTML = html || '<p class="text-sm text-stone-500">Keranjang kosong.</p>';
  document.getElementById("sum-badge").textContent = count;
  document.getElementById("sum-count").textContent = count;
  document.getElementById("sum-sub").textContent = rp(sub);

  const shipEl = document.getElementById("sum-ship");
  let ship = 0;
  if (mode === "kurir") {
    if (ongkir === null) {
      shipEl.className = "text-xs font-medium text-stone-500";
      shipEl.textContent = "Dihitung setelah lokasi dipilih";
    } else {
      ship = ongkir;
      shipEl.className = "font-medium text-stone-800";
      shipEl.textContent = rp(ship) + (jarakKm !== null ? " (" + String(jarakKm).replace(".", ",") + " km)" : "");
    }
  } else {
    shipEl.className = "text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800";
    shipEl.textContent = "Gratis (Ambil di Toko)";
  }
  document.getElementById("sum-total").textContent = rp(sub + ship);
  document.getElementById("btn-next").disabled = count === 0;
  document.getElementById("btn-next").classList.toggle("opacity-50", count === 0);
}

// Hapus item dari ringkasan
document.getElementById("sum-list").addEventListener("click", e => {
  const btn = e.target.closest("button[data-del]");
  if (!btn) return;
  const id = btn.closest("[data-id]").dataset.id;
  const cart = getCart();
  delete cart[id];
  saveCart(cart);
  renderSummary();
});

// Tab
document.querySelectorAll(".tab-btn").forEach(b => b.addEventListener("click", () => setMode(b.dataset.mode)));

// Lanjut ke pembayaran
document.getElementById("btn-next").addEventListener("click", () => {
  const err = document.getElementById("form-error");
  const address = document.getElementById("address").value.trim();
  const phone = document.getElementById("phone").value.replace(/\D/g, "");
  const date = document.getElementById("date").value;
  const time = document.getElementById("time").value;

  let msg = "";
  if (mode === "kurir" && address.length < 10) msg = "Alamat pengiriman wajib diisi dengan lengkap.";
  if (!msg && mode === "kurir" && !ongkirToken) msg = "Tandai lokasi pengantaran di peta dulu supaya ongkir bisa dihitung.";
  if (!msg && phone.length < 9) msg = "No. handphone / WhatsApp wajib diisi dengan benar.";
  if (!msg && !date) msg = "Tanggal belum dipilih.";
  if (!msg && !time) msg = "Belum ada jam yang tersedia. Pilih tanggal lain.";
  const metodeEl = document.querySelector('input[name="metode_pembayaran"]:checked');
  const metodeBayar = metodeEl ? metodeEl.value : "";
  if (!msg && !["TUNAI", "QRIS", "TRANSFER"].includes(metodeBayar)) msg = "Pilih metode pembayaran terlebih dahulu (Tunai, QRIS, atau Transfer Bank).";
  const bankEl = document.querySelector('input[name="bank_tujuan"]:checked');
  const bankTujuan = (metodeBayar === "TRANSFER" && bankEl) ? bankEl.value : null;
  if (!msg && metodeBayar === "TRANSFER" && !bankTujuan) msg = "Pilih bank tujuan transfer (BCA, BRI, BNI, atau Mandiri).";
  if (!msg && time) {
    // cek ulang aturan 3 jam (waktu terus berjalan sejak halaman dibuka)
    if (wibToMs(date, hm(time)) < nowMs() + MIN_JAM * 3600 * 1000) {
      msg = "Pesanan diproses minimal " + MIN_JAM + " jam sebelum diambil/dikirim. Silakan pilih jam lain.";
      renderDate();
    }
  }

  if (msg) { err.textContent = msg; err.classList.remove("hidden"); return; }
  err.classList.add("hidden");

  const btn = document.getElementById("btn-next");
  const cartNow = getCart();
  const items = Object.keys(cartNow)
    .filter(id => PRODUCTS[id] && cartNow[id] > 0)
    .map(id => ({ id_produk: Number(id), jumlah: cartNow[id] }));

  if (!items.length) {
    err.textContent = "Keranjang kosong atau produk sudah tidak tersedia.";
    err.classList.remove("hidden");
    return;
  }

  btn.disabled = true;

  // Kirim pesanan ke server -> disimpan ke database (PesananController@store)
  fetch("{{ url('/pesanan') }}", {
    method: "POST",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({
      jenis_pengambilan: mode === "kurir" ? "DELIVERY" : "PICKUP",
      alamat_lengkap: mode === "kurir" ? address : null,
      no_telepon: phone,
      ongkir_token: mode === "kurir" ? ongkirToken : null,
      tanggal_ambil: date,
      jam_ambil: time,
      metode_pembayaran: metodeBayar,
      bank_tujuan: bankTujuan,
      items: items
    })
  }).then(async res => {
    // Belum login -> arahkan ke halaman login (keranjang tetap tersimpan)
    if (res.status === 401 || res.status === 419) { window.location.href = "{{ route('login') }}"; return; }

    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const pesan = data.message || (data.errors ? Object.values(data.errors)[0][0] : "Pesanan gagal dibuat.");
      throw new Error(pesan);
    }

    // Data untuk halaman "Pesanan Terkonfirmasi" dan "Detail Pesanan": nomor pesanan dari database
    const order = {
      no: "#" + data.kode_pesanan,
      id: data.id_pesanan,
      mode: mode,
      address: mode === "kurir" ? address : "",
      phone: phone,
      ongkir: mode === "kurir" ? ongkir : 0,
      date: date,
      time: Number(time.split(":")[0]) < 12 ? "pagi" : (Number(time.split(":")[0]) < 15 ? "siang" : "sore"),
      jam: time,
      cart: cartNow
    };
    try { localStorage.setItem("kueku_last_order", JSON.stringify(order)); localStorage.removeItem(CART_KEY); } catch (e) {}
    // QRIS: halaman QR Code. Transfer Bank: halaman rekening & unggah bukti. Tunai: halaman pesanan terkonfirmasi.
    window.location.href =
      (data.metode_pembayaran === "QRIS" && data.qris_url) ? data.qris_url :
      (data.metode_pembayaran === "TRANSFER" && data.transfer_url) ? data.transfer_url :
      "{{ url('/pesanan/selesai') }}";
  }).catch(e => {
    err.textContent = e.message;
    err.classList.remove("hidden");
    btn.disabled = false;
  });
});

document.getElementById("date").addEventListener("change", renderSlots);

// Peta: tombol & kolom alamat
document.getElementById("btn-lokasi").addEventListener("click", lokasiSaya);
document.getElementById("btn-cari").addEventListener("click", cariAlamat);
document.getElementById("address").addEventListener("input", () => { alamatOtomatis = false; });
// Selesai mengetik alamat dan belum ada pin -> otomatis dicarikan di peta
document.getElementById("address").addEventListener("change", () => {
  if (!titik && document.getElementById("address").value.trim().length >= 10) cariAlamat();
});

initPeta();
if (!peta) info("Peta gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.", "err");
setMode("kurir");

// Pilihan bank tampil hanya saat metode Transfer Bank dipilih
document.querySelectorAll('input[name="metode_pembayaran"]').forEach(function (r) {
  r.addEventListener("change", function () {
    const dipilih = document.querySelector('input[name="metode_pembayaran"]:checked');
    document.getElementById("blok-bank").classList.toggle("hidden", !dipilih || dipilih.value !== "TRANSFER");
  });
});
</script>
</body>
</html>
