<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Detail Produk - Kueku Marinda</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          maroon: { 50:'#fdf3f3',100:'#fae6e7',200:'#f6d1d3',600:'#a94345',700:'#993A3C',800:'#812e30',900:'#6c2426' },
          cream: { 50:'#fdfbf7',100:'#f8f4ec',200:'#f1ebd9',bg:'#fbf9f5' }
        },
        fontFamily: {
          serif: ['"Playfair Display"','Georgia','serif'],
          sans: ['"Plus Jakarta Sans"','sans-serif'],
        }
      }
    }
  }
</script>
<style>
  body { background-color:#fbf9f5; color:#2b2523; font-family:'Plus Jakarta Sans',sans-serif; }
</style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-[#fcf9f4] text-[#1c1c19] selection:bg-maroon-100 selection:text-maroon-900">
@php
  $kat = $produk->kategori->nama_kategori ?? $produk->kategori->nama ?? 'Produk';
  $tersedia = $produk->status_produk === 'TERSEDIA' && $produk->stok > 0;
  $foto = $produk->foto_produk ? asset('images/produk/' . $produk->foto_produk) : '';
  $katLower = strtolower($kat);
  if (str_contains($katLower, 'minuman')) { $room = '30 Menit'; $fridge = '6 Jam'; }
  elseif (str_contains($katLower, 'roti')) { $room = '1 Hari'; $fridge = '3 Hari'; }
  else { $room = '6 Jam'; $fridge = '3 Hari'; }
@endphp

<!-- Header -->
<header class="sticky top-0 z-40 bg-[#fcf9f4]/90 backdrop-blur-md border-b border-[#993A3C]/15 px-4 lg:px-12 py-3.5 transition-all shadow-sm">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-2 sm:px-6">
    <div class="flex items-center space-x-4">
      <a aria-label="Kembali" class="inline-flex items-center justify-center text-[#7a2327] hover:text-[#993A3C] p-1.5 rounded-full hover:bg-[#993A3C]/10 transition" href="{{ url('/produk') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </a>
      <h1 class="text-xl sm:text-2xl font-bold text-[#1c1c19] tracking-tight">Detail Produk</h1>
    </div>
    <div class="flex items-center">
      <a aria-label="Keranjang Belanja" href="{{ url('/keranjang') }}" class="relative p-1.5 text-[#7a2327] hover:text-[#993A3C] transition flex items-center justify-center">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span class="absolute -top-1 -right-1 bg-[#993A3C] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-[#fcf9f4] shadow-sm" id="cart-badge-count">0</span>
      </a>
    </div>
  </div>
</header>

<!-- Main -->
<main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-12 py-8 lg:py-12">
  <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">
    <div class="lg:col-span-6 w-full">
      <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-[#f0ede9] border border-[#993A3C]/15 shadow-md ring-1 ring-[#993A3C]/5">
        @if ($foto)<img alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover object-center" id="main-product-image" src="{{ $foto }}">@endif
      </div>
    </div>

    <div class="lg:col-span-6 flex flex-col justify-center space-y-7 pl-0 lg:pl-6">
      <div class="space-y-2.5">
        <div class="inline-block px-3 py-1 rounded-full bg-[#ffdad8]/70 text-[#7a2327] text-xs font-semibold tracking-wide uppercase mb-1" id="product-badge">{{ $kat }}</div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-['Playfair_Display'] font-bold text-[#1c1c19] tracking-tight" id="product-name">{{ $produk->nama_produk }}</h2>
        <p class="text-[#564241] text-base font-normal pt-1" id="product-sub">Fresh Baked • Stok tersisa {{ $produk->stok }}</p>
      </div>

      <div class="space-y-1.5 pt-1">
        <div class="text-2xl sm:text-3xl font-bold text-[#7a2327] tracking-tight" id="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
      </div>

      <div class="space-y-2 pt-1">
        <p class="text-[#564241] text-sm sm:text-base leading-relaxed max-w-lg pt-1 font-normal" id="product-desc">{{ $produk->deskripsi }}</p>
      </div>

      <div class="pt-1">
        <div class="p-3.5 rounded-2xl bg-[#f0ede9] border border-[#993A3C]/15 max-w-lg">
          <div class="text-xs font-semibold uppercase tracking-wider text-[#7a2327] mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#7a2327]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Petunjuk Penyimpanan</span>
          </div>
          <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
            <div class="flex items-center gap-2 text-[#564241]">
              <svg class="w-4 h-4 text-[#7a2327] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"></path></svg>
              <div><span class="block text-[#897271] text-[11px]">Suhu Ruang</span><span class="font-bold text-[#1c1c19]" id="store-room">{{ $room }}</span></div>
            </div>
            <div class="flex items-center gap-2 text-[#564241]">
              <svg class="w-4 h-4 text-[#7a2327] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 2h8a2 2 0 012 2v16a2 2 0 01-2 2H8a2 2 0 01-2-2V4a2 2 0 012-2zm0 8h12"></path><circle cx="10" cy="6" r="0.5" fill="currentColor"></circle><circle cx="10" cy="15" r="0.5" fill="currentColor"></circle></svg>
              <div><span class="block text-[#897271] text-[11px]">Suhu Kulkas</span><span class="font-bold text-[#1c1c19]" id="store-fridge">{{ $fridge }}</span></div>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-4 flex items-center gap-6 sm:gap-8">
        <div class="flex items-center space-x-3">
          <button aria-label="Kurangi Jumlah" class="w-10 h-10 rounded-full bg-[#ffdad8]/60 hover:bg-[#ffdad8] active:scale-95 text-[#7a2327] border border-[#993A3C]/25 flex items-center justify-center font-bold text-xl transition duration-150 shadow-sm" id="btn-decrement" type="button">−</button>
          <span class="w-8 text-center font-bold text-2xl text-[#1c1c19] select-none" id="product-qty">1</span>
          <button aria-label="Tambah Jumlah" class="w-10 h-10 rounded-full bg-[#ffdad8]/60 hover:bg-[#ffdad8] active:scale-95 text-[#7a2327] border border-[#993A3C]/25 flex items-center justify-center font-bold text-xl transition duration-150 shadow-sm" id="btn-increment" type="button">+</button>
        </div>
        <button class="bg-[#993A3C] hover:bg-[#802f31] active:scale-95 text-white font-bold py-3.5 px-8 rounded-full shadow-md hover:shadow-lg transition-all duration-200 text-base flex items-center justify-center gap-2 tracking-wide" id="btn-add-to-cart" type="button">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
          <span>Tambah ke keranjang</span>
        </button>
      </div>
    </div>
  </section>
</main>

<footer class="py-8 text-center text-xs text-[#897271] border-t border-[#993A3C]/10 mt-8">
  <p>© 2024 Kueku Marinda. Seluruh Hak Cipta Dilindungi.</p>
</footer>

<script>
/* ---------- Keranjang (localStorage, dipakai bersama beranda, keranjang, dan checkout) ---------- */
const CART_KEY = "kueku_cart";
function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch (e) { return {}; } }
function saveCart(c) { try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {} }
function cartTotal() { return Object.values(getCart()).reduce((a, b) => a + b, 0); }

/* Data produk ini berasal dari database (lewat controller) */
const ID = "{{ $produk->id_produk }}";
const STOK = {{ (int) $produk->stok }};
const TERSEDIA = @json($tersedia);

document.addEventListener("DOMContentLoaded", () => {
  const qtyEl = document.getElementById("product-qty");
  const badgeEl = document.getElementById("cart-badge-count");
  const btnAdd = document.getElementById("btn-add-to-cart");
  badgeEl.textContent = cartTotal();

  if (!TERSEDIA) {
    btnAdd.disabled = true;
    btnAdd.classList.remove("bg-[#993A3C]", "hover:bg-[#802f31]");
    btnAdd.classList.add("bg-gray-300", "cursor-not-allowed");
    btnAdd.querySelector("span").textContent = "Stok habis";
    document.getElementById("btn-increment").disabled = true;
    document.getElementById("btn-decrement").disabled = true;
    return;
  }

  let qty = 1;
  document.getElementById("btn-increment").addEventListener("click", () => { if (qty < STOK) { qty++; qtyEl.textContent = qty; } });
  document.getElementById("btn-decrement").addEventListener("click", () => { if (qty > 1) { qty--; qtyEl.textContent = qty; } });

  btnAdd.addEventListener("click", () => {
    const cart = getCart();
    cart[ID] = Math.min((cart[ID] || 0) + qty, STOK);
    saveCart(cart);
    badgeEl.textContent = cartTotal();

    const original = btnAdd.innerHTML;
    btnAdd.classList.remove("bg-[#993A3C]", "hover:bg-[#802f31]");
    btnAdd.classList.add("bg-emerald-600", "hover:bg-emerald-700");
    btnAdd.innerHTML = '<svg class="w-5 h-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Berhasil Ditambahkan!</span>';
    setTimeout(() => {
      btnAdd.innerHTML = original;
      btnAdd.classList.remove("bg-emerald-600", "hover:bg-emerald-700");
      btnAdd.classList.add("bg-[#993A3C]", "hover:bg-[#802f31]");
    }, 1600);
  });
});
</script>
</body>
</html>
