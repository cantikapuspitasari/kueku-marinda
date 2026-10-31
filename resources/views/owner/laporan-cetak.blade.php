<!DOCTYPE html>
<html lang="id" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="utf-8">
<title>Laporan Pesanan, {{ $label }}</title>
<style>
  body { font-family: Arial, Helvetica, sans-serif; color: #1c1917; font-size: 12px; margin: 24px; }
  h1 { font-size: 18px; margin: 0 0 4px; }
  h2 { font-size: 14px; margin: 22px 0 6px; }
  p.ket { margin: 0 0 4px; color: #57534e; }
  table { border-collapse: collapse; width: 100%; }
  th, td { border: 1px solid #78716c; padding: 5px 8px; text-align: left; vertical-align: top; }
  th { background: #f1ebe3; }
  td.kanan, th.kanan { text-align: right; }
  td.tengah, th.tengah { text-align: center; }
  .kosong { padding: 10px; border: 1px solid #78716c; color: #57534e; }
  .catatan { margin-top: 18px; font-size: 11px; color: #57534e; }
  .alat { margin-bottom: 16px; }
  .alat button { background: #993A3C; color: #fff; border: 0; padding: 8px 16px; border-radius: 6px; font-size: 13px; cursor: pointer; }
  @media print { .alat { display: none; } body { margin: 0; } }
</style>
</head>
<body>
@if ($mode === 'cetak')
  <div class="alat"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>
@endif

<h1>Laporan Pesanan, {{ $label }}</h1>
<p class="ket">Kueku Marinda • Dicetak {{ now()->locale('id')->translatedFormat('d M Y H:i') }}</p>

<h2>Ringkasan</h2>
<table border="1" cellspacing="0" cellpadding="4" style="width:50%">
  <tr><th>Keterangan</th><th class="kanan">Nilai</th></tr>
  <tr><td>Total Pesanan</td><td class="kanan">{{ number_format($ringkas['pesanan'], 0, ',', '.') }}</td></tr>
  <tr><td>Total Pendapatan</td><td class="kanan">Rp {{ number_format($ringkas['pendapatan'], 0, ',', '.') }}</td></tr>
</table>

<h2>Produk Terlaris, {{ $label }}</h2>
@if ($terlaris->isEmpty())
  <div class="kosong">Tidak ada data penjualan pada periode ini.</div>
@else
  <table border="1" cellspacing="0" cellpadding="4">
    <tr>
      <th class="tengah">Peringkat</th><th>Nama Produk</th><th>Kategori</th>
      <th class="kanan">Jumlah Terjual</th><th class="kanan">Total Pendapatan</th>
    </tr>
    @foreach ($terlaris as $i => $t)
      <tr>
        <td class="tengah">{{ $i + 1 }}</td>
        <td>{{ $t->nama_produk }}</td>
        <td>{{ $t->nama_kategori ?? '-' }}</td>
        <td class="kanan">{{ number_format($t->terjual, 0, ',', '.') }}</td>
        <td class="kanan">Rp {{ number_format($t->pendapatan, 0, ',', '.') }}</td>
      </tr>
    @endforeach
  </table>
@endif

<h2>Rincian Pesanan</h2>
@if ($rincian->isEmpty())
  <div class="kosong">Tidak ada pesanan pada periode ini.</div>
@else
  <table border="1" cellspacing="0" cellpadding="4">
    <tr>
      <th>Tanggal</th><th>No. Pesanan</th><th>Pelanggan</th><th>Jenis</th><th>Status</th><th class="kanan">Total</th>
    </tr>
    @foreach ($rincian as $r)
      <tr>
        <td>{{ $r->tanggal_fmt }}</td>
        <td>{{ $r->kode_pesanan }}</td>
        <td>{{ $r->pelanggan ?? '-' }}</td>
        <td>{{ $r->jenis_fmt }}</td>
        <td>{{ $r->status_fmt }}</td>
        <td class="kanan">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
      </tr>
    @endforeach
  </table>
@endif

<p class="catatan">Catatan: Ringkasan dan Produk Terlaris hanya menghitung pesanan berstatus Selesai. Pesanan yang dibatalkan tidak dihitung.</p>

@if ($mode === 'cetak')
<script>window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 300); });</script>
@endif
</body>
</html>
