@extends('owner.layout', ['aktif' => 'monitoring'])

@section('title', 'Monitoring Toko')
@section('judul', 'Monitoring Toko')
@section('subjudul', 'Pantau volume pesanan dan status pengerjaan. Halaman ini hanya untuk memantau; status diubah oleh Admin.')

@section('aksi')
  <a href="{{ request()->fullUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-maroon bg-rose-50 border border-rose-100 hover:bg-rose-100 transition">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
    Segarkan
  </a>
@endsection

@php
  $statusMap = [
    'PENDING'      => ['Menunggu Verifikasi', 'bg-amber-100/80 text-amber-800 border-amber-200'],
    'DIKONFIRMASI' => ['Terverifikasi',       'bg-indigo-100 text-indigo-800 border-indigo-200'],
    'DIPROSES'     => ['Diproses',            'bg-blue-100 text-blue-800 border-blue-200'],
    'SELESAI'      => ['Selesai',             'bg-emerald-100 text-emerald-800 border-emerald-200'],
    'DIBATALKAN'   => ['Dibatalkan',          'bg-rose-100 text-rose-800 border-rose-200'],
  ];
  $tabs = [['', 'Semua', array_sum($hitung->all())]];
  foreach ($statusMap as $k => $v) { $tabs[] = [$k, $v[0], $hitung[$k] ?? 0]; }
@endphp

@section('isi')
<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white p-6 rounded-2xl border border-warm shadow-sm">
    <p class="text-xs uppercase font-bold tracking-wider text-stone-400">Pesanan Hari Ini</p>
    <div class="flex items-baseline gap-2 mt-2"><span class="text-3xl font-extrabold text-stone-900">{{ $stat['hari_ini'] }}</span><span class="text-base font-semibold text-stone-600">Pesanan</span></div>
    <p class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500">Total seluruh pesanan: <span class="font-semibold text-stone-700">{{ $stat['total'] }}</span></p>
  </div>
  <div class="bg-white p-6 rounded-2xl border border-warm shadow-sm">
    <p class="text-xs uppercase font-bold tracking-wider text-stone-400">Sedang Diproses</p>
    <div class="flex items-baseline gap-2 mt-2"><span class="text-3xl font-extrabold text-maroon">{{ $stat['proses'] }}</span><span class="text-base font-semibold text-stone-600">Pesanan</span></div>
    <p class="mt-4 pt-3 border-t border-stone-100 text-xs text-amber-700 font-medium flex items-center"><span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>Menunggu verifikasi, dikonfirmasi, atau di dapur</p>
  </div>
  <div class="bg-white p-6 rounded-2xl border border-warm shadow-sm">
    <p class="text-xs uppercase font-bold tracking-wider text-stone-400">Selesai Hari Ini</p>
    <div class="flex items-baseline gap-2 mt-2"><span class="text-3xl font-extrabold text-stone-900">{{ $stat['selesai_hari_ini'] }}</span><span class="text-base font-semibold text-stone-600">Pesanan</span></div>
    <p class="mt-4 pt-3 border-t border-stone-100 text-xs text-stone-500"><span class="text-emerald-600 font-semibold">{{ $stat['persen_selesai'] }}%</span> dari pesanan hari ini sudah selesai</p>
  </div>
</section>

<div class="flex items-center gap-2 overflow-x-auto pb-1">
  @foreach ($tabs as [$kode, $label, $jml])
    <a href="{{ route('owner.monitoring', $kode ? ['status' => $kode] : []) }}" class="text-xs px-4 py-2 rounded-xl whitespace-nowrap transition {{ ($status ?? '') === $kode ? 'bg-maroon text-white font-semibold shadow-sm' : 'bg-white text-stone-700 border border-warm font-medium hover:bg-[#F6F3EE]' }}">{{ $label }} ({{ $jml }})</a>
  @endforeach
</div>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="p-6 border-b border-stone-100 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h3 class="text-xl font-bold font-serif text-stone-900">Pesanan Terbaru</h3>
      <p class="text-xs text-stone-500 mt-1">Daftar antrean pesanan dari pembeli.</p>
    </div>
    <label class="flex items-center gap-2 text-xs text-stone-500 cursor-pointer">
      <input id="auto" type="checkbox" class="rounded border-stone-300 text-maroon focus:ring-maroon">
      Muat ulang otomatis tiap 30 detik
    </label>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-stone-50/75 border-b border-stone-100 text-[11px] font-bold text-stone-500 uppercase tracking-wider">
          <th class="py-3.5 px-6">Pelanggan / Produk</th>
          <th class="py-3.5 px-6">Tanggal &amp; Jam</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100 text-sm">
        @forelse ($pesanan as $p)
          @php
            $nama = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
            $first = $p->detailPesanan->first();
            $extra = $p->detailPesanan->count() - 1;
            $foto = ($first && $first->produk && $first->produk->foto_produk) ? asset('images/produk/' . $first->produk->foto_produk) : '';
            $st = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700 border-stone-200'];
            $tgl = \Carbon\Carbon::parse($p->tanggal_pesan);
          @endphp
          <tr class="hover:bg-amber-50/20 transition-colors">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200 overflow-hidden flex-shrink-0">@if ($foto)<img src="{{ $foto }}" alt="" class="w-full h-full object-cover">@endif</div>
                <div class="min-w-0">
                  <div class="font-bold text-stone-900 leading-tight">{{ $nama }}</div>
                  <div class="text-xs text-stone-500 mt-1 truncate">{{ $first->produk->nama_produk ?? '-' }}{{ $extra > 0 ? ' + ' . $extra . ' produk lainnya' : '' }} <span class="font-mono text-stone-400">• {{ $p->kode_pesanan }}</span></div>
                </div>
              </div>
            </td>
            <td class="py-4 px-6 whitespace-nowrap text-stone-600 font-medium text-xs">
              <div>{{ $tgl->format('d M Y') }}</div>
              <div class="text-stone-400 font-mono text-[11px] mt-0.5">{{ $tgl->format('H:i') }} WIB</div>
            </td>
            <td class="py-4 px-6 whitespace-nowrap"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $st[1] }}">{{ $st[0] }}</span></td>
            <td class="py-4 px-6 whitespace-nowrap text-center">
              <button type="button" class="btn-detail px-4 py-1.5 text-xs font-semibold text-stone-700 bg-white border border-stone-300 rounded-lg shadow-sm hover:bg-stone-50 hover:border-maroon hover:text-maroon transition" data-id="{{ $p->id_pesanan }}">Lihat Detail</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada pesanan{{ $status ? ' dengan status ini' : '' }}.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-6 py-4 bg-stone-50/60 border-t border-stone-100 flex flex-wrap items-center justify-between gap-3 text-xs text-stone-500">
    <div>Menampilkan {{ $pesanan->count() }} dari {{ $pesanan->total() }} pesanan</div>
    <div>{{ $pesanan->links() }}</div>
  </div>
</section>

@foreach ($pesanan as $p)
  @php
    $nama = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
    $st = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700 border-stone-200'];
    $delivery = $p->jenis_pengambilan === 'DELIVERY';
    $al = $p->id_alamat ? ($alamat[$p->id_alamat] ?? null) : null;
    $alamatTeks = $delivery ? ($al ? trim(($al->alamat_lengkap ?? '') . ', ' . ($al->kota ?? '') . ' ' . ($al->kode_pos ?? ''), ' ,') : 'Alamat belum tercatat') : 'Ambil di toko';
    $subtotal = $p->detailPesanan->sum('subtotal');
    $ongkir = max(0, $p->total_harga - $subtotal);
  @endphp
  <div id="detail-{{ $p->id_pesanan }}" class="modal-detail hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl my-8">
      <div class="px-6 py-5 border-b border-warm flex items-start justify-between gap-4">
        <div>
          <span class="text-[11px] font-bold text-maroon uppercase tracking-wider">Detail Pesanan</span>
          <h3 class="text-xl font-bold font-serif text-stone-900 mt-0.5">{{ $p->kode_pesanan }}</h3>
        </div>
        <div class="flex items-center gap-3">
          <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $st[1] }}">{{ $st[0] }}</span>
          <button type="button" class="tutup text-stone-400 hover:text-stone-700 text-xl leading-none" aria-label="Tutup">&times;</button>
        </div>
      </div>
      <div class="p-6 space-y-5">
        <div class="grid sm:grid-cols-2 gap-4 p-4 rounded-xl bg-[#FBF9F5] border border-warm">
          <div>
            <span class="text-[11px] font-semibold text-stone-400 uppercase">Pelanggan</span>
            <p class="text-sm font-bold text-stone-900 mt-0.5">{{ $nama }}</p>
            <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</p>
          </div>
          <div>
            <span class="text-[11px] font-semibold text-stone-400 uppercase">{{ $delivery ? 'Diantar kurir ke' : 'Pengambilan' }}</span>
            <p class="text-sm font-medium text-stone-700 mt-0.5">{{ $alamatTeks }}</p>
          </div>
        </div>
        <ul class="divide-y divide-warm/60 border border-warm rounded-xl overflow-hidden">
          @foreach ($p->detailPesanan as $d)
            <li class="flex items-center gap-3 px-4 py-3">
              <div class="w-12 h-12 rounded-lg bg-stone-100 overflow-hidden flex-shrink-0 border border-stone-200">@if ($d->produk && $d->produk->foto_produk)<img src="{{ asset('images/produk/' . $d->produk->foto_produk) }}" alt="" class="w-full h-full object-cover">@endif</div>
              <div class="flex-1 min-w-0"><p class="text-sm font-semibold text-stone-800 truncate">{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</p><p class="text-xs text-stone-500">{{ $d->jumlah }} x</p></div>
              <span class="text-sm font-semibold text-stone-900">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </li>
          @endforeach
          @if ($ongkir > 0)<li class="flex justify-between px-4 py-2.5 text-sm text-stone-600"><span>Ongkos kirim</span><span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span></li>@endif
          <li class="flex justify-between px-4 py-3 bg-[#FBF9F5] text-sm font-bold text-stone-900"><span>Total</span><span class="text-maroon">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span></li>
        </ul>
      </div>
    </div>
  </div>
@endforeach
@endsection

@push('scripts')
<script>
  const buka = id => { const m = document.getElementById("detail-" + id); if (m) { m.classList.remove("hidden"); m.classList.add("flex"); } };
  const tutupSemua = () => document.querySelectorAll(".modal-detail").forEach(m => { m.classList.add("hidden"); m.classList.remove("flex"); });
  document.querySelectorAll(".btn-detail").forEach(b => b.addEventListener("click", () => buka(b.dataset.id)));
  document.querySelectorAll(".tutup").forEach(b => b.addEventListener("click", tutupSemua));
  document.querySelectorAll(".modal-detail").forEach(m => m.addEventListener("click", e => { if (e.target === m) tutupSemua(); }));
  document.addEventListener("keydown", e => { if (e.key === "Escape") tutupSemua(); });

  // Muat ulang otomatis (pilihan disimpan di browser)
  const auto = document.getElementById("auto");
  try { auto.checked = localStorage.getItem("kueku_auto_refresh") === "1"; } catch (e) {}
  auto.addEventListener("change", () => { try { localStorage.setItem("kueku_auto_refresh", auto.checked ? "1" : "0"); } catch (e) {} });
  setInterval(() => { if (auto.checked && !document.querySelector(".modal-detail.flex")) location.reload(); }, 30000);
</script>
@endpush
