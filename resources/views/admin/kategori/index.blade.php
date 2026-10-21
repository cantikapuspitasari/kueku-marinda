@extends('admin.layout', ['aktif' => 'kategori'])

@section('title', 'Kelola Kategori')
@section('judul', 'Kelola Kategori')
@section('subjudul', 'Atur kategori kue. Kategori menjadi tab menu di beranda pelanggan.')

@section('aksi')
  <button type="button" id="btn-tambah" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold rounded-xl shadow-sm transition">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-width="2.5"></path></svg>
    Tambah Kategori
  </button>
@endsection

@php
  $warna = [
    'bg-maroon/10 text-maroon', 'bg-amber-50 text-amber-700', 'bg-orange-50 text-orange-700',
    'bg-rose-50 text-maroon', 'bg-emerald-50 text-emerald-700', 'bg-sky-50 text-sky-700',
  ];
@endphp

@section('isi')
<div class="bg-white rounded-2xl shadow-sm border border-warm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-[#F8F6F2] border-b border-warm text-stone-600 uppercase text-[11px] tracking-wider font-semibold">
          <th class="py-3.5 px-6">Nama Kategori</th>
          <th class="py-3.5 px-6">Jumlah Produk</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-warm/70 text-sm">
        @forelse ($kategori as $i => $k)
          <tr class="hover:bg-[#FAF8F5]/80 transition-colors">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg {{ $warna[$i % count($warna)] }} flex items-center justify-center text-xs font-bold">{{ strtoupper(mb_substr($k->nama_tampil, 0, 1)) }}</span>
                <span class="font-semibold text-stone-800">{{ $k->nama_tampil }}</span>
              </div>
            </td>
            <td class="py-4 px-6 text-stone-600 font-medium">{{ $k->jumlah_produk }} Produk</td>
            <td class="py-4 px-6">
              @if ($k->jumlah_produk > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/80"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif</span>
              @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-600 border border-stone-200"><span class="w-1.5 h-1.5 rounded-full bg-stone-400 mr-1.5"></span>Kosong</span>
              @endif
            </td>
            <td class="py-4 px-6 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-2">
                <button type="button" class="btn-edit px-3.5 py-1.5 text-xs font-medium text-maroon bg-white border border-maroon/30 hover:border-maroon rounded-md hover:bg-maroon/5 transition-colors"
                  data-url="{{ route('admin.kategori.update', $k->id_kategori) }}" data-nama="{{ $k->nama_tampil }}">Edit</button>
                <form method="POST" action="{{ route('admin.kategori.destroy', $k->id_kategori) }}" onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama_tampil) }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-3.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-md hover:bg-rose-100 transition-colors">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada kategori. Klik "Tambah Kategori".</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 bg-[#FAF9F6] border-t border-warm text-xs text-stone-500">Menampilkan {{ $kategori->count() }} kategori. Kategori yang masih punya produk tidak bisa dihapus.</div>
</div>

<!-- Modal tambah / edit -->
<div id="modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
  <form id="form-kat" method="POST" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
    @csrf
    <input type="hidden" name="_method" id="f-method" value="POST">
    <h3 id="modal-judul" class="text-lg font-bold font-serif text-stone-900">Tambah Kategori</h3>
    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700 mt-5 mb-2" for="f-nama">Nama Kategori</label>
    <input id="f-nama" name="nama" type="text" required maxlength="100" placeholder="Contoh: Cake Medium 15cm" class="w-full text-sm border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2.5 px-3">
    <div class="flex justify-end gap-2 mt-6">
      <button type="button" id="batal" class="px-4 py-2 rounded-xl border border-warm text-xs font-semibold text-stone-700 hover:bg-stone-50">Batal</button>
      <button type="submit" class="px-5 py-2 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold">Simpan</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  const modal = document.getElementById("modal");
  const form = document.getElementById("form-kat");
  const bukaModal = (judul, url, method, nama) => {
    document.getElementById("modal-judul").textContent = judul;
    form.action = url;
    document.getElementById("f-method").value = method;
    document.getElementById("f-nama").value = nama;
    modal.classList.remove("hidden"); modal.classList.add("flex");
    document.getElementById("f-nama").focus();
  };
  const tutup = () => { modal.classList.add("hidden"); modal.classList.remove("flex"); };

  document.getElementById("btn-tambah").addEventListener("click", () =>
    bukaModal("Tambah Kategori", "{{ route('admin.kategori.store') }}", "POST", ""));
  document.querySelectorAll(".btn-edit").forEach(b => b.addEventListener("click", () =>
    bukaModal("Edit Kategori", b.dataset.url, "PUT", b.dataset.nama)));
  document.getElementById("batal").addEventListener("click", tutup);
  modal.addEventListener("click", e => { if (e.target === modal) tutup(); });
</script>
@endpush
