<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Kueku Marinda - Toko Kue &amp; Roti Artisanal</title>
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
  .product-card { cursor: pointer; }
  .product-card h4 a:hover { color: #993A3C; }
</style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen">

<!-- Header -->
<header class="w-full bg-[#FCF9F4] border-b border-[#993A3C]/15 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
  <a class="flex items-center gap-3 group shrink-0" href="#beranda">
    <img alt="Logo Kueku Marinda" class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-[#993A3C]/20 transition-transform group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDp7AJEQ76C6MOzJmciFpvV6P9aqIdHHv4Vd9QVKOPW98sClvZ0GNR2RU8Mc3Ughath9lIYw-AHOAXYaIq_eKKmsooyGXm2VMbD0JmmXqj-1HISrnb6TkNyAh4F8MSHx-4-JK9KpteTUjfo_toYq9Pr5HPV8vzUYMtgjLs-MgTPRCw2AL_axsEFbWFdHIdXnw4_DVQYUebd14bVErpTKQwR_jMJ1QogQFrk1UA2KjmskKDrDPquRfE2ByUvMCV_pRDaPgM">
    <span class="text-2xl font-bold tracking-tight text-[#993A3C] font-serif">Kueku Marinda</span>
  </a>
  <div class="flex-1 max-w-xs md:max-w-sm lg:max-w-md mx-2">
    <div class="relative">
      <input id="search-input" class="w-full bg-[#F6F3EE] border border-[#993A3C]/20 rounded-full py-2 pl-10 pr-4 text-sm text-[#2D2828] placeholder-[#696464] focus:outline-none focus:ring-2 focus:ring-[#993A3C] focus:border-transparent transition-all" placeholder="Cari..." type="text">
      <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#696464]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </div>
    </div>
  </div>
  <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-sm font-medium">
    <a class="text-[#993A3C] font-semibold border-b-2 border-[#993A3C] pb-0.5 tracking-wide" href="#beranda">Beranda</a>
        <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1 inline-flex items-center gap-1.5" href="{{ url('/keranjang') }}">keranjang
      <span id="cart-badge-count" class="bg-[#993A3C] text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">0</span>
    </a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="#favorite">Favorite</a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="#pesanan">Pesanan</a>
  </nav>
  <div class="flex items-center">
    <a class="inline-flex items-center gap-2 bg-[#F6F3EE] hover:bg-[#EFECE6] border border-[#993A3C]/20 text-[#2D2828] px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all shadow-sm" href="#profil">
      <svg class="w-4 h-4 text-[#993A3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span class="whitespace-nowrap">Profil pelanggan</span>
    </a>
  </div>
</div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12" id="beranda">

<!-- ===================== FAVORIT ===================== -->
<section class="pt-4 pb-12" id="favorite">
  <div id="fav-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"></div>
</section>

<!-- Hero -->
<section class="bg-gradient-to-br from-cream to-white rounded-3xl p-8 sm:p-12 border border-orange-100/60 shadow-sm flex flex-col md:flex-row items-center justify-between gap-8">
  <div class="md:w-1/2 space-y-5">
    <h1 class="font-serif text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">Cita Rasa Manis &amp; Lembut Istimewa</h1>
    <p class="text-gray-600 text-base sm:text-lg leading-relaxed">Nikmati variasi roti lembut, cake ulang tahun elegan, bento cake estetik, dan minuman segar buatan tangan kami dengan bahan premium terbaik setiap hari.</p>
  </div>
  <div class="md:w-1/2 w-full flex justify-center">
    <div class="relative w-full max-w-lg aspect-[4/3] rounded-2xl overflow-hidden shadow-lg border border-white">
      <img alt="Showcase Produk Kueku Marinda" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCsTFZWv5Euy_IFZEFiz77jJcue5p3U_t9d4hS1aJFCsWqRTLrEV-JhR3rBAHFja57sNyBNMK_GiIS4eJZoH0_C3oKksXwcQBhx8wFMx1ksS7K-y3MD3B54RIOzXHHnIJtunFKTGGDj6x-7Rd57g-SY4ik8lzK2bMTpnJiIcTZcr0CDQl260nHz8Tu9Fq6WOQaAFS4fDw4pqBeKqIJAKWipfJrZfkmf1B_YuAH9RR-07WTV88ge4WJYzA">
    </div>
  </div>
</section>

<!-- Info pembayaran -->
<section class="bg-gray-100/90 rounded-2xl p-5 border border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-maroon shadow-xs shrink-0">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </div>
    <div>
      <h2 class="font-semibold text-gray-800 text-sm sm:text-base">Deskripsi mengenai pembayaran</h2>
      <p class="text-xs text-gray-500">Pembayaran dilakukan secara tunai (cash). Untuk pesanan <strong>Ambil di Toko</strong>, pembayaran dilakukan saat pelanggan mengambil pesanan di toko Kueku Marinda, sedangkan untuk pesanan <strong>Diantar</strong>, pembayaran dilakukan secara tunai kepada kurir saat pesanan diterima.</p>
    </div>
  </div>
  <div class="flex items-center gap-2 text-xs font-medium text-maroon bg-white px-3 py-1.5 rounded-lg border border-gray-200 shrink-0">
    <span>Pengiriman Cepat &amp; Higienis</span>
  </div>
</section>

@php
  // Kelompokkan produk dari database per kategori
  $groups = $produk->groupBy(function ($p) {
      return $p->kategori->nama_kategori ?? $p->kategori->nama ?? 'Lainnya';
  });
@endphp

<!-- Tab menu (dibuat dari kategori di database) -->
<section class="space-y-4 pt-2" id="pilihan-menu">
  <h2 class="font-serif text-2xl font-bold text-gray-900">Pilihan menu</h2>
  <div class="flex items-center gap-6 sm:gap-8 border-b border-gray-200 overflow-x-auto pb-3 text-sm font-semibold whitespace-nowrap">
    @foreach ($groups as $namaKategori => $daftar)
      <a class="{{ $loop->first ? 'text-maroon border-b-2 border-maroon pb-3 -mb-3 transition' : 'text-gray-500 hover:text-maroon transition pb-3' }}" href="#kat-{{ \Illuminate\Support\Str::slug($namaKategori) }}">{{ $namaKategori }}</a>
    @endforeach
  </div>
</section>

@forelse ($groups as $namaKategori => $daftar)
<!-- ===================== {{ $namaKategori }} ===================== -->
<section class="space-y-6 pt-4" id="kat-{{ \Illuminate\Support\Str::slug($namaKategori) }}">
  <h3 class="font-serif text-2xl font-bold text-gray-900">{{ $namaKategori }}</h3>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

    @foreach ($daftar as $p)
    @php
      $tersedia = $p->status_produk === 'TERSEDIA' && $p->stok > 0;
      $foto = $p->foto_produk ? asset('images/produk/' . $p->foto_produk) : '';
    @endphp
    <div class="product-card bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition" data-id="{{ $p->id_produk }}">
      <div class="relative w-full aspect-[4/3] bg-gray-100">
        <a href="{{ url('/produk/' . $p->id_produk) }}">@if ($foto)<img alt="{{ $p->nama_produk }}" class="w-full h-full object-cover" src="{{ $foto }}">@endif</a>
        <button aria-label="Favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-gray-600 hover:text-maroon flex items-center justify-center shadow transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
      </div>
      <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
        <div>
          <h4 class="font-semibold text-gray-900 text-base"><a href="{{ url('/produk/' . $p->id_produk) }}">{{ $p->nama_produk }}</a></h4>
          <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit($p->deskripsi, 90) }}</p>
        </div>
        <div class="pt-2 border-t border-gray-100 flex items-center justify-between"><span class="text-xs text-gray-400 font-medium">{{ $namaKategori }}</span><span class="text-sm font-bold text-maroon">Rp {{ number_format($p->harga, 0, ',', '.') }}</span></div>
        @if ($tersedia)
        <button class="btn-add w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-sm"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg><span>tambah ke keranjang</span></button>
        @else
        <button class="w-full py-2 bg-gray-200 text-gray-500 rounded-xl text-xs font-semibold cursor-not-allowed" disabled type="button">Stok habis</button>
        @endif
      </div>
    </div>
    @endforeach

  </div>
</section>
@empty
<p class="text-center text-gray-500 py-16">Belum ada produk.</p>
@endforelse

</main>

<footer class="bg-white border-t border-gray-200 py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
  <div class="flex items-center gap-2">
    <span class="font-serif font-bold text-gray-900 text-sm">Kueku Marinda</span>
    <span>© 2025. Seluruh hak cipta dilindungi.</span>
  </div>
  <div class="flex items-center space-x-6">
    <a class="hover:text-maroon" href="#">Syarat &amp; Ketentuan</a>
    <a class="hover:text-maroon" href="#">Kebijakan Privasi</a>
    <a class="hover:text-maroon" href="#">Bantuan &amp; FAQ</a>
  </div>
</div>
</footer>

<script>
/* ---------- Keranjang ---------- */
const CART_KEY = "kueku_cart";
function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch (e) { return {}; } }
function saveCart(c) { try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {} }
function cartTotal() { return Object.values(getCart()).reduce((a, b) => a + b, 0); }
function refreshBadge() {
  const b = document.getElementById("cart-badge-count");
  if (b) b.textContent = cartTotal();
}

/* ---------- Favorit ---------- */
const FAV_KEY = "kueku_fav";
function getFav() { try { return JSON.parse(localStorage.getItem(FAV_KEY)) || []; } catch (e) { return []; } }
function saveFav(a) { try { localStorage.setItem(FAV_KEY, JSON.stringify(a)); } catch (e) {} }

const HEART_OUTLINE = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>';
const HEART_FILLED  = '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>';

function syncHearts() {
  const favs = getFav();
  document.querySelectorAll(".product-card").forEach(card => {
    const btn = card.querySelector('button[aria-label="Favorit"]');
    if (!btn) return;
    const on = favs.includes(card.dataset.id);
    btn.innerHTML = on ? HEART_FILLED : HEART_OUTLINE;
    btn.classList.toggle("text-[#993A3C]", on);
    btn.classList.toggle("text-gray-600", !on);
  });
}

function renderFav() {
  const grid = document.getElementById("fav-grid");
  if (grid) {
    grid.innerHTML = "";
    getFav().forEach(id => {
      const src = document.querySelector('.product-card[data-id="' + id + '"]');
      if (!src) return;
      const clone = src.cloneNode(true);
      clone.style.display = "";
      grid.appendChild(clone);
    });
  }
  syncHearts();
}

function toggleFav(id) {
  let favs = getFav();
  favs = favs.includes(id) ? favs.filter(x => x !== id) : [...favs, id];
  saveFav(favs);
  renderFav();
}

/* ---------- Satu pendengar klik untuk semua kartu (termasuk salinan di Favorit) ---------- */
document.addEventListener("click", e => {
  const card = e.target.closest(".product-card");
  if (!card) return;
  const id = card.dataset.id;

  // Love
  if (e.target.closest('button[aria-label="Favorit"]')) {
    e.stopPropagation();
    toggleFav(id);
    return;
  }

  // Tambah ke keranjang
  const add = e.target.closest(".btn-add");
  if (add) {
    const cart = getCart();
    cart[id] = (cart[id] || 0) + 1;
    saveCart(cart);
    refreshBadge();
    const label = add.querySelector("span");
    if (label) {
      const old = label.textContent;
      label.textContent = "Ditambahkan ✓";
      setTimeout(() => { label.textContent = old; }, 1200);
    }
    return;
  }

  // Klik kartu / link -> buka detail
  try { localStorage.setItem("kueku_selected", id); } catch (err) {}
  if (e.target.closest("a")) return;
  window.location.href = "{{ url('/produk') }}/" + encodeURIComponent(id);
});

/* Buang isi keranjang lama yang id-nya tidak ada di database */
@php $validIds = $produk->pluck('id_produk')->map(fn ($i) => (string) $i)->values(); @endphp
const VALID_IDS = @json($validIds);
(function () {
  const c = getCart(); let changed = false;
  Object.keys(c).forEach(k => { if (!VALID_IDS.includes(k)) { delete c[k]; changed = true; } });
  if (changed) saveCart(c);
})();

refreshBadge();
renderFav();

/* ---------- Pencarian sederhana ---------- */
const searchInput = document.getElementById("search-input");
if (searchInput) {
  searchInput.addEventListener("input", e => {
    const q = e.target.value.trim().toLowerCase();
    document.querySelectorAll(".product-card").forEach(card => {
      card.style.display = card.textContent.toLowerCase().includes(q) ? "" : "none";
    });
  });
}
</script>
</body>
</html>
