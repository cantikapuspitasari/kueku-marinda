<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Favorit - Kueku Marinda</title>
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
<header class="w-full bg-[#FCF9F4] border-b border-[#993A3C]/15 sticky top-0 z-50">
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
    <a class="text-[#993A3C] font-semibold border-b-2 border-[#993A3C] pb-0.5 tracking-wide" href="{{ route('favorit') }}">Favorite</a>
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

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
  <div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-gray-900">Produk Favorit</h1>
    <p class="text-sm text-gray-500 mt-1">Kue dan roti yang Anda tandai dengan ikon hati.</p>
  </div>

  <div id="fav-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"></div>

  <div id="fav-empty" class="hidden text-center py-20 bg-white rounded-2xl border border-gray-200">
    <div class="w-16 h-16 mx-auto rounded-full bg-maroon-100 text-maroon flex items-center justify-center mb-4">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    </div>
    <h2 class="font-serif text-xl font-bold text-gray-900">Belum ada favorit</h2>
    <p class="text-sm text-gray-500 mt-1 mb-6">Tekan ikon hati pada produk di beranda untuk menyimpannya di sini.</p>
    <a href="{{ url('/produk') }}" class="inline-flex px-6 py-2.5 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-sm font-semibold transition">Lihat Menu</a>
  </div>
</main>

<script>
/* Data produk dari database (kunci = id_produk) */
const PRODUCTS = @json($produkJs);
const rp = n => "Rp " + Number(n).toLocaleString("id-ID");

const CART_KEY = "kueku_cart", FAV_KEY = "kueku_fav";
function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) || {}; } catch (e) { return {}; } }
function saveCart(c) { try { localStorage.setItem(CART_KEY, JSON.stringify(c)); } catch (e) {} }
function getFav() { try { return JSON.parse(localStorage.getItem(FAV_KEY)) || []; } catch (e) { return []; } }
function saveFav(a) { try { localStorage.setItem(FAV_KEY, JSON.stringify(a)); } catch (e) {} }
function refreshBadge() {
  const b = document.getElementById("cart-badge-count");
  if (b) b.textContent = Object.values(getCart()).reduce((a, c) => a + c, 0);
}
const esc = s => String(s).replace(/[&<>"]/g, m => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[m]));

function render() {
  const grid = document.getElementById("fav-grid");
  const favs = getFav().filter(id => PRODUCTS[id]);   // abaikan produk yang sudah dihapus dari database
  let html = "";
  favs.forEach(id => {
    const p = PRODUCTS[id];
    const ada = p.aktif && p.stok > 0;
    html += `
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden flex flex-col product-shadow transition" data-id="${id}">
      <div class="relative w-full aspect-[4/3] bg-gray-100">
        <a href="{{ url('/produk') }}/${id}">${p.img ? `<img alt="${esc(p.name)}" class="w-full h-full object-cover" src="${p.img}">` : ""}</a>
        <button data-aksi="hapus" aria-label="Hapus dari favorit" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-[#993A3C] flex items-center justify-center shadow transition">
          <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
        </button>
      </div>
      <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
        <div>
          <h4 class="font-semibold text-gray-900 text-base"><a href="{{ url('/produk') }}/${id}">${esc(p.name)}</a></h4>
          <p class="text-xs text-gray-500 mt-1">${esc(p.sub || "")}</p>
        </div>
        <div class="pt-2 border-t border-gray-100 flex items-center justify-end"><span class="text-sm font-bold text-maroon">${rp(p.price)}</span></div>
        ${ada
          ? `<button data-aksi="tambah" class="w-full py-2 bg-maroon hover:bg-maroon-600 text-white rounded-xl text-xs font-semibold transition shadow-sm"><span>tambah ke keranjang</span></button>`
          : `<button class="w-full py-2 bg-gray-200 text-gray-500 rounded-xl text-xs font-semibold cursor-not-allowed" disabled type="button">Stok habis</button>`}
      </div>
    </div>`;
  });
  grid.innerHTML = html;
  document.getElementById("fav-empty").classList.toggle("hidden", favs.length > 0);
}

document.addEventListener("click", e => {
  const btn = e.target.closest("button[data-aksi]");
  if (!btn) return;
  const id = btn.closest("[data-id]").dataset.id;

  if (btn.dataset.aksi === "hapus") {
    saveFav(getFav().filter(x => x !== id));
    render();
    return;
  }
  if (btn.dataset.aksi === "tambah") {
    const cart = getCart();
    cart[id] = (cart[id] || 0) + 1;
    saveCart(cart);
    refreshBadge();
    const span = btn.querySelector("span"), old = span.textContent;
    span.textContent = "Ditambahkan ✓";
    setTimeout(() => { span.textContent = old; }, 1200);
  }
});

refreshBadge();
render();
</script>
</body>
</html>
