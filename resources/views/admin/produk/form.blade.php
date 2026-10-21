@extends('admin.layout', ['aktif' => 'produk'])

@php
  $edit = !is_null($produk);
  $fotoSekarang = $edit && $produk->foto_produk ? asset('images/produk/' . $produk->foto_produk) : '';
@endphp

@section('title', $edit ? 'Edit Produk' : 'Tambah Produk')
@section('judul', $edit ? 'Edit Produk' : 'Tambah Produk')
@section('subjudul', 'Data yang disimpan di sini langsung dipakai halaman toko pelanggan.')

@section('aksi')
  <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 rounded-xl border border-warm text-xs font-semibold text-stone-700 bg-white hover:bg-stone-50 transition">Kembali</a>
@endsection

@section('isi')
<form method="POST" enctype="multipart/form-data" action="{{ $edit ? route('admin.produk.update', $produk->id_produk) : route('admin.produk.store') }}" class="bg-white rounded-2xl border border-warm shadow-sm p-6 lg:p-8 grid lg:grid-cols-3 gap-8">
  @csrf
  @if ($edit) @method('PUT') @endif

  <div class="lg:col-span-2 space-y-5">
    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="nama_produk">Nama Produk</label>
      <input id="nama_produk" name="nama_produk" type="text" required value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3">
    </div>

    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="id_kategori">Kategori</label>
      <select id="id_kategori" name="id_kategori" required class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3 bg-white">
        <option value="" disabled {{ old('id_kategori', $produk->id_kategori ?? '') === '' ? 'selected' : '' }}>Pilih kategori...</option>
        @foreach ($kategori as $k)
          <option value="{{ $k->id }}" {{ (string) old('id_kategori', $produk->id_kategori ?? '') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
        @endforeach
      </select>
      <p class="text-xs text-stone-400 mt-1">Kategori belum ada? Tambahkan dulu di <a class="text-maroon font-semibold" href="{{ route('admin.kategori.index') }}">Kelola Kategori</a>.</p>
    </div>

    <div>
      <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
    </div>

    <div class="grid sm:grid-cols-3 gap-5">
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="harga">Harga (Rp)</label>
        <input id="harga" name="harga" type="number" min="0" step="1" required value="{{ old('harga', isset($produk) ? (int) $produk->harga : '') }}" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3">
      </div>
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="stok">Stok</label>
        <input id="stok" name="stok" type="number" min="0" required value="{{ old('stok', $produk->stok ?? 0) }}" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3">
      </div>
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2" for="status_produk">Status</label>
        @php $statusLama = old('status_produk', ($produk->status_produk ?? 'TERSEDIA') === 'NONAKTIF' ? 'NONAKTIF' : 'TERSEDIA'); @endphp
        <select id="status_produk" name="status_produk" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3 bg-white">
          <option value="TERSEDIA" {{ $statusLama !== 'NONAKTIF' ? 'selected' : '' }}>Aktif (mengikuti stok)</option>
          <option value="NONAKTIF" {{ $statusLama === 'NONAKTIF' ? 'selected' : '' }}>Nonaktif (disembunyikan)</option>
        </select>
      </div>
    </div>
    <p class="text-xs text-stone-400">Stok 0 otomatis menjadi "Stok Habis" dan tidak bisa dipesan. Stok terisi lagi otomatis menjadi "Tersedia".</p>
  </div>

  <div class="space-y-4">
    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">Foto Produk</label>
    <div class="w-full aspect-[4/3] rounded-xl border border-warm bg-stone-100 overflow-hidden flex items-center justify-center">
      <img id="preview" src="{{ $fotoSekarang }}" alt="" class="w-full h-full object-cover {{ $fotoSekarang ? '' : 'hidden' }}">
      <span id="preview-kosong" class="text-xs text-stone-400 {{ $fotoSekarang ? 'hidden' : '' }}">Belum ada foto</span>
    </div>
    <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,.webp" class="block w-full text-xs text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-maroon/10 file:text-maroon hover:file:bg-maroon/20">
    <p class="text-xs text-stone-400">JPG, PNG, atau WEBP, maksimal 2 MB.{{ $edit ? ' Kosongkan jika tidak ingin mengganti foto.' : '' }}</p>

    <button type="submit" class="w-full px-6 py-3 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-sm font-semibold shadow-sm transition">{{ $edit ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
  </div>
</form>
@endsection

@push('scripts')
<script>
  document.getElementById("foto").addEventListener("change", function () {
    const f = this.files && this.files[0];
    if (!f) return;
    const img = document.getElementById("preview");
    img.src = URL.createObjectURL(f);
    img.classList.remove("hidden");
    document.getElementById("preview-kosong").classList.add("hidden");
  });
</script>
@endpush
