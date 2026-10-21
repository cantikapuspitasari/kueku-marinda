@extends('admin.layout', ['aktif' => 'produk'])

@section('title', 'Kelola Produk')
@section('judul', 'Kelola Produk')
@section('subjudul', 'Atur katalog kue, harga, foto produk, dan stok etalase. Perubahan langsung tampil di toko pelanggan.')

@section('aksi')
  <a href="{{ route('admin.produk.create') }}" class="inline-flex items-center justify-center gap-2 bg-maroon hover:bg-maroon-dark text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v12M6 12h12" stroke-linecap="round" stroke-width="2.5"></path></svg>
    Tambah Produk
  </a>
@endsection

@php
  $namaKategori = fn ($p) => $p->kategori->nama_kategori ?? $p->kategori->nama ?? '-';
@endphp

@section('isi')
<!-- Filter kategori + cari -->
<section class="flex flex-wrap items-center justify-between gap-3">
  <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar" id="filter-kategori">
    <button type="button" data-kat="" class="tab-kat bg-maroon text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm whitespace-nowrap transition">Semua Produk ({{ $produk->count() }})</button>
    @foreach ($kategori as $k)
      <button type="button" data-kat="{{ $k->id }}" class="tab-kat bg-white hover:bg-[#F6F3EE] text-stone-700 border border-warm text-xs font-medium px-4 py-2 rounded-xl whitespace-nowrap transition">{{ $k->nama }} ({{ $produk->where('id_kategori', $k->id)->count() }})</button>
    @endforeach
  </div>
  <input id="cari" type="text" placeholder="Cari nama produk..." class="text-xs border-stone-300 rounded-xl focus:border-maroon focus:ring-maroon py-2.5 px-3.5 w-56 bg-white">
</section>

<!-- Kartu produk -->
<section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="grid-produk">
  @forelse ($produk as $p)
    @php
      $foto = $p->foto_produk ? asset('images/produk/' . $p->foto_produk) : '';
      $badge = [
        'TERSEDIA'   => ['Tersedia',   'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'STOK_HABIS' => ['Stok Habis', 'bg-rose-50 text-rose-700 border-rose-200'],
        'NONAKTIF'   => ['Nonaktif',   'bg-stone-100 text-stone-600 border-stone-200'],
      ][$p->status_produk] ?? [$p->status_produk, 'bg-stone-100 text-stone-600 border-stone-200'];
    @endphp
    <article class="kartu bg-white rounded-2xl p-4 border border-warm shadow-sm hover:shadow-md transition flex flex-col justify-between {{ $p->status_produk === 'NONAKTIF' ? 'opacity-70' : '' }}" data-kat="{{ $p->id_kategori }}" data-nama="{{ strtolower($p->nama_produk) }}">
      <div>
        <div class="relative w-full aspect-[16/10] bg-stone-100 rounded-xl overflow-hidden mb-4 border border-stone-100 group">
          @if ($foto)
            <img alt="{{ $p->nama_produk }}" src="{{ $foto }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
          @else
            <div class="w-full h-full flex items-center justify-center text-xs text-stone-400">Belum ada foto</div>
          @endif
          <div class="absolute top-2.5 right-2.5 bg-white/90 backdrop-blur-sm text-xs font-bold text-stone-700 px-2 py-1 rounded-lg shadow-sm border border-black/5">Stok: {{ $p->stok }} pcs</div>
        </div>
        <div class="space-y-1">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-block bg-maroon/10 text-maroon font-semibold text-xs px-2.5 py-0.5 rounded-full">{{ $namaKategori($p) }}</span>
            <span class="inline-block border text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $badge[1] }}">{{ $badge[0] }}</span>
          </div>
          <h3 class="text-base font-bold text-stone-900 line-clamp-1 pt-1">{{ $p->nama_produk }}</h3>
          <p class="text-xs text-stone-500 font-medium">Harga</p>
          <p class="text-lg font-bold text-maroon">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
        </div>

        <!-- Ubah stok cepat -->
        <form method="POST" action="{{ route('admin.produk.stok', $p->id_produk) }}" class="mt-3 flex items-center gap-2">
          @csrf
          @method('PATCH')
          <label class="text-xs font-medium text-stone-500" for="stok-{{ $p->id_produk }}">Stok</label>
          <input id="stok-{{ $p->id_produk }}" name="stok" type="number" min="0" max="100000" value="{{ $p->stok }}" class="w-24 text-xs border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-1.5 px-2.5">
          <button type="submit" class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-maroon hover:text-white text-xs font-semibold text-stone-700 transition">Simpan</button>
        </form>
      </div>

      <div class="flex items-center gap-2 mt-5 pt-3 border-t border-warm/60">
        <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="flex-1 py-2 px-3 border border-maroon text-maroon hover:bg-maroon hover:text-white rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>Edit
        </a>
        <form method="POST" action="{{ route('admin.produk.destroy', $p->id_produk) }}" class="flex-1" onsubmit="return confirm('Hapus produk {{ addslashes($p->nama_produk) }}?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="w-full py-2 px-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>Hapus
          </button>
        </form>
      </div>
    </article>
  @empty
    <div class="col-span-full bg-white rounded-2xl border border-warm p-10 text-center text-sm text-stone-500">Belum ada produk. Klik "Tambah Produk" untuk mulai.</div>
  @endforelse
</section>
<p id="kosong" class="hidden text-center text-sm text-stone-500 py-10">Tidak ada produk yang cocok.</p>
@endsection

@push('scripts')
<script>
  // Filter kategori + pencarian (di sisi browser, tanpa reload)
  let katAktif = "";
  const kartu = document.querySelectorAll(".kartu");
  const cari = document.getElementById("cari");

  function terapkan() {
    const q = cari.value.trim().toLowerCase();
    let tampil = 0;
    kartu.forEach(k => {
      const ok = (!katAktif || k.dataset.kat === katAktif) && (!q || k.dataset.nama.includes(q));
      k.classList.toggle("hidden", !ok);
      if (ok) tampil++;
    });
    document.getElementById("kosong").classList.toggle("hidden", tampil > 0 || kartu.length === 0);
  }

  document.querySelectorAll(".tab-kat").forEach(b => b.addEventListener("click", () => {
    katAktif = b.dataset.kat;
    document.querySelectorAll(".tab-kat").forEach(x => {
      const on = x === b;
      x.classList.toggle("bg-maroon", on);
      x.classList.toggle("text-white", on);
      x.classList.toggle("shadow-sm", on);
      x.classList.toggle("bg-white", !on);
      x.classList.toggle("text-stone-700", !on);
      x.classList.toggle("border", !on);
      x.classList.toggle("border-warm", !on);
    });
    terapkan();
  }));
  cari.addEventListener("input", terapkan);
</script>
@endpush
