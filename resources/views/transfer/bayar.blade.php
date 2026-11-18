<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Bayar dengan Transfer Bank - Kueku Marinda</title>
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

@include('partials.pelanggan-mobile', ['aktif' => 'pesanan', 'judul' => 'Transfer Bank', 'kembali' => route('pesanan.saya')])

<main class="max-w-xl mx-auto px-4 sm:px-6 py-6 md:py-10 pb-28 md:pb-10 space-y-5">
  <div class="hidden md:block">
    <h1 class="font-serif text-3xl font-bold text-gray-900">Bayar dengan Transfer Bank</h1>
    <p class="text-sm text-gray-500 mt-1">Transfer ke rekening di bawah, lalu unggah bukti transfernya.</p>
  </div>

  @if (session('success'))<div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800">{{ session('success') }}</div>@endif
  @if (session('error'))<div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700">{{ session('error') }}</div>@endif
  @if ($errors->any())
    <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700 space-y-1">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
  @endif

  @php
    $st = $pesanan->statusBayar();
    $terakhir = $riwayat->first();
    $ditolak = $st === 'DITOLAK' ? $riwayat->firstWhere('status', 'DITOLAK') : null;
  @endphp

  <div class="bg-white rounded-2xl border border-gray-200 p-6 product-shadow">
    <div class="text-center">
      <p class="text-xs text-gray-400">Kode pesanan</p>
      <p class="font-mono font-bold text-gray-900">{{ $pesanan->kode_pesanan }}</p>
      <p class="mt-3 text-xs text-gray-400">Total pembayaran</p>
      <p class="text-3xl font-bold text-maroon">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
    </div>

    {{-- Rekening tujuan --}}
    @if ($bank)
      <div class="mt-5 rounded-xl border border-gray-200 bg-gray-50 p-4">
        <p class="text-xs text-gray-400">Bank tujuan</p>
        <p class="font-bold text-gray-900">{{ $bank['nama'] }}</p>
        <p class="mt-3 text-xs text-gray-400">Nomor rekening</p>
        <div class="flex items-center justify-between gap-3">
          <p id="no-rek" class="font-mono text-xl font-bold tracking-wider text-gray-900">{{ $bank['rekening'] }}</p>
          <button type="button" id="btn-salin" class="shrink-0 px-4 py-2 rounded-lg border border-maroon/40 text-maroon text-xs font-semibold hover:bg-maroon-50 transition">Salin</button>
        </div>
        <p class="mt-3 text-xs text-gray-400">Atas nama</p>
        <p class="font-semibold text-gray-900">{{ $bank['atas_nama'] }}</p>
      </div>
    @else
      <p class="mt-5 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">Bank tujuan belum tercatat pada pesanan ini. Hubungi admin.</p>
    @endif

    {{-- Status pembayaran --}}
    <div class="mt-5">
      @if ($batal)
        <div class="rounded-xl bg-gray-100 border border-gray-200 px-4 py-3 text-sm text-gray-700">Pesanan ini sudah dibatalkan, pembayaran tidak dapat diproses.</div>

      @elseif ($st === 'LUNAS')
        <div class="text-center">
          <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg>
          </div>
          <p class="mt-3 text-xl font-bold text-emerald-700">Pembayaran LUNAS</p>
          <p class="text-sm text-gray-500 mt-1">Bukti transfer sudah diverifikasi admin{{ $pesanan->waktu_bayar ? ' pada ' . $pesanan->waktu_bayar->format('d M Y, H:i') : '' }}.</p>
          <div class="mt-4 flex flex-wrap justify-center gap-2">
            <a href="{{ route('pesanan.saya') }}" class="px-5 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition">Lihat Pesanan Saya</a>
            <a href="{{ route('pesanan.struk', $pesanan->id_pesanan) }}" target="_blank" class="px-5 py-2.5 bg-white hover:bg-maroon-50 text-maroon border border-maroon/40 rounded-xl text-sm font-semibold transition">Lihat Struk</a>
          </div>
        </div>

      @elseif ($st === 'MENUNGGU_VERIFIKASI')
        <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3">
          <p class="inline-flex items-center gap-2 text-sm font-bold text-amber-800"><span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>MENUNGGU VERIFIKASI</p>
          <p class="text-sm text-amber-900/80 mt-1">Bukti transfer Anda sudah diterima dan sedang diperiksa admin. Halaman ini diperbarui otomatis.</p>
        </div>
        @if ($terakhir)
          <a href="{{ route('transfer.bukti', $terakhir->id_bukti) }}" target="_blank" rel="noopener" class="mt-3 block">
            <img src="{{ route('transfer.bukti', $terakhir->id_bukti) }}" alt="Bukti transfer yang diunggah" class="mx-auto max-h-64 rounded-xl border border-gray-200 object-contain bg-gray-50">
          </a>
          <p class="text-center text-[11px] text-gray-400 mt-1">Diunggah {{ $terakhir->diunggah_pada->format('d M Y, H:i') }}</p>
        @endif

      @else
        {{-- BELUM_BAYAR atau DITOLAK: boleh unggah --}}
        @if ($ditolak)
          <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 mb-4">
            <p class="text-sm font-bold text-red-700">Bukti transfer DITOLAK</p>
            <p class="text-sm text-red-800 mt-1">Alasan admin: {{ $ditolak->catatan_admin }}</p>
            <p class="text-xs text-red-700/80 mt-1">Silakan periksa lalu unggah ulang bukti transfer yang benar.</p>
          </div>
        @else
          <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-2.5 mb-4 text-sm font-semibold text-amber-800">Status: BELUM BAYAR</div>
          <ol class="mb-4 text-sm text-gray-600 space-y-1.5 list-decimal list-inside">
            <li>Salin nomor rekening di atas.</li>
            <li>Lakukan transfer lewat mobile banking atau ATM sebesar <b>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</b>.</li>
            <li>Foto atau screenshot bukti transfer, lalu unggah di bawah ini.</li>
          </ol>
        @endif

        <form method="POST" action="{{ route('transfer.unggah', $pesanan->id_pesanan) }}" enctype="multipart/form-data" id="form-bukti" class="space-y-3">
          @csrf
          <label for="bukti" class="block text-sm font-semibold text-gray-800">Bukti transfer <span class="text-xs font-normal text-gray-400">(JPG/PNG, maks. 2 MB)</span></label>
          <input type="file" name="bukti" id="bukti" accept="image/jpeg,image/png" required
                 class="block w-full text-sm text-gray-600 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-maroon file:text-white file:font-semibold hover:file:bg-maroon-600">
          <p id="err-file" class="hidden text-xs text-red-600"></p>
          <img id="preview" alt="Pratinjau bukti" class="hidden mx-auto max-h-56 rounded-xl border border-gray-200 object-contain bg-gray-50">
          <button type="submit" id="btn-kirim" class="w-full px-5 py-3 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition">
            {{ $ditolak ? 'Unggah Ulang Bukti Transfer' : 'Kirim Bukti Transfer' }}
          </button>
        </form>
      @endif
    </div>
  </div>

  {{-- Riwayat verifikasi --}}
  @if ($riwayat->count() > 1 || ($riwayat->count() === 1 && $st !== 'MENUNGGU_VERIFIKASI'))
    <div class="bg-white rounded-2xl border border-gray-200 p-5 text-sm">
      <h2 class="font-semibold text-gray-800 mb-2">Riwayat bukti transfer</h2>
      <ul class="space-y-2">
        @foreach ($riwayat as $r)
          <li class="flex items-start justify-between gap-3">
            <div>
              <p class="text-gray-700">{{ $r->diunggah_pada->format('d M Y, H:i') }}</p>
              @if ($r->status === 'DITOLAK' && $r->catatan_admin)<p class="text-xs text-red-600">Alasan: {{ $r->catatan_admin }}</p>@endif
            </div>
            <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $r->status === 'DITERIMA' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : ($r->status === 'DITOLAK' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-amber-100 text-amber-800 border-amber-200') }}">{{ $r->status === 'MENUNGGU' ? 'MENUNGGU' : $r->status }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  @endif

  <a href="{{ route('pesanan.saya') }}" class="block text-center text-sm text-gray-500 hover:text-maroon">Kembali ke Pesanan Saya</a>
</main>

<script>
(function () {
  // Tombol Salin: clipboard API butuh HTTPS, jadi ada cadangan untuk http://IP-laptop
  const btn = document.getElementById("btn-salin");
  if (btn) {
    btn.addEventListener("click", function () {
      const angka = document.getElementById("no-rek").textContent.replace(/\s+/g, "");
      const selesai = function () { btn.textContent = "Tersalin"; setTimeout(function () { btn.textContent = "Salin"; }, 2000); };
      const cadangan = function () {
        const t = document.createElement("textarea");
        t.value = angka; t.setAttribute("readonly", ""); t.style.position = "fixed"; t.style.opacity = "0";
        document.body.appendChild(t); t.select();
        try { document.execCommand("copy"); selesai(); } catch (e) { btn.textContent = "Salin manual"; }
        document.body.removeChild(t);
      };
      if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(angka).then(selesai, cadangan); } else { cadangan(); }
    });
  }

  // Pemeriksaan file di browser (server tetap memeriksa ulang)
  const input = document.getElementById("bukti");
  if (input) {
    const err = document.getElementById("err-file"), prev = document.getElementById("preview"), kirim = document.getElementById("btn-kirim");
    input.addEventListener("change", function () {
      err.classList.add("hidden"); prev.classList.add("hidden"); kirim.disabled = false;
      const f = input.files[0];
      if (!f) return;
      let pesan = "";
      if (["image/jpeg", "image/png"].indexOf(f.type) === -1) pesan = "File harus berupa gambar JPG atau PNG.";
      else if (f.size > 2 * 1024 * 1024) pesan = "Ukuran file maksimal 2 MB.";
      if (pesan) { err.textContent = pesan; err.classList.remove("hidden"); kirim.disabled = true; input.value = ""; return; }
      prev.src = URL.createObjectURL(f); prev.classList.remove("hidden");
    });
    document.getElementById("form-bukti").addEventListener("submit", function () { kirim.disabled = true; kirim.textContent = "Mengunggah..."; });
  }

  // Menunggu verifikasi: muat ulang halaman tiap 15 detik agar hasil verifikasi langsung terlihat
  @if ($st === 'MENUNGGU_VERIFIKASI' && ! $batal)
  setTimeout(function () { window.location.reload(); }, 15000);
  @endif

  const b = document.getElementById("cart-badge-count");
  if (b) { try { b.textContent = Object.values(JSON.parse(localStorage.getItem("kueku_cart") || "{}")).reduce((a, x) => a + x, 0); } catch (e) {} }
})();
</script>

</body>
</html>
