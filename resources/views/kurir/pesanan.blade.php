@extends('kurir.layout', ['aktif' => 'pesanan'])

@section('title', 'Pesanan Diantar')
@section('judul', 'Pesanan yang Diantar')
@section('subjudul', 'Pilih pesanan lalu ikuti langkah pengantaran sampai pembayaran cash diterima.')

@php
  $statusMap = [
    'MENUNGGU'      => ['Menunggu Diantar', 'bg-amber-100 text-amber-800 border-amber-200'],
    'DIAMBIL_KURIR' => ['Menunggu Diantar', 'bg-amber-100 text-amber-800 border-amber-200'],
    'DIANTAR'       => ['Di Perjalanan',    'bg-indigo-100 text-indigo-800 border-indigo-200'],
    'TERKIRIM'      => ['Selesai',          'bg-emerald-100 text-emerald-800 border-emerald-200'],
  ];
  $nomorWa = fn ($tel) => $tel ? preg_replace('/^0/', '62', preg_replace('/\D/', '', $tel)) : null;
@endphp

@section('isi')
<!-- Daftar pesanan -->
<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
      <thead class="bg-[#F6F3EE] text-xs uppercase tracking-wider text-stone-500">
        <tr>
          <th class="py-3.5 px-6 font-semibold">Pelanggan / Alamat</th>
          <th class="py-3.5 px-6 font-semibold">Jadwal Antar</th>
          <th class="py-3.5 px-6 font-semibold text-center">Status</th>
          <th class="py-3.5 px-6 font-semibold text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($tugas as $t)
          @php $st = $statusMap[$t->status_pengiriman] ?? [$t->status_pengiriman, 'bg-stone-100 text-stone-700 border-stone-200']; @endphp
          <tr class="hover:bg-[#FBF9F5] transition">
            <td class="py-4 px-6">
              <span class="block font-semibold text-stone-900">{{ $t->pelanggan ?? 'Pelanggan' }}</span>
              <span class="block text-xs text-stone-500 mt-0.5 max-w-xs truncate">{{ $t->alamat_lengkap ?? 'Alamat belum tercatat' }}</span>
              <span class="block text-[11px] font-mono text-stone-400 mt-0.5">{{ $t->kode_pesanan }}</span>
            </td>
            <td class="py-4 px-6 text-stone-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($t->tanggal_ambil)->translatedFormat('d M Y') }}</td>
            <td class="py-4 px-6 text-center"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $st[1] }}">{{ $st[0] }}</span></td>
            <td class="py-4 px-6 text-center">
              <button type="button" class="btn-detail px-4 py-1.5 rounded-lg border border-warm text-xs font-semibold text-stone-700 bg-white hover:border-maroon hover:text-maroon transition" data-id="{{ $t->id_pesanan }}">Detail</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="py-12 px-6 text-center text-sm text-stone-500">Belum ada tugas antar. Pesanan muncul di sini setelah admin menyerahkannya kepada Anda.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<!-- Detail pengiriman -->
@if ($tugas->count())
<section class="space-y-4">
  <h3 class="text-xl font-bold font-serif text-stone-800">Detail Pengiriman</h3>

  @foreach ($tugas as $t)
    @php
      $wa = $nomorWa($t->telepon);
      $sedangAntar = $t->status_pengiriman === 'DIANTAR';
      $selesai = $t->status_pengiriman === 'TERKIRIM';
      $sudahBayar = ! is_null($t->id_pembayaran);
    @endphp
    <div id="panel-{{ $t->id_pesanan }}" class="panel-detail hidden bg-white rounded-2xl border border-warm shadow-sm p-6 space-y-5">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
          <span class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold">Nama pelanggan</span>
          <p class="text-lg font-bold text-stone-900">{{ $t->pelanggan ?? 'Pelanggan' }}</p>
          <span class="text-[11px] uppercase tracking-wider text-stone-400 font-semibold mt-2 block">Alamat &amp; no. HP pelanggan</span>
          <p class="text-sm text-stone-700">{{ $t->alamat_lengkap ?? 'Alamat belum tercatat' }}</p>
          <p class="text-sm text-stone-500">{{ $t->telepon ?: 'No. HP belum tercatat' }}</p>
        </div>
        <div class="text-right">
          <span class="text-[11px] text-stone-400 block">Tagih tunai (COD)</span>
          <span class="text-xl font-bold text-maroon">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span>
          <span class="block text-[11px] font-mono text-stone-400 mt-1">{{ $t->kode_pesanan }}</span>
        </div>
      </div>

      <ul class="rounded-xl border border-stone-100 divide-y divide-stone-100 text-sm">
        @foreach ($item[$t->id_pesanan] ?? [] as $it)
          <li class="flex justify-between px-4 py-2.5"><span class="text-stone-700">{{ $it->nama_produk }}</span><span class="font-semibold text-stone-900">{{ $it->jumlah }}x</span></li>
        @endforeach
      </ul>

      <ol class="divide-y divide-stone-100">
        <li class="flex flex-wrap items-center justify-between gap-3 py-4">
          <div class="flex items-start gap-3">
            <span class="w-8 h-8 rounded-full border border-stone-300 text-stone-500 flex items-center justify-center text-sm font-semibold shrink-0">1</span>
            <div><h4 class="font-bold text-stone-900 text-sm">Hubungi Pelanggan via WhatsApp</h4><p class="text-xs text-stone-500">Opsional, jika perlu konfirmasi alamat sebelum berangkat.</p></div>
          </div>
          @if ($wa)
            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2 rounded-xl border border-warm text-xs font-semibold text-stone-700 hover:border-emerald-500 hover:text-emerald-700 transition">Hubungi via WA</a>
          @else
            <span class="text-xs text-stone-400">No. HP belum ada</span>
          @endif
        </li>

        <li class="flex flex-wrap items-center justify-between gap-3 py-4">
          <div class="flex items-start gap-3">
            <span class="w-8 h-8 rounded-full border {{ $sedangAntar || $selesai ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'border-stone-300 text-stone-500' }} flex items-center justify-center text-sm font-semibold shrink-0">{{ $sedangAntar || $selesai ? '✓' : '2' }}</span>
            <div><h4 class="font-bold text-stone-900 text-sm">Antar Pesanan</h4><p class="text-xs text-stone-500">Admin menandai "di perjalanan" setelah Anda memberi kabar akan berangkat.</p></div>
          </div>
          @if ($sedangAntar || $selesai)
            <span class="text-xs font-semibold text-emerald-700">Sudah ditandai admin</span>
          @else
            <span class="text-xs text-stone-500 max-w-[14rem] text-right">Kabari admin bahwa Anda siap berangkat. Admin yang menandai "Kurir berangkat mengantar".</span>
          @endif
        </li>

        <li class="py-4 space-y-4">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-start gap-3">
              <span class="w-8 h-8 rounded-full border {{ $selesai ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'border-stone-300 text-stone-500' }} flex items-center justify-center text-sm font-semibold shrink-0">{{ $selesai ? '✓' : '3' }}</span>
              <div><h4 class="font-bold text-stone-900 text-sm">Serahkan Pesanan &amp; Terima Pembayaran Cash</h4><p class="text-xs text-stone-500">Input uang yang diberikan pelanggan, lalu update status jadi "selesai" setelah pesanan diterima.</p></div>
            </div>
            @if ($selesai)
              <span class="text-xs font-semibold text-emerald-700">Selesai</span>
            @elseif ($sedangAntar && $sudahBayar)
              <form method="POST" action="{{ route('pengiriman.tandaiTerkirim', $t->id_pesanan) }}" onsubmit="return confirm('Pesanan sudah diterima pelanggan dan dibayar tunai?')">
                @csrf @method('PATCH')
                <button type="submit" class="px-5 py-2 rounded-xl border border-maroon text-maroon hover:bg-maroon-light text-xs font-semibold transition">Update Status: Selesai</button>
              </form>
            @elseif ($sedangAntar)
              <span class="text-xs text-stone-500">Catat pembayaran dulu</span>
            @else
              <span class="text-xs text-stone-400">Aktif setelah "Di Perjalanan"</span>
            @endif
          </div>

          @if ($sudahBayar)
            {{-- Pembayaran sudah tercatat --}}
            <div class="rounded-xl bg-[#FBF9F5] border border-warm px-4 py-3 text-sm space-y-1">
              <div class="flex justify-between"><span class="text-stone-500">Total pembayaran</span><span class="font-semibold text-stone-900">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span></div>
              <div class="flex justify-between"><span class="text-stone-500">Uang diterima</span><span class="font-semibold text-stone-900">Rp {{ number_format($t->uang_diterima, 0, ',', '.') }}</span></div>
              <div class="flex justify-between"><span class="text-stone-500">Kembalian</span><span class="font-bold text-maroon">Rp {{ number_format($t->kembalian, 0, ',', '.') }}</span></div>
              <a href="{{ route('pesanan.struk', $t->id_pesanan) }}" target="_blank" class="inline-block pt-1 text-xs font-semibold text-maroon hover:underline">Lihat Struk</a>
            </div>
          @elseif ($sedangAntar)
            {{-- Kurir menginput uang yang diberikan pelanggan --}}
            <form method="POST" action="{{ route('pembayaran.store', $t->id_pesanan) }}" data-bayar data-total="{{ (int) round($t->total_harga) }}" autocomplete="off" class="rounded-xl border border-warm p-4 space-y-3">
              @csrf
              <input type="hidden" name="tempat_pembayaran" value="ALAMAT_PEMBELI">
              <input type="hidden" name="uang_diterima" value="{{ old('uang_diterima') }}" data-bayar-nilai>

              <div>
                <label for="uang-{{ $t->id_pesanan }}" class="block text-xs font-semibold text-stone-600 mb-1">Uang diterima dari pelanggan</label>
                <input type="text" id="uang-{{ $t->id_pesanan }}" inputmode="numeric" placeholder="Contoh: 100000" value="{{ old('uang_diterima') }}" data-bayar-input class="w-full rounded-lg border-stone-300 text-sm focus:border-maroon focus:ring-maroon">
              </div>

              <div class="rounded-lg bg-[#FBF9F5] border border-warm px-4 py-3 text-sm space-y-1">
                <div class="flex justify-between"><span class="text-stone-500">Total pembayaran</span><span class="font-semibold text-stone-900">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-stone-500">Uang diterima</span><span class="font-semibold text-stone-900" data-bayar-diterima>Rp0</span></div>
                <div class="flex justify-between"><span class="text-stone-500">Kembalian</span><span class="font-bold text-maroon" data-bayar-kembalian>Rp0</span></div>
              </div>

              <p class="text-xs font-semibold min-h-[1rem]" data-bayar-pesan></p>

              <button type="submit" data-bayar-submit disabled class="px-5 py-2 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed">Simpan Pembayaran</button>
            </form>
          @endif
        </li>
      </ol>
    </div>
  @endforeach
</section>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/kembalian.js') }}"></script>
<script>
  const tampil = id => {
    try { sessionStorage.setItem("kurirPanelDibuka", id); } catch (e) {}
    document.querySelectorAll(".panel-detail").forEach(p => p.classList.toggle("hidden", p.id !== "panel-" + id));
    const el = document.getElementById("panel-" + id);
    if (el) el.scrollIntoView({ behavior: "smooth", block: "nearest" });
  };
  document.querySelectorAll(".btn-detail").forEach(b => b.addEventListener("click", () => tampil(b.dataset.id)));
  // Buka otomatis tugas yang terakhir dibuka (mis. setelah menyimpan pembayaran); kalau tidak ada, tugas pertama
  let idBuka = null;
  try { idBuka = sessionStorage.getItem("kurirPanelDibuka"); } catch (e) {}
  const pertama = document.querySelector(".btn-detail");
  const panelBuka = idBuka && document.getElementById("panel-" + idBuka);
  if (panelBuka) {
    panelBuka.classList.remove("hidden");
  } else if (pertama) {
    const p = document.getElementById("panel-" + pertama.dataset.id);
    if (p) p.classList.remove("hidden");
  }
</script>
@endpush

@push('head')
<style>
  .bayar-salah { color: #dc2626; }
  .bayar-info  { color: #57534e; }
</style>
@endpush
