@extends('owner.layout', ['aktif' => 'akun'])

@section('title', 'Kelola Akun')
@section('judul', 'Kelola Akun')
@section('subjudul', 'Manajemen hak akses staf internal toko (Admin dan Kurir). Akun pembeli tidak dikelola di sini.')

@section('aksi')
  <button type="button" onclick="document.getElementById('form-tambah-akun').scrollIntoView({behavior:'smooth'})" class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon-dark text-white px-5 py-2.5 rounded-xl font-medium text-sm shadow-md shadow-maroon/20 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-width="2.5"></path></svg>
    Tambah Akun
  </button>
@endsection

@php
  $nAdmin = $akun->where('role', 'ADMIN')->count();
  $nKurir = $akun->where('role', 'KURIR')->count();
  $inisial = fn ($n) => strtoupper(mb_substr(trim($n), 0, 1) . (str_contains(trim($n), ' ') ? mb_substr(trim(strrchr(trim($n), ' ')), 1, 1) : mb_substr(trim($n), 1, 1)));
@endphp

@section('isi')
<section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div class="inline-flex p-1.5 bg-stone-200/60 rounded-xl border border-stone-200 space-x-1">
    @foreach ([['', 'Semua Akun', $akun->count()], ['ADMIN', 'Admin', $nAdmin], ['KURIR', 'Kurir', $nKurir]] as [$k, $nm, $n])
      <button type="button" data-role="{{ $k }}" class="tab-role px-4 py-2 text-xs md:text-sm rounded-lg transition-all {{ $k === '' ? 'font-semibold bg-white text-stone-900 shadow-sm' : 'font-medium text-stone-600 hover:text-stone-900 hover:bg-white/50' }}">
        {{ $nm }} <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold {{ $k === '' ? 'bg-maroon/10 text-maroon' : 'bg-stone-300/60 text-stone-700' }} rounded-full">{{ $n }}</span>
      </button>
    @endforeach
  </div>
  <input id="cari" type="text" placeholder="Cari staf atau email..." class="w-full sm:w-72 text-xs px-4 py-2.5 rounded-xl border border-stone-300 bg-white placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
</section>

<section class="bg-white border border-stone-200/80 rounded-2xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs md:text-sm">
      <thead class="bg-stone-50 border-b border-stone-200/80 text-stone-500 font-semibold tracking-wider uppercase text-[11px]">
        <tr>
          <th class="py-4 px-6">Nama Akun</th>
          <th class="py-4 px-6">Jenis / Peran</th>
          <th class="py-4 px-6 text-center">Status</th>
          <th class="py-4 px-6 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100 text-stone-700">
        @forelse ($akun as $a)
          @php $aktifAkun = $a->status_akun === 'AKTIF'; $admin = $a->role === 'ADMIN'; @endphp
          <tr class="baris hover:bg-stone-50/70 transition-colors {{ $aktifAkun ? '' : 'bg-stone-50/30' }}" data-role="{{ $a->role }}" data-cari="{{ strtolower($a->nama . ' ' . $a->email) }}">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3 {{ $aktifAkun ? '' : 'opacity-80' }}">
                <div class="w-10 h-10 rounded-full {{ !$aktifAkun ? 'bg-stone-200 text-stone-600' : ($admin ? 'bg-maroon/10 text-maroon border border-maroon/15' : 'bg-blue-50 text-blue-700 border border-blue-200') }} font-bold flex items-center justify-center shrink-0 text-xs">{{ $inisial($a->nama) }}</div>
                <div>
                  <p class="font-semibold text-stone-900 text-sm">{{ $a->nama }}</p>
                  <p class="text-xs text-stone-500">{{ $a->email }}{{ $a->no_telepon ? ' • ' . $a->no_telepon : '' }}</p>
                </div>
              </div>
            </td>
            <td class="py-4 px-6">
              <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border {{ !$aktifAkun ? 'bg-stone-100 text-stone-600 border-stone-200' : ($admin ? 'bg-amber-50 text-amber-800 border-amber-200/80' : 'bg-sky-50 text-sky-800 border-sky-200/80') }}">{{ $admin ? 'Admin Operasional' : 'Kurir Toko' }}</span>
            </td>
            <td class="py-4 px-6 text-center">
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border {{ $aktifAkun ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $aktifAkun ? 'bg-emerald-500' : 'bg-stone-400' }}"></span>{{ $aktifAkun ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="py-4 px-6 text-right whitespace-nowrap">
              <div class="inline-flex items-center gap-2">
                <button type="button" class="btn-edit px-3 py-1.5 text-xs font-medium rounded-lg border border-stone-300 text-stone-700 hover:bg-stone-100 transition-colors"
                  data-url="{{ route('owner.akun.update', $a->id_user) }}" data-nama="{{ $a->nama }}" data-telp="{{ $a->no_telepon }}" data-role="{{ $a->role }}" data-email="{{ $a->email }}">Edit</button>
                <form method="POST" action="{{ route($aktifAkun ? 'owner.akun.nonaktifkan' : 'owner.akun.aktifkan', $a->id_user) }}" @if ($aktifAkun) onsubmit="return confirm('Nonaktifkan akun {{ addslashes($a->nama) }}? Akun ini tidak bisa login lagi.')" @endif>
                  @csrf @method('PATCH')
                  <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors {{ $aktifAkun ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50' }}">{{ $aktifAkun ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada akun staf.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<section class="bg-white border border-stone-200 rounded-2xl shadow-sm p-6 md:p-8" id="form-tambah-akun">
  <div class="mb-5 border-b border-stone-100 pb-4">
    <h2 class="text-lg font-serif font-semibold text-stone-900">Tambah Akun Baru</h2>
    <p class="text-xs md:text-sm text-stone-500 mt-1">Lengkapi data staf di bawah ini untuk memberikan hak akses ke portal operasional.</p>
  </div>
  <form method="POST" action="{{ route('owner.akun.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
      <div>
        <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-2" for="input-nama">Nama Lengkap Staf</label>
        <input id="input-nama" name="nama" value="{{ old('nama') }}" required maxlength="100" placeholder="misal: Siti Nurhaliza" type="text" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-2" for="input-email">Email Akun (Login)</label>
        <input id="input-email" name="email" value="{{ old('email') }}" required maxlength="120" placeholder="nama@kuekumarinda.com" type="email" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-2" for="input-phone">Nomor Telepon / WhatsApp</label>
        <input id="input-phone" name="no_telepon" value="{{ old('no_telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx" type="text" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-2" for="input-pass">Kata Sandi Sementara</label>
        <input id="input-pass" name="password" required minlength="8" placeholder="Minimal 8 karakter" type="password" class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:outline-none focus:ring-2 focus:ring-maroon/20 focus:border-maroon">
      </div>
    </div>
    <div>
      <span class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-2.5">Pilih Peran Akun</span>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg">
        <label class="flex items-center p-3.5 rounded-xl border-2 border-stone-200 cursor-pointer hover:border-maroon/50 has-[:checked]:border-maroon has-[:checked]:bg-maroon/5 transition-all">
          <input type="radio" name="role" value="ADMIN" {{ old('role', 'ADMIN') === 'ADMIN' ? 'checked' : '' }} class="text-maroon focus:ring-maroon border-stone-300 h-4 w-4">
          <div class="ml-3"><span class="block text-sm font-semibold text-stone-800">Jenis: Admin</span><span class="block text-xs text-stone-500">Kelola menu, pesanan, dan konfirmasi</span></div>
        </label>
        <label class="flex items-center p-3.5 rounded-xl border-2 border-stone-200 cursor-pointer hover:border-maroon/50 has-[:checked]:border-maroon has-[:checked]:bg-maroon/5 transition-all">
          <input type="radio" name="role" value="KURIR" {{ old('role') === 'KURIR' ? 'checked' : '' }} class="text-maroon focus:ring-maroon border-stone-300 h-4 w-4">
          <div class="ml-3"><span class="block text-sm font-semibold text-stone-800">Jenis: Kurir</span><span class="block text-xs text-stone-500">Antar kue dan catat pembayaran</span></div>
        </label>
      </div>
    </div>
    <div class="flex items-center gap-3 pt-4 border-t border-stone-100">
      <button type="submit" class="inline-flex items-center gap-2 bg-maroon hover:bg-maroon-dark text-white font-medium text-sm px-6 py-2.5 rounded-xl shadow-md shadow-maroon/20 transition">Simpan Akun</button>
      <button type="reset" class="px-5 py-2.5 rounded-xl border border-stone-300 text-stone-600 hover:bg-stone-100 text-sm font-medium transition">Batal</button>
    </div>
  </form>
</section>

<!-- Modal edit -->
<div id="modal" class="hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4">
  <form id="form-edit" method="POST" class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
    @csrf @method('PUT')
    <h3 class="text-lg font-bold font-serif text-stone-900">Edit Akun</h3>
    <p id="e-email" class="text-xs text-stone-500 -mt-2"></p>
    <div>
      <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-1.5" for="e-nama">Nama</label>
      <input id="e-nama" name="nama" required maxlength="100" type="text" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-maroon focus:border-maroon">
    </div>
    <div>
      <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-1.5" for="e-telp">No. Telepon</label>
      <input id="e-telp" name="no_telepon" maxlength="20" type="text" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-maroon focus:border-maroon">
    </div>
    <div>
      <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-1.5" for="e-role">Peran</label>
      <select id="e-role" name="role" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-maroon focus:border-maroon bg-white">
        <option value="ADMIN">Admin</option>
        <option value="KURIR">Kurir</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-semibold uppercase text-stone-600 tracking-wider mb-1.5" for="e-pass">Reset Kata Sandi (opsional)</label>
      <input id="e-pass" name="password" minlength="8" type="password" placeholder="Kosongkan jika tidak diganti" class="w-full px-3 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-maroon focus:border-maroon">
    </div>
    <div class="flex justify-end gap-2 pt-2">
      <button type="button" id="batal" class="px-4 py-2 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50">Batal</button>
      <button type="submit" class="px-5 py-2 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold">Simpan</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  // Filter peran + cari
  let roleAktif = "";
  const baris = document.querySelectorAll(".baris");
  const cari = document.getElementById("cari");
  function terapkan() {
    const q = cari.value.trim().toLowerCase();
    baris.forEach(r => r.classList.toggle("hidden", !((!roleAktif || r.dataset.role === roleAktif) && (!q || r.dataset.cari.includes(q)))));
  }
  document.querySelectorAll(".tab-role").forEach(t => t.addEventListener("click", () => {
    roleAktif = t.dataset.role;
    document.querySelectorAll(".tab-role").forEach(x => {
      const on = x === t;
      x.classList.toggle("bg-white", on); x.classList.toggle("text-stone-900", on); x.classList.toggle("shadow-sm", on); x.classList.toggle("font-semibold", on);
      x.classList.toggle("text-stone-600", !on); x.classList.toggle("font-medium", !on);
    });
    terapkan();
  }));
  cari.addEventListener("input", terapkan);

  // Modal edit
  const modal = document.getElementById("modal");
  const tutup = () => { modal.classList.add("hidden"); modal.classList.remove("flex"); };
  document.querySelectorAll(".btn-edit").forEach(b => b.addEventListener("click", () => {
    document.getElementById("form-edit").action = b.dataset.url;
    document.getElementById("e-nama").value = b.dataset.nama;
    document.getElementById("e-telp").value = b.dataset.telp || "";
    document.getElementById("e-role").value = b.dataset.role;
    document.getElementById("e-pass").value = "";
    document.getElementById("e-email").textContent = b.dataset.email;
    modal.classList.remove("hidden"); modal.classList.add("flex");
  }));
  document.getElementById("batal").addEventListener("click", tutup);
  modal.addEventListener("click", e => { if (e.target === modal) tutup(); });
</script>
@endpush
