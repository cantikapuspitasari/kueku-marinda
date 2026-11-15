<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Struk {{ $pesanan->kode_pesanan }}</title>
  <style>
    /* ===== Ukuran font: ubah di sini kalau mau ===== */
    :root {
      --lebar: 8cm;        /* lebar struk */
      --fs-item: 12px;     /* nama produk, jumlah x harga, subtotal item */
      --fs-bawah: 11px;    /* subtotal / ongkir / total / pembayaran (di bawah daftar produk) */
      --fs-kecil: 10px;    /* alamat & kontak paling bawah */
    }

    * { box-sizing: border-box; }
    body { margin: 0; background: #e7e5e4; font-family: "Courier New", Courier, monospace; color: #000; }

    .halaman { padding: 16px 0 40px; display: flex; flex-direction: column; align-items: center; gap: 14px; }

    .struk { width: var(--lebar); background: #fff; padding: 5mm 4mm 6mm; font-size: var(--fs-item); line-height: 1.35; }

    .tengah { text-align: center; }
    .garis  { border: 0; border-top: 1px dashed #000; margin: 7px 0; }
    .baris  { display: flex; justify-content: space-between; gap: 8px; }
    .baris > span:last-child { text-align: right; white-space: nowrap; }

    .logo { width: 22mm; height: auto; display: block; margin: 0 auto 3px; }
    .toko { font-size: 14px; font-weight: bold; letter-spacing: .5px; }
    .kode { font-size: var(--fs-item); font-weight: bold; }

    .info { font-size: var(--fs-item); }
    .info .baris { justify-content: flex-start; gap: 0; }
    .info .baris > span:last-child { text-align: left; white-space: normal; }
    .info .k { display: inline-block; width: 11ch; flex-shrink: 0; }

    .produk { margin-bottom: 4px; }
    .produk .nama { font-weight: bold; text-transform: uppercase; }

    .bawah { font-size: var(--fs-bawah); }
    .grand { font-size: var(--fs-item); font-weight: bold; }

    .kecil { font-size: var(--fs-kecil); line-height: 1.4; }

    .tombol { display: flex; gap: 8px; font-family: system-ui, sans-serif; }
    .tombol button, .tombol a { padding: 9px 18px; border-radius: 8px; border: 1px solid #993a3c; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; }
    .tombol button { background: #993a3c; color: #fff; }
    .tombol a { background: #fff; color: #993a3c; }

    /* ===== Input uang diterima (hanya di layar, tidak ikut tercetak) ===== */
    .tombol button:disabled { opacity: .45; cursor: not-allowed; }
    .notif { width: min(92vw, 420px); padding: 10px 14px; border-radius: 8px; font-family: system-ui, sans-serif; font-size: 13px; }
    .notif.ok  { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
    .notif.err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
    .panel-bayar { width: min(92vw, 420px); background: #fff; border: 1px solid #d6d3d1; border-radius: 12px; padding: 14px 16px; font-family: system-ui, sans-serif; font-size: 13px; color: #292524; }
    .panel-bayar h2 { margin: 0 0 2px; font-size: 14px; }
    .panel-bayar .sub { margin: 0 0 10px; font-size: 12px; color: #78716c; }
    .panel-bayar label { display: block; font-weight: 600; margin-bottom: 4px; }
    .panel-bayar input[type=text] { width: 100%; padding: 9px 10px; border: 1px solid #a8a29e; border-radius: 8px; font-size: 15px; font-family: inherit; }
    .panel-bayar input[type=text]:focus { outline: 2px solid #993a3c55; border-color: #993a3c; }
    .panel-bayar .ringkas { margin: 10px 0 4px; padding: 8px 10px; background: #faf7f2; border-radius: 8px; }
    .panel-bayar .ringkas div { display: flex; justify-content: space-between; padding: 2px 0; }
    .panel-bayar .ringkas .utama { font-weight: 700; color: #993a3c; }
    .panel-bayar .bayar-pesan { min-height: 18px; margin: 4px 0 8px; font-size: 12px; font-weight: 600; }
    .bayar-salah { color: #b91c1c; }
    .bayar-info  { color: #57534e; }
    .panel-bayar button { width: 100%; padding: 10px 18px; border-radius: 8px; border: 1px solid #993a3c; background: #993a3c; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; }
    .panel-bayar button:disabled { opacity: .45; cursor: not-allowed; }

    /* Ukuran kertas cadangan; tinggi sebenarnya dihitung otomatis oleh skrip di bawah */
    @page { size: 80mm 200mm; margin: 0; }
    @media print {
      html, body { width: var(--lebar); margin: 0; padding: 0; background: #fff; }
      .halaman { padding: 0; display: block; }
      .tombol, .notif, .panel-bayar, .pratinjau { display: none !important; }
      .struk { box-shadow: none; margin: 0; page-break-inside: avoid; break-inside: avoid; }
    }
  </style>
</head>
<body>
@php
  $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
  $waktuTeks = \Carbon\Carbon::parse($waktu)->locale('id')->translatedFormat('d M Y, H.i');
  $tglPesanTeks = \Carbon\Carbon::parse($pesanan->tanggal_pesan)->locale('id')->translatedFormat('d M Y, H.i');
  $logo = public_path('images/logo-struk.png');
@endphp

<div class="halaman">
  <div class="tombol">
    <button type="button" onclick="window.print()" @if ($bisaInputBayar) disabled title="Simpan pembayaran dulu sebelum mencetak" @endif>Cetak Struk</button>
    <a href="{{ $urlKembali }}">Kembali</a>
  </div>

  @if (session('success'))
    <div class="notif ok">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="notif err">
      @foreach ($errors->all() as $pesanError)<div>{{ $pesanError }}</div>@endforeach
    </div>
  @endif

  @if ($bisaInputBayar)
    <form method="POST" action="{{ route('pembayaran.store', $pesanan->id_pesanan) }}" class="panel-bayar" data-bayar data-total="{{ $total }}" autocomplete="off">
      @csrf
      <input type="hidden" name="tempat_pembayaran" value="TOKO">
      <input type="hidden" name="uang_diterima" value="{{ old('uang_diterima') }}" data-bayar-nilai>

      <h2>Pembayaran Ambil di Toko</h2>
      <p class="sub">Masukkan uang tunai yang diberikan pembeli. Kembalian dihitung otomatis.</p>

      <label for="uang-diterima">Uang diterima</label>
      <input type="text" id="uang-diterima" inputmode="numeric" placeholder="Contoh: 100000" value="{{ old('uang_diterima') }}" data-bayar-input>

      <div class="ringkas">
        <div><span>Total pembayaran</span><span>{{ $rp($total) }}</span></div>
        <div><span>Uang diterima</span><span data-bayar-diterima>Rp0</span></div>
        <div class="utama"><span>Kembalian</span><span data-bayar-kembalian>Rp0</span></div>
      </div>

      <div class="bayar-pesan" data-bayar-pesan></div>

      <button type="submit" data-bayar-submit disabled>Simpan Pembayaran</button>
    </form>
  @endif

  <div class="struk">
    <div class="tengah">
      @if (file_exists($logo))
        <img src="{{ asset('images/logo-struk.png') }}" alt="Kueku Marinda" class="logo">
      @else
        <div class="toko">KUEKU MARINDA</div>
      @endif
      <div class="kode">{{ $pesanan->kode_pesanan }}</div>
      <div class="kecil" style="margin-top:4px;">
        <div>Jl. Terusan Ps. Cibeureum No.13, Ciwidey, Kec. Ciwidey, Kabupaten Bandung, Jawa Barat 40973</div>
        <div>Whatsapp : 081214785234</div>
      </div>
    </div>

    <hr class="garis">

    <div class="info">
      <div class="baris"><span class="k">Pelanggan</span><span>: {{ $pelanggan }}</span></div>
      <div class="baris"><span class="k">Tgl Pesan</span><span>: {{ $tglPesanTeks }}</span></div>
      @if ($pembayaran)
        <div class="baris"><span class="k">Transaksi</span><span>: {{ $waktuTeks }}</span></div>
      @endif
      <div class="baris"><span class="k">Staff</span><span>: {{ $staffNama ? $staffNama . ' (' . $staffPeran . ')' : $staffPeran }}</span></div>
      <div class="baris"><span class="k">Metode</span><span>: {{ $delivery ? 'Diantar kurir' : 'Ambil di toko' }}</span></div>
    </div>

    <hr class="garis">

    @foreach ($pesanan->detailPesanan as $d)
      @php $satuan = $d->jumlah > 0 ? $d->subtotal / $d->jumlah : 0; @endphp
      <div class="produk">
        <div class="nama">{{ $d->produk->nama_produk ?? 'Produk dihapus' }}</div>
        <div class="baris"><span>{{ $d->jumlah }} x {{ $rp($satuan) }}</span><span>{{ $rp($d->subtotal) }}</span></div>
      </div>
    @endforeach

    <hr class="garis">

    <div class="bawah">
      <div class="baris"><span>Subtotal :</span><span>{{ $rp($subtotal) }}</span></div>
      @if ($ongkir > 0)
        <div class="baris"><span>Ongkos kirim :</span><span>{{ $rp($ongkir) }}</span></div>
      @endif
      <div class="baris"><span>Total :</span><span>{{ $rp($total) }}</span></div>
    </div>

    <hr class="garis">

    <div class="baris grand"><span>Grand Total :</span><span>{{ $rp($total) }}</span></div>

    <hr class="garis">

    <div class="bawah">
      @if ($pembayaran)
        <div class="baris"><span>Pembayaran :</span><span>Tunai {{ $pembayaran->tempat_pembayaran === 'TOKO' ? 'di toko' : 'ke kurir' }}</span></div>
        <div class="baris"><span>Uang diterima :</span><span>{{ $rp($uangDiterima) }}</span></div>
        <div class="baris"><span>Kembalian :</span><span>{{ $rp($kembalian) }}</span></div>
      @else
        @if ($bisaInputBayar)
          {{-- Pratinjau saat admin mengetik; tidak ikut tercetak sampai pembayaran disimpan --}}
          <div class="baris pratinjau"><span>Uang diterima* :</span><span id="pv-diterima">Rp0</span></div>
          <div class="baris pratinjau"><span id="pv-label">Kembalian* :</span><span id="pv-kembalian">Rp0</span></div>
          <div class="pratinjau kecil">*pratinjau, belum disimpan</div>
        @endif
        <div class="baris"><span>Status :</span><span>BELUM DIBAYAR</span></div>
      @endif
    </div>

    <hr class="garis">

    <div class="tengah kecil">
      <div style="font-size: var(--fs-bawah);">Enjoy the TASTE</div>
    </div>

  </div>
</div>

@if ($bisaInputBayar)
<script src="{{ asset('js/kembalian.js') }}"></script>
<script>
  // Pratinjau uang diterima & kembalian pada struk saat admin mengetik
  (function () {
    var form = document.querySelector('form[data-bayar]');
    if (!form) return;
    var rp = function (n) { return 'Rp' + Number(n).toLocaleString('id-ID'); };
    form.addEventListener('bayar:hitung', function (e) {
      var d = e.detail;
      document.getElementById('pv-diterima').textContent = rp(d.uang);
      document.getElementById('pv-kembalian').textContent = rp(d.kembalian);
      document.getElementById('pv-label').textContent = d.kurang ? 'Kurang* :' : 'Kembalian* :';
      if (d.kurang) document.getElementById('pv-kembalian').textContent = rp(d.total - d.uang);
    });
  })();
</script>
@endif

<script>
  // Kertas = lebar 8 cm x tinggi sesuai panjang struk (bukan A4).
  (function () {
    function aturKertas() {
      var el = document.querySelector('.struk');
      if (!el) return;
      var mm = Math.ceil(el.getBoundingClientRect().height * 25.4 / 96) + 2;
      var st = document.getElementById('ukuran-kertas');
      if (!st) { st = document.createElement('style'); st.id = 'ukuran-kertas'; document.head.appendChild(st); }
      st.textContent = '@page { size: 80mm ' + mm + 'mm; margin: 0; }';
    }
    window.addEventListener('load', aturKertas);
    window.addEventListener('beforeprint', aturKertas);
  })();
</script>
</body>
</html>
