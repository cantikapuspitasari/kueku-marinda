<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Detail Pesanan - Kueku Marinda</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          maroon: { 50: '#FDF6F6', 100: '#FBEBEB', 600: '#B04345', 700: '#993A3C', 800: '#7F2E30', 900: '#632123' },
          ivory: '#FCF9F4',
          surface: '#FFFFFF',
          warmgray: '#F6F3EE',
          bordercol: '#EAE5DC'
        },
        fontFamily: {
          serif: ['"Playfair Display"', 'serif'],
          sans: ['"Plus Jakarta Sans"', 'sans-serif']
        }
      }
    }
  }
</script>
<style>
  body { background-color: #FCF9F4; font-family: 'Plus Jakarta Sans', sans-serif; color: #2D2926; }
  .font-playfair { font-family: 'Playfair Display', serif; }
</style>
</head>
<body class="min-h-screen flex flex-col antialiased text-[#332C27] bg-[#FCF9F4]">

<!-- Header -->
<header class="sticky top-0 z-50 bg-[#FCF9F4] border-b border-[#993A3C]/15 px-8 py-3.5 flex items-center justify-between w-full">
  <div class="flex items-center gap-8">
    <a class="flex items-center gap-3.5 group flex-shrink-0" href="{{ url('/produk') }}">
      <img alt="Kueku Marinda Logo" class="w-10 h-10 object-contain rounded-full" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwGCIuOCoAITy8b29-Xl0ot1fCWvx77fWzcFmbq-CNcvsAALDEIM_2QGz1fdXFIMzulM4GUMdnlUnHbYGuUeNR79JFdlnWBZ4MFuFWOjKBD4vlqoQ5Xu9DZzuvcXcidqcpK4mgYB01LA9t_DmFzRAMDAhKttKNP1omrjEyj3212Ve8b4bgYnw4vjdeNxIO994iYurhY4sf9O0JKNcGVkkYgijs4szgldACK9gsNeWV1020qX6bmsq84g0fmdJU2N02nmM">
      <span class="font-playfair text-2xl font-bold text-[#993A3C] tracking-tight">Kueku Marinda</span>
    </a>
    <div class="hidden md:flex items-center bg-[#F6F3EE] border border-[#993A3C]/20 px-4 py-2 rounded-full w-72">
      <svg class="w-4 h-4 text-[#993A3C] flex-shrink-0 mr-2.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      <input class="bg-transparent text-xs text-[#7D7571] placeholder-[#7D7571] focus:outline-none w-full border-none p-0" placeholder="Cari..." type="text">
    </div>
  </div>
  <div class="flex items-center gap-6">
    <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-[#5C5552]">
      <a class="hover:text-[#993A3C] transition" href="{{ url('/produk') }}">Beranda</a>
      <a class="hover:text-[#993A3C] transition" href="{{ url('/keranjang') }}">keranjang</a>
      <a class="hover:text-[#993A3C] transition" href="{{ route('favorit') }}">Favorite</a>
      <a class="text-[#993A3C] font-semibold border-b-2 border-[#993A3C] pb-1" href="{{ route('pesanan.saya') }}">Pesanan</a>
    </nav>
    <a class="flex items-center gap-2 px-4 py-2 bg-[#F6F3EE] border border-[#993A3C]/20 hover:border-[#993A3C]/40 rounded-full text-sm text-[#4A4543] transition-colors" href="{{ route('profil') }}">
      <svg class="w-4 h-4 text-[#993A3C] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      <span class="whitespace-nowrap">Profil pelanggan</span>
    </a>
  </div>
</header>

<main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 max-w-7xl">

  <!-- Judul -->
  <section class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-[#E6E0D6]">
      <div class="flex items-center gap-3.5">
        <a class="w-11 h-11 rounded-full bg-white border border-[#DDD5C8] flex items-center justify-center text-maroon-700 hover:bg-maroon-700 hover:text-white transition shadow-xs" href="{{ url('/pesanan/selesai') }}" title="Kembali">
          <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
          <h1 class="font-playfair text-2xl sm:text-3xl font-bold text-neutral-900 tracking-tight">Detail Pesanan</h1>
          <p class="text-xs sm:text-sm text-neutral-500 mt-0.5">Pantau status perjalanan kue Anda secara langsung</p>
        </div>
      </div>
      <div class="flex items-center gap-3 bg-maroon-50 border border-maroon-200/80 px-4 py-2.5 rounded-xl text-xs sm:text-sm shadow-2xs">
        <i class="fa-solid fa-receipt text-maroon-700"></i>
        <span class="text-neutral-600 font-medium">No. Pesanan:</span>
        <span id="order-no" class="font-bold text-maroon-700 font-mono tracking-wide">-</span>
      </div>
    </div>
  </section>

  <div class="space-y-8">

    <!-- Status -->
    <section class="bg-[#F8F5F0] border border-[#E9E3D8] rounded-2xl p-6 sm:p-8 shadow-xs">
      <div class="flex items-center justify-between mb-8 pb-4 border-b border-[#E8E1D5]">
        <div class="flex items-center gap-3">
          <div class="w-2.5 h-6 bg-maroon-700 rounded-full"></div>
          <h2 id="status-title" class="font-playfair text-xl sm:text-2xl font-bold text-neutral-900">Status Pengantaran</h2>
        </div>
        <span class="px-3.5 py-1.5 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full flex items-center gap-2 shadow-2xs">
          <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
          <span id="status-badge">Pesanan Terkonfirmasi</span>
        </span>
      </div>
      <div id="timeline" class="relative pl-2 sm:pl-4"></div>
    </section>

    <!-- Rincian -->
    <section class="bg-[#F8F5F0] border border-[#E9E3D8] rounded-2xl p-6 sm:p-8 shadow-xs">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#E8E1D5]">
        <div class="w-2.5 h-6 bg-maroon-700 rounded-full"></div>
        <h2 class="font-playfair text-xl sm:text-2xl font-bold text-neutral-900">Rincian Pesanan</h2>
      </div>

      <div id="items-list" class="space-y-4 mb-6"></div>

      <div class="bg-white border border-[#E4DDD0] rounded-xl p-6 mb-8 shadow-2xs">
        <div class="space-y-3 text-sm pb-4 border-b border-[#ECE6DC]">
          <div class="flex justify-between text-neutral-600">
            <span>Subtotal Produk</span>
            <span id="sum-sub" class="font-medium text-neutral-900">Rp 0</span>
          </div>
          <div class="flex justify-between text-neutral-600">
            <span id="ship-label">Biaya Pengantaran</span>
            <span id="sum-ship" class="font-semibold text-neutral-900">Rp 0</span>
          </div>
        </div>
        <div class="pt-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
          <div>
            <span class="text-xs text-neutral-500 block uppercase tracking-wider font-semibold">Metode Pembayaran</span>
            <span class="font-bold text-neutral-800 flex items-center gap-2 mt-1">
              <i class="fa-solid fa-money-bill-wave text-emerald-600"></i>
              <span id="pay-method">Total Pembayaran (CASH / COD)</span>
            </span>
          </div>
          <div class="text-left sm:text-right">
            <span class="text-xs text-neutral-400 block sm:hidden">Total Biaya:</span>
            <span id="sum-total" class="text-2xl sm:text-3xl font-bold text-maroon-700">Rp 0</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button class="w-full py-3.5 px-6 rounded-xl bg-maroon-700 hover:bg-maroon-800 text-white font-semibold text-sm shadow-xs hover:shadow transition flex items-center justify-center gap-2" type="button">
          <i class="fa-solid fa-headset text-sm"></i>
          <span>Hubungi Admin</span>
        </button>
        <a class="w-full py-3.5 px-6 rounded-xl bg-white border border-[#D5CDC0] hover:bg-neutral-50 text-neutral-700 font-semibold text-sm shadow-2xs transition flex items-center justify-center gap-2" href="{{ url('/produk') }}">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          <span>Kembali ke Beranda</span>
        </a>
      </div>
    </section>

    <!-- Kurir (muncul setelah ada data kurir dari database) -->
    <section id="courier-section" class="hidden bg-[#F8F5F0] border border-[#E9E3D8] rounded-2xl p-6 sm:p-8 shadow-xs">
      <div class="flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 sm:gap-5 w-full md:w-auto">
          <div class="relative">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white border-2 border-white shadow-sm ring-2 ring-maroon-700/20 flex items-center justify-center text-maroon-700 text-2xl">
              <i class="fa-solid fa-motorcycle"></i>
            </div>
          </div>
          <div>
            <span class="text-xs uppercase tracking-wider text-neutral-400 font-semibold block">Kurir Pengantar</span>
            <h3 id="courier-name" class="text-lg sm:text-xl font-bold text-neutral-900 mt-0.5">-</h3>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-1.5 text-xs text-neutral-600">
              <span class="inline-flex items-center gap-1.5 bg-white px-3 py-1 rounded-md border border-[#E2DBD0] shadow-2xs">
                <i class="fa-solid fa-motorcycle text-maroon-700"></i>
                <span id="courier-vehicle">-</span>
              </span>
            </div>
          </div>
        </div>
        <div class="w-full md:w-auto flex-shrink-0">
          <a id="courier-wa" class="w-full md:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-[#25D366] hover:bg-[#20BD5A] text-white font-semibold text-sm rounded-xl shadow-xs hover:shadow transition" href="#" rel="noopener noreferrer" target="_blank">
            <i class="fa-brands fa-whatsapp text-lg"></i>
            <span>Chat melalui WhatsApp</span>
          </a>
        </div>
      </div>
    </section>

  </div>
</main>

<footer class="mt-16 bg-[#FCF9F4] border-t border-[#EAE5DC] w-full px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#7A726D]">
  <div class="flex items-center gap-3">
    <img alt="Kueku Marinda Logo" class="w-8 h-8 rounded-full object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwGCIuOCoAITy8b29-Xl0ot1fCWvx77fWzcFmbq-CNcvsAALDEIM_2QGz1fdXFIMzulM4GUMdnlUnHbYGuUeNR79JFdlnWBZ4MFuFWOjKBD4vlqoQ5Xu9DZzuvcXcidqcpK4mgYB01LA9t_DmFzRAMDAhKttKNP1omrjEyj3212Ve8b4bgYnw4vjdeNxIO994iYurhY4sf9O0JKNcGVkkYgijs4szgldACK9gsNeWV1020qX6bmsq84g0fmdJU2N02nmM">
    <span class="font-playfair font-bold text-sm text-[#993A3C]">Kueku Marinda</span>
    <span>•</span>
    <p>© 2026 Kueku Marinda. Seluruh Hak Cipta Dilindungi.</p>
  </div>
  <div class="flex items-center gap-6 font-medium text-xs">
    <a class="hover:text-[#993A3C] transition" href="#">Syarat &amp; Ketentuan</a>
    <a class="hover:text-[#993A3C] transition" href="#">Kebijakan Privasi</a>
    <a class="hover:text-[#993A3C] transition" href="#">Bantuan &amp; FAQ</a>
  </div>
</footer>

<script>
/* ---------- Data produk (id sama dengan data-id di beranda) ---------- */
const PRODUCTS = @json($produkJs);

const ONGKIR = {{ \App\Http\Controllers\PesananController::ONGKIR }};
const rp = n => "Rp " + n.toLocaleString("id-ID");

/* Pesanan terakhir (disimpan oleh halaman checkout) */
let order = null;
const KODE_URL = new URLSearchParams(location.search).get("kode");
if (KODE_URL) {
  order = { no: "#" + KODE_URL, cart: {} };            // pesanan yang dipilih dari "Pesanan Saya"
} else {
  try { order = JSON.parse(localStorage.getItem("kueku_last_order")); } catch (e) {}
}
let mode = order && order.mode === "toko" ? "toko" : "kurir";

/* Tahap status saat ini: 0 = Pesanan di konfirmasi.
   Nanti bisa diisi dari database (status_pesanan) supaya timeline ikut berubah. */
let CURRENT = -1;
let STATE = "menunggu"; // menunggu | ok | batal

const STEPS = {
  kurir: [
    { icon: "fa-check", title: "Pesanan di konfirmasi", desc: "Pesanan telah diverifikasi oleh dapur kami. Resep lezat mulai dipersiapkan oleh tim pastry.", badge: "Pesanan Terkonfirmasi" },
    { icon: "fa-box", title: "Sedang di kemas", desc: "Kue baru matang selesai dihias dan dikemas rapat dengan kotak higienis bersegel pita Kueku Marinda.", badge: "Sedang Dikemas" },
    { icon: "fa-motorcycle", title: "Di serahkan ke kurir", desc: "Paket diserahkan ke kurir resmi Kueku Marinda untuk dibawa ke tas pendingin delivery.", badge: "Diserahkan ke Kurir" },
    { icon: "fa-route", title: "Kurir menuju lokasimu", desc: "Kurir sedang dalam perjalanan menuju alamat tujuan Anda.", badge: "Sedang Dalam Perjalanan" },
    { icon: "fa-house-chimney-check", title: "Pesanan sampai tujuan", desc: "Pastikan penerima bersiap di lokasi untuk konfirmasi dan pembayaran tunai (CASH).", badge: "Pesanan Sampai" }
  ],
  toko: [
    { icon: "fa-check", title: "Pesanan di konfirmasi", desc: "Pesanan telah diverifikasi oleh dapur kami. Resep lezat mulai dipersiapkan oleh tim pastry.", badge: "Pesanan Terkonfirmasi" },
    { icon: "fa-box", title: "Sedang di kemas", desc: "Kue baru matang selesai dihias dan dikemas rapat dengan kotak higienis bersegel pita Kueku Marinda.", badge: "Sedang Dikemas" },
    { icon: "fa-store", title: "Siap diambil di toko", desc: "Pesanan sudah siap di toko Kueku Marinda. Tunjukkan Nomor Pesanan atau nomor WhatsApp kepada kasir.", badge: "Siap Diambil" },
    { icon: "fa-route", title: "Menuju toko", desc: "Datang ke toko sesuai jadwal pengambilan yang telah kamu pilih.", badge: "Menuju Toko" },
    { icon: "fa-house-chimney-check", title: "Pesanan diterima", desc: "Lakukan pembayaran tunai di kasir saat pesanan diambil.", badge: "Pesanan Diterima" }
  ]
};

function renderTimeline() {
  const steps = STEPS[mode];
  let html = "";
  steps.forEach((s, i) => {
    const done = i < CURRENT, active = i === CURRENT, last = i === steps.length - 1;
    const line = last ? "" :
      '<div class="absolute left-5 sm:left-6 top-10 bottom-0 w-[3px] ' + (done ? "bg-maroon-700" : "bg-[#D6D0C5]") + '"></div>';

    let circle, title, desc;
    if (done || active) {
      circle = '<div class="relative z-10 w-10 sm:w-12 h-10 sm:h-12 rounded-full bg-maroon-700 text-white flex items-center justify-center ' + (active ? "shadow-lg" : "shadow-md") + ' flex-shrink-0 ring-4 ' + (active ? "ring-maroon-200" : "ring-maroon-100") + '"><i class="fa-solid ' + s.icon + ' text-base"></i></div>';
    } else {
      circle = '<div class="relative z-10 w-10 sm:w-12 h-10 sm:h-12 rounded-full bg-[#E5E1D8] text-neutral-400 flex items-center justify-center border-2 border-[#D3CCC0] flex-shrink-0"><i class="fa-solid ' + s.icon + ' text-sm"></i></div>';
    }
    if (active) {
      title = '<div class="flex items-center gap-2"><h3 class="text-base sm:text-lg font-bold text-maroon-700">' + s.title + '</h3><span class="text-[11px] font-semibold bg-maroon-700 text-white px-2 py-0.5 rounded">Proses</span></div>';
      desc = '<p class="text-xs sm:text-sm text-neutral-700 font-medium leading-relaxed max-w-3xl">' + s.desc + '</p>';
    } else if (done) {
      title = '<h3 class="text-base sm:text-lg font-bold text-neutral-900">' + s.title + '</h3>';
      desc = '<p class="text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-3xl">' + s.desc + '</p>';
    } else {
      title = '<h3 class="text-base sm:text-lg font-semibold text-neutral-500">' + s.title + '</h3>';
      desc = '<p class="text-xs sm:text-sm text-neutral-400 leading-relaxed max-w-3xl">' + s.desc + '</p>';
    }

    html += '<div class="relative flex gap-5 sm:gap-7 ' + (last ? "" : "pb-9 ") + 'group">' + line + circle +
      '<div class="pt-1 flex-1"><div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-1">' + title + '</div>' + desc + '</div></div>';
  });
  if (STATE === "batal") {
    html = '<div class="p-5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">Pesanan ini dibatalkan. Hubungi admin bila ada pertanyaan.</div>';
  }
  document.getElementById("timeline").innerHTML = html;
  document.getElementById("status-badge").textContent =
    STATE === "batal" ? "Dibatalkan" : (CURRENT < 0 ? "Menunggu Verifikasi Admin" : steps[CURRENT].badge);
  document.getElementById("status-title").textContent = mode === "toko" ? "Status Pengambilan" : "Status Pengantaran";
}

function renderOrder() {
  document.getElementById("order-no").textContent = order && order.no ? order.no : "-";

  const cart = order && order.cart ? order.cart : {};
  let sub = 0, count = 0, html = "";
  Object.keys(cart).forEach(id => {
    const p = PRODUCTS[id], qty = cart[id];
    if (!p || qty < 1) return;
    sub += p.price * qty; count += qty;
    html += `
    <div class="flex items-center justify-between p-4 sm:p-5 bg-white border border-[#E4DDD0] rounded-xl shadow-2xs transition hover:border-maroon-200">
      <div class="flex items-center gap-4 sm:gap-5">
        <img alt="${p.name}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover border border-[#ECE6DC]" src="${p.img}">
        <div>
          <h3 class="font-semibold text-base sm:text-lg text-neutral-900">${p.name}</h3>
          <p class="text-xs sm:text-sm text-neutral-500 mt-0.5">${p.sub}</p>
          <div class="inline-block mt-2 px-2.5 py-0.5 bg-neutral-100 text-neutral-700 text-xs font-semibold rounded">${qty}x</div>
        </div>
      </div>
      <div class="text-right">
        <span class="text-xs text-neutral-400 block mb-0.5">Harga</span>
        <span class="font-bold text-base sm:text-lg text-neutral-900">${rp(p.price * qty)}</span>
      </div>
    </div>`;
  });
  document.getElementById("items-list").innerHTML = html || '<p class="text-sm text-neutral-500">Belum ada data pesanan.</p>';

  const shipEl = document.getElementById("sum-ship");
  let ship = 0;
  if (mode === "kurir") {
    ship = count ? ONGKIR : 0;
    document.getElementById("ship-label").textContent = "Ongkos Kirim";
    shipEl.textContent = rp(ship);
    shipEl.className = "font-semibold text-neutral-900";
    document.getElementById("pay-method").textContent = "Total Pembayaran (CASH / COD)";
  } else {
    document.getElementById("ship-label").textContent = "Biaya Pengambilan";
    shipEl.textContent = "GRATIS";
    shipEl.className = "text-emerald-700 font-semibold";
    document.getElementById("pay-method").textContent = "Total Pembayaran (Bayar di Toko)";
  }
  document.getElementById("sum-sub").textContent = rp(sub);
  document.getElementById("sum-total").textContent = rp(sub + ship);
}

renderTimeline();
renderOrder();

/* ---------- Status asli dari database (diperbarui otomatis tiap 8 detik) ---------- */
function tampilkanKurir(k) {
  const box = document.getElementById("courier-section");
  if (!k) { box.classList.add("hidden"); return; }
  box.classList.remove("hidden");
  document.getElementById("courier-name").textContent = k.nama || "-";
  document.getElementById("courier-vehicle").textContent = "Kurir Kueku Marinda";
  const no = (k.no_telepon || "").replace(/\D/g, "").replace(/^0/, "62");
  document.getElementById("courier-wa").href = no ? "https://wa.me/" + no : "#";
}

async function muatStatus() {
  if (!order || !order.no) return;
  const kode = String(order.no).replace("#", "");
  try {
    const res = await fetch("{{ url('/pesanan/lacak') }}/" + encodeURIComponent(kode), {
      headers: { "Accept": "application/json" }, credentials: "same-origin"
    });
    if (!res.ok) return;
    const data = await res.json();
    mode = data.mode === "toko" ? "toko" : "kurir";
    order = { no: "#" + data.kode, cart: data.cart || {}, mode: mode };
    STATE = data.state;
    CURRENT = data.tahap;
    renderTimeline();
    renderOrder();
    tampilkanKurir(data.kurir);
  } catch (e) { /* abaikan, coba lagi nanti */ }
}
muatStatus();
setInterval(muatStatus, 8000);
</script>
</body>
</html>
