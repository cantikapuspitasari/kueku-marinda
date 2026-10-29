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

    .logo { max-width: 55mm; max-height: 18mm; display: block; margin: 0 auto 3px; }
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

    /* Ukuran kertas cadangan; tinggi sebenarnya dihitung otomatis oleh skrip di bawah */
    @page { size: 80mm 200mm; margin: 0; }
    @media print {
      html, body { width: var(--lebar); margin: 0; padding: 0; background: #fff; }
      .halaman { padding: 0; display: block; }
      .tombol { display: none; }
      .struk { box-shadow: none; margin: 0; page-break-inside: avoid; break-inside: avoid; }
    }
  </style>
</head>
<body>
@php
  $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');
  $waktuTeks = \Carbon\Carbon::parse($waktu)->locale('id')->translatedFormat('d M Y, H.i');
  $logo = public_path('images/logo-struk.png');
@endphp

<div class="halaman">
  <div class="tombol">
    <button type="button" onclick="window.print()">Cetak Struk</button>
    <a href="javascript:history.back()">Kembali</a>
  </div>

  <div class="struk">
    <div class="tengah">
      @if (file_exists($logo))
        <img src="{{ asset('images/logo-struk.png') }}" alt="Kueku Marinda" class="logo">
      @endif
      <div class="toko">KUEKU MARINDA</div>
      <div class="kode">{{ $pesanan->kode_pesanan }}</div>
    </div>

    <hr class="garis">

    <div class="info">
      <div class="baris"><span class="k">Pelanggan</span><span>: {{ $pelanggan }}</span></div>
      <div class="baris"><span class="k">Transaksi</span><span>: {{ $waktuTeks }}</span></div>
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
        <div class="baris"><span>Tunai ({{ $pembayaran->tempat_pembayaran === 'TOKO' ? 'di toko' : 'ke kurir' }}) :</span><span>{{ $rp($pembayaran->jumlah_bayar) }}</span></div>
      @else
        <div class="baris"><span>Status :</span><span>BELUM DIBAYAR</span></div>
      @endif
    </div>

    <hr class="garis">

    <div class="tengah kecil">
      <div style="font-size: var(--fs-bawah);">Enjoy the TASTE</div>
    </div>

    <hr class="garis">

    <div class="kecil">
      <div>Alamat : Jl. Terusan Ps. Cibeureum, Ciwidey, Kec. Ciwidey, Kabupaten Bandung, Jawa Barat 40973</div>
      <div>Whatsapp : 081214785234</div>
    </div>
  </div>
</div>

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
