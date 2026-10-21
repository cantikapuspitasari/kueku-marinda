@extends('admin.layout', ['aktif' => 'pesanan'])

@section('title', 'Pesanan Masuk')
@section('judul', 'Pesanan Masuk')
@section('subjudul', 'Kelola dan proses pesanan pelanggan. Data diambil langsung dari pesanan yang dibuat di toko.')

@php
  $statusMap = [
    'PENDING'      => ['Menunggu Verifikasi', 'bg-amber-100 text-amber-800 border-amber-200'],
    'DIKONFIRMASI' => ['Terverifikasi',       'bg-indigo-100 text-indigo-800 border-indigo-200'],
    'DIPROSES'     => ['Diproses',            'bg-blue-100 text-blue-800 border-blue-200'],
    'SELESAI'      => ['Selesai',             'bg-emerald-100 text-emerald-800 border-emerald-200'],
    'DIBATALKAN'   => ['Dibatalkan',          'bg-rose-100 text-rose-800 border-rose-200'],
  ];
  $hitung = fn ($s) => $pesanan->where('status_pesanan', $s)->count();
  $tabs = [['', 'Semua', $pesanan->count()], ['PENDING', 'Menunggu', $hitung('PENDING')], ['DIKONFIRMASI', 'Terverifikasi', $hitung('DIKONFIRMASI')],
           ['DIPROSES', 'Diproses', $hitung('DIPROSES')], ['SELESAI', 'Selesai', $hitung('SELESAI')], ['DIBATALKAN', 'Dibatalkan', $hitung('DIBATALKAN')]];
@endphp

@section('isi')
<div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
  @foreach ($tabs as [$kode, $label, $jml])
    <button type="button" data-st="{{ $kode }}" class="tab-st text-xs px-4 py-2 rounded-xl whitespace-nowrap transition {{ $kode === '' ? 'bg-maroon text-white font-semibold shadow-sm' : 'bg-white text-stone-700 border border-warm font-medium hover:bg-[#F6F3EE]' }}">{{ $label }} ({{ $jml }})</button>
  @endforeach
</div>

<section class="bg-white rounded-2xl border border-warm shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
      <thead>
        <tr class="bg-[#F8F6F2] border-b border-warm text-[11px] font-semibold text-stone-500 uppercase tracking-wider">
          <th class="py-3.5 px-6">Pelanggan / Produk</th>
          <th class="py-3.5 px-6">No. Pesanan</th>
          <th class="py-3.5 px-6">Total</th>
          <th class="py-3.5 px-6 text-center">Status</th>
          <th class="py-3.5 px-6 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-warm/60">
        @forelse ($pesanan as $p)
          @php
            $nama  = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
            $first = $p->detailPesanan->first();
            $extra = $p->detailPesanan->count() - 1;
            $fotoFirst = ($first && $first->produk && $first->produk->foto_produk) ? asset('images/produk/' . $first->produk->foto_produk) : '';
            $st = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700 border-stone-200'];
          @endphp
          <tr class="baris hover:bg-amber-50/30 transition-colors" data-st="{{ $p->status_pesanan }}">
            <td class="py-4 px-6">
              <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200 overflow-hidden flex-shrink-0">
                  @if ($fotoFirst)<img src="{{ $fotoFirst }}" alt="" class="w-full h-full object-cover">@endif
                </div>
                <div class="min-w-0">
                  <span class="font-bold text-stone-900 block text-sm">{{ $nama }}</span>
                  <span class="text-xs text-stone-500 block mt-0.5 truncate">{{ $first->produk->nama_produk ?? '-' }}{{ $extra > 0 ? ' + ' . $extra . ' produk lainnya' : '' }}</span>
                </div>
              </div>
            </td>
            <td class="py-4 px-6">
              <span class="text-xs font-mono font-semibold text-stone-700">{{ $p->kode_pesanan }}</span>
              <span class="block text-[11px] text-stone-400 mt-0.5">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y, H:i') }}</span>
            </td>
            <td class="py-4 px-6 font-semibold text-stone-900 whitespace-nowrap">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
            <td class="py-4 px-6 text-center"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold border {{ $st[1] }}">{{ $st[0] }}</span></td>
            <td class="py-4 px-6 text-center">
              <button type="button" class="btn-detail px-4 py-1.5 rounded-lg border border-warm text-xs font-semibold text-stone-700 bg-white hover:border-maroon hover:text-maroon transition" data-id="{{ $p->id_pesanan }}">Detail</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="py-10 px-6 text-center text-sm text-stone-500">Belum ada pesanan masuk.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<!-- Modal detail per pesanan -->
@foreach ($pesanan as $p)
  @php
    $nama = $p->pembeli->nama ?? $p->pembeli->name ?? 'Pelanggan';
    $st = $statusMap[$p->status_pesanan] ?? [$p->status_pesanan, 'bg-stone-100 text-stone-700 border-stone-200'];
    $delivery = $p->jenis_pengambilan === 'DELIVERY';
    $al = $p->id_alamat ? ($alamat[$p->id_alamat] ?? null) : null;
    $alamatTeks = $delivery ? ($al ? trim(($al->alamat_lengkap ?? '') . ', ' . ($al->kota ?? '') . ' ' . ($al->kode_pos ?? ''), ' ,') : 'Alamat belum tercatat') : 'Ambil di toko';
    $subtotal = $p->detailPesanan->sum('subtotal');
    $ongkir = max(0, $p->total_harga - $subtotal);
    $urlStatus = route('pesanan.updateStatus', $p->id_pesanan);
  @endphp
  <div id="detail-{{ $p->id_pesanan }}" class="modal-detail hidden fixed inset-0 z-50 bg-black/40 items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl my-8">
      <div class="px-6 py-5 border-b border-warm flex items-start justify-between gap-4">
        <div>
          <span class="text-[11px] font-bold text-maroon uppercase tracking-wider">Detail &amp; Proses Pesanan</span>
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
              <div class="w-12 h-12 rounded-lg bg-stone-100 overflow-hidden flex-shrink-0 border border-stone-200">
                @if ($d->produk && $d->produk->foto_produk)<img src="{{ asset('images/produk/' . $d->produk->foto_produk) }}" alt="" class="w-full h-full object-cover">@endif
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-stone-800 truncate">{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</p>
                <p class="text-xs text-stone-500">{{ $d->jumlah }} x</p>
              </div>
              <span class="text-sm font-semibold text-stone-900">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </li>
          @endforeach
          @if ($ongkir > 0)
            <li class="flex justify-between px-4 py-2.5 text-sm text-stone-600"><span>Ongkos kirim</span><span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span></li>
          @endif
          <li class="flex justify-between px-4 py-3 bg-[#FBF9F5] text-sm font-bold text-stone-900"><span>Total</span><span class="text-maroon">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span></li>
        </ul>

        @if ($p->status_pesanan === 'PENDING')
          <form method="POST" action="{{ $urlStatus }}" class="p-4 rounded-xl border border-warm flex flex-wrap items-center justify-between gap-3">
            @csrf @method('PATCH')
            <input type="hidden" name="status_pesanan" value="DIKONFIRMASI">
            <div>
              <h4 class="text-sm font-bold text-stone-900">Verifikasi Pesanan</h4>
              <p class="text-xs text-stone-500">Cek data pesanan sebelum diproses ke dapur.</p>
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-maroon hover:bg-maroon-dark text-white text-xs font-semibold shadow-sm transition">Verifikasi</button>
          </form>
        @endif

        @php
          $alur = ['PENDING' => ['DIKONFIRMASI','DIBATALKAN'], 'DIKONFIRMASI' => ['DIPROSES','DIBATALKAN'], 'DIPROSES' => ['SELESAI','DIBATALKAN'], 'SELESAI' => [], 'DIBATALKAN' => []];
          $berikut = $alur[$p->status_pesanan] ?? [];
          $sudahBayar = (bool) $p->pembayaran;
          $tempatBayar = $delivery ? 'ALAMAT_PEMBELI' : 'TOKO';
        @endphp

        @if (in_array($p->status_pesanan, ['DIKONFIRMASI', 'DIPROSES']))
          <div class="p-4 rounded-xl border border-warm flex flex-wrap items-center justify-between gap-3">
            <div>
              <h4 class="text-sm font-bold text-stone-900">Pembayaran</h4>
              @if ($sudahBayar)
                <p class="text-xs text-emerald-700 font-medium">Sudah tercatat dibayar.</p>
              @else
                <p class="text-xs text-stone-500">{{ $delivery ? 'Delivery dibayar di alamat pembeli (dicatat kurir/admin).' : 'Pickup dibayar di toko. Pesanan otomatis selesai setelah dicatat.' }}</p>
              @endif
            </div>
            @if (!$sudahBayar)
              <form method="POST" action="{{ route('pembayaran.store', $p->id_pesanan) }}">
                @csrf
                <input type="hidden" name="tempat_pembayaran" value="{{ $tempatBayar }}">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-stone-900 hover:bg-black text-white text-xs font-semibold transition">Catat Pembayaran</button>
              </form>
            @endif
          </div>
        @endif

        @if (count($berikut))
          <form method="POST" action="{{ $urlStatus }}" class="p-4 rounded-xl border border-warm flex flex-wrap items-end justify-between gap-3">
            @csrf @method('PATCH')
            <div class="flex-1 min-w-[220px]">
              <h4 class="text-sm font-bold text-stone-900">Update Status Pesanan</h4>
              <select name="status_pesanan" class="mt-2 w-full text-xs font-medium border-stone-300 rounded-lg focus:border-maroon focus:ring-maroon py-2 px-3 bg-white">
                @foreach ($berikut as $kode)
                  <option value="{{ $kode }}">{{ $statusMap[$kode][0] }}</option>
                @endforeach
              </select>
              <p class="text-[11px] text-stone-400 mt-1">Hanya status lanjutan yang valid. Menyelesaikan pesanan butuh pembayaran tercatat; membatalkan mengembalikan stok.</p>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl border border-maroon text-maroon hover:bg-maroon-light text-xs font-semibold transition">Update Status</button>
          </form>
        @else
          <p class="text-xs text-stone-500 text-center">Pesanan ini sudah final, status tidak bisa diubah lagi.</p>
        @endif
      </div>
    </div>
  </div>
@endforeach
@endsection

@push('scripts')
<script>
  const buka = id => { const m = document.getElementById("detail-" + id); if (!m) return; m.classList.remove("hidden"); m.classList.add("flex");
    try { history.replaceState(null, "", "?id=" + id); } catch (e) {} };
  const tutupSemua = () => { document.querySelectorAll(".modal-detail").forEach(m => { m.classList.add("hidden"); m.classList.remove("flex"); });
    try { history.replaceState(null, "", location.pathname); } catch (e) {} };

  document.querySelectorAll(".btn-detail").forEach(b => b.addEventListener("click", () => buka(b.dataset.id)));
  document.querySelectorAll(".tutup").forEach(b => b.addEventListener("click", tutupSemua));
  document.querySelectorAll(".modal-detail").forEach(m => m.addEventListener("click", e => { if (e.target === m) tutupSemua(); }));
  document.addEventListener("keydown", e => { if (e.key === "Escape") tutupSemua(); });

  // Filter status
  document.querySelectorAll(".tab-st").forEach(t => t.addEventListener("click", () => {
    document.querySelectorAll(".tab-st").forEach(x => {
      const on = x === t;
      x.classList.toggle("bg-maroon", on); x.classList.toggle("text-white", on); x.classList.toggle("font-semibold", on); x.classList.toggle("shadow-sm", on);
      x.classList.toggle("bg-white", !on); x.classList.toggle("text-stone-700", !on); x.classList.toggle("border", !on); x.classList.toggle("border-warm", !on); x.classList.toggle("font-medium", !on);
    });
    document.querySelectorAll(".baris").forEach(r => r.classList.toggle("hidden", t.dataset.st !== "" && r.dataset.st !== t.dataset.st));
  }));

  // Buka otomatis kalau datang dari dashboard (?id=...)
  const idUrl = new URLSearchParams(location.search).get("id");
  if (idUrl) buka(idUrl);
</script>
@endpush
