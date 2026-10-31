<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Keranjang Saya - Kueku Marinda</title>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          maroon: { DEFAULT: '#993A3C', light: '#b14548', dark: '#7e2d2f', 50: '#fdf7f7', 100: '#faedee' },
          ivory: { DEFAULT: '#FCF9F4', card: '#F6F3EE', subtle: '#EFECE6' },
          charcoal: { DEFAULT: '#2D2828', muted: '#696464' }
        },
        fontFamily: {
          serif: ['"Playfair Display"', 'Georgia', 'serif'],
          sans: ['"Plus Jakarta Sans"', 'sans-serif']
        }
      }
    }
  }
</script>
<style>
  body { background-color: #FCF9F4; color: #2D2828; font-family: 'Plus Jakarta Sans', sans-serif; }
  .font-brand { font-family: 'Playfair Display', Georgia, serif; }
</style>
</head>
<body class="min-h-screen flex flex-col antialiased bg-[#FCF9F4] text-[#2D2828]">

<!-- Header -->
<header class="w-full bg-[#FCF9F4] border-b border-[#993A3C]/15 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
  <a class="flex items-center gap-3 group shrink-0" href="{{ url('/produk') }}">
    <img alt="Logo Kueku Marinda" class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-[#993A3C]/20 transition-transform group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCCNDZeOiEWl9DZfb3lIlaF9dpCVz7cWrvmSCrNlK9ggI4UQMVVTD0MvoQftuJPVkqUUgYAy1azxYysOg40FNLQAw7oyvFQ0pIU0tn53NojNRP1xB62C_9g2gcVwcSkn8dsCfIaZP9FxLpOdWag4P8MEL6lxGa80T83jY5BqH1yfjabDn_WK1I7msiTmLfQUE0M3aFYzUzcpWKtQQftLCCim1AV0kwCpet2JWwENUt0Q8IGiZ-xzOAEwPRVLYw3Ur-CSqo">
    <span class="font-brand text-2xl font-bold tracking-tight text-[#993A3C]">Kueku Marinda</span>
  </a>
  <div class="flex-1 max-w-xs md:max-w-sm lg:max-w-md mx-2">
    <div class="relative">
      <input class="w-full bg-[#F6F3EE] border border-[#993A3C]/20 rounded-full py-2 pl-10 pr-4 text-sm text-[#2D2828] placeholder-[#696464] focus:outline-none focus:ring-2 focus:ring-[#993A3C] focus:border-transparent transition-all" placeholder="Cari..." type="text">
      <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#696464]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </div>
    </div>
  </div>
  <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-sm font-medium">
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ url('/produk') }}">Beranda</a>
    <a class="text-[#993A3C] font-semibold border-b-2 border-[#993A3C] pb-0.5 tracking-wide inline-flex items-center gap-1.5" href="{{ url('/keranjang') }}">keranjang
      <span id="cart-badge-count" class="bg-[#993A3C] text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center">0</span>
    </a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ route('favorit') }}">Favorite</a>
    <a class="text-[#696464] hover:text-[#993A3C] transition-colors py-1" href="{{ route('pesanan.saya') }}">Pesanan</a>
  </nav>
  <div class="flex items-center">
    <a class="inline-flex items-center gap-2 bg-[#F6F3EE] hover:bg-[#EFECE6] border border-[#993A3C]/20 text-[#2D2828] px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-all shadow-sm" href="{{ route('profil') }}">
      <svg class="w-4 h-4 text-[#993A3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      <span class="whitespace-nowrap">Profil pelanggan</span>
    </a>
  </div>
</div>
</header>

<!-- Konten -->
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
  <section class="mb-6">
    <div class="flex items-center gap-3">
      <a aria-label="Kembali" href="{{ url('/produk') }}" class="p-2 rounded-full text-[#2D2828] hover:text-[#993A3C] hover:bg-[#F6F3EE] transition-colors">
        <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
      </a>
      <h1 class="font-brand text-2xl sm:text-3xl font-bold tracking-tight text-[#2D2828]">Keranjang saya</h1>
    </div>
    <hr class="mt-4 border-t border-[#993A3C]/15">
  </section>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Daftar item (diisi otomatis oleh script) -->
    <section aria-label="Daftar Kue dalam Keranjang" class="lg:col-span-7 space-y-4" id="cart-list"></section>

    <!-- Ringkasan pesanan -->
    <aside class="lg:col-span-5">
      <div class="bg-[#F6F3EE] border border-[#993A3C]/15 rounded-2xl p-6 sm:p-7 shadow-sm sticky top-28">
        <h2 class="font-brand text-xl font-bold text-[#2D2828] pb-4 border-b border-[#993A3C]/15">Ringkasan pesanan</h2>

        <div class="py-4 space-y-3.5 text-sm">
          <div class="flex justify-between items-center text-[#696464]">
            <span>Subtotal <span class="text-xs text-[#696464]/80 font-medium">(<span id="sum-count">0</span> item)</span></span>
            <span class="font-semibold text-[#2D2828]" id="sum-sub">Rp 0</span>
          </div>
          <div class="flex justify-between items-center text-[#696464]">
            <span class="flex items-center gap-1.5">
              Ongkos kirim
              <span class="inline-block" title="Dihitung berdasarkan lokasi pengantaran">
                <svg class="w-3.5 h-3.5 text-[#696464]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle><path d="M12 16v-4m0-4h.01" stroke-width="2"></path></svg>
              </span>
            </span>
            <span class="font-semibold text-[#2D2828]" id="sum-ship">Rp 0</span>
          </div>
        </div>

        <div class="border-t border-[#993A3C]/15 pt-4 mb-6">
          <div class="flex justify-between items-baseline">
            <span class="font-brand text-lg font-bold text-[#2D2828]">Total</span>
            <span class="text-2xl font-bold text-[#993A3C] tracking-tight" id="sum-total">Rp 0</span>
          </div>
          <p class="text-[11px] text-[#696464] mt-1 text-right">Sudah termasuk pajak &amp; kemasan aman</p>
        </div>

        <a href="{{ url('/checkout') }}" class="w-full bg-[#993A3C] hover:bg-[#7e2d2f] text-white py-3.5 px-6 rounded-full font-semibold text-base transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.99] flex items-center justify-center gap-2 group">
          <span>Lanjut</span>
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>

        <div class="mt-4 flex items-center justify-center gap-2 text-xs text-[#696464]">
          <svg class="w-4 h-4 text-[#993A3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          <span>Pemesanan higienis &amp; jaminan fresh bake</span>
        </div>
      </div>
    </aside>
  </div>
</main>

<footer class="mt-16 border-t border-[#993A3C]/10 bg-[#F6F3EE]/60 py-6 text-center text-xs text-[#696464]">
  <div class="max-w-7xl mx-auto px-4">
    <p>© 2026 Kueku Marinda. Semua hak cipta dilindungi.</p>
  </div>
</footer>

<script>
/* ---------- Data produk (id harus sama dengan data-id di beranda) ---------- */
const PRODUCTS = @json($produkJs);

/* ---------- Keranjang ---------- */
const CART_KEY = "kueku_cart";
const ONGKIR = {{ \App\Http\Controllers\PesananController::ONGKIR }};
const rp = n => "Rp " + n.toLocaleString("id-ID");

function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch (e) { return {}; } }
function saveCart(c) { try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {} }

function renderCart() {
  const cart = getCart();
  const list = document.getElementById("cart-list");
  let count = 0, sub = 0, html = "";

  Object.keys(cart).forEach(id => {
    const p = PRODUCTS[id], qty = cart[id];
    if (!p || qty < 1) return;
    count += qty;
    sub += p.price * qty;
    html += `
    <article class="bg-[#F6F3EE] border border-[#993A3C]/15 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 shadow-sm hover:shadow transition-shadow" data-id="${id}">
      <div class="relative w-full sm:w-28 h-28 shrink-0 rounded-xl overflow-hidden bg-white border border-[#993A3C]/10 flex items-center justify-center">
        <img alt="${p.name}" class="w-full h-full object-cover" src="${p.img}">
      </div>
      <div class="flex-1 w-full flex flex-col justify-between">
        <div>
          <h2 class="font-brand text-lg font-semibold text-[#2D2828] line-clamp-1">${p.name}</h2>
          <p class="text-xs text-[#696464] mt-0.5">${p.sub}</p>
        </div>
        <div class="mt-2 text-base font-bold text-[#993A3C]">${rp(p.price)}</div>
      </div>
      <div class="flex items-center justify-between w-full sm:w-auto gap-4 shrink-0 border-t sm:border-t-0 pt-3 sm:pt-0 border-[#993A3C]/10">
        <div class="flex items-center bg-white border border-[#993A3C]/20 rounded-full px-2 py-1 shadow-inner gap-3">
          <button data-act="dec" aria-label="Kurangi kuantitas" class="w-6 h-6 flex items-center justify-center rounded-full text-[#993A3C] hover:bg-[#993A3C]/10 font-bold text-sm transition-colors" type="button">−</button>
          <span class="text-sm font-semibold text-[#2D2828] min-w-[1.2rem] text-center">${qty}</span>
          <button data-act="inc" aria-label="Tambah kuantitas" class="w-6 h-6 flex items-center justify-center rounded-full text-[#993A3C] hover:bg-[#993A3C]/10 font-bold text-sm transition-colors" type="button">+</button>
        </div>
        <button data-act="del" aria-label="Hapus item" class="w-9 h-9 rounded-full bg-white/80 hover:bg-[#993A3C]/10 border border-[#993A3C]/20 text-[#696464] hover:text-[#993A3C] flex items-center justify-center transition-colors shadow-sm" title="Hapus dari keranjang" type="button">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path></svg>
        </button>
      </div>
    </article>`;
  });

  list.innerHTML = html;

  const ship = count ? ONGKIR : 0;
  document.getElementById("sum-count").textContent = count;
  document.getElementById("sum-sub").textContent = rp(sub);
  document.getElementById("sum-ship").textContent = rp(ship);
  document.getElementById("sum-total").textContent = rp(sub + ship);
  document.getElementById("cart-badge-count").textContent = count;
}

document.getElementById("cart-list").addEventListener("click", e => {
  const btn = e.target.closest("button[data-act]");
  if (!btn) return;
  const id = btn.closest("article").dataset.id;
  const cart = getCart();
  if (btn.dataset.act === "inc") cart[id] = (cart[id] || 0) + 1;
  if (btn.dataset.act === "dec") cart[id] = Math.max(1, (cart[id] || 1) - 1);
  if (btn.dataset.act === "del") delete cart[id];
  saveCart(cart);
  renderCart();
});

renderCart();
</script>
</body>
</html>
