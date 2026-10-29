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
            <div><h4 class="font-bold text-stone-900 text-sm">Antar Pesanan</h4><p class="text-xs text-stone-500">Update status jadi "di perjalanan" saat mulai mengantar.</p></div>
          </div>
          @if (!$sedangAntar && !$selesai)
            <form method="POST" action="{{ route('pengiriman.mulaiAntar', $t->id_pesanan) }}">
              @csrf @method('PATCH')
              <button type="submit" class="px-5 py-2 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold shadow-sm transition">Update Status: Di Perjalanan</button>
            </form>
          @endif
        </li>

        <li class="flex flex-wrap items-center justify-between gap-3 py-4">
          <div class="flex items-start gap-3">
            <span class="w-8 h-8 rounded-full border {{ $selesai ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'border-stone-300 text-stone-500' }} flex items-center justify-center text-sm font-semibold shrink-0">{{ $selesai ? '✓' : '3' }}</span>
            <div><h4 class="font-bold text-stone-900 text-sm">Serahkan Pesanan &amp; Terima Pembayaran Cash</h4><p class="text-xs text-stone-500">Update status jadi "selesai" setelah pesanan diterima.</p></div>
          </div>
          @if ($sedangAntar)
            <form method="POST" action="{{ route('pengiriman.tandaiTerkirim', $t->id_pesanan) }}" onsubmit="return confirm('Pesanan sudah diterima pelanggan dan dibayar tunai?')">
              @csrf @method('PATCH')
              <button type="submit" class="px-5 py-2 rounded-xl border border-maroon text-maroon hover:bg-maroon-light text-xs font-semibold transition">Update Status: Selesai</button>
            </form>
          @elseif ($selesai)
            <span class="text-xs font-semibold text-emerald-700">Selesai</span>
          @else
            <span class="text-xs text-stone-400">Aktif setelah "Di Perjalanan"</span>
          @endif
        </li>
      </ol>
    </div>
  @endforeach
</section>
@endif
@endsection

@push('scripts')
<script>
  const tampil = id => {
    document.querySelectorAll(".panel-detail").forEach(p => p.classList.toggle("hidden", p.id !== "panel-" + id));
    const el = document.getElementById("panel-" + id);
    if (el) el.scrollIntoView({ behavior: "smooth", block: "nearest" });
  };
  document.querySelectorAll(".btn-detail").forEach(b => b.addEventListener("click", () => tampil(b.dataset.id)));
  // Buka otomatis tugas pertama yang belum selesai
  const pertama = document.querySelector(".btn-detail");
  if (pertama) {
    const p = document.getElementById("panel-" + pertama.dataset.id);
    if (p) p.classList.remove("hidden");
  }
</script>
@endpush
