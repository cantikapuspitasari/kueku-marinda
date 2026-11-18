<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pembayaran QRIS - Kueku Marinda</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;background:#FCF9F4;color:#292524;-webkit-font-smoothing:antialiased}
main{max-width:28rem;margin:0 auto;padding:1.5rem 1rem}
.tengah{text-align:center}
h1{font-family:Georgia,"Times New Roman",serif;font-size:1.6rem;color:#993A3C;margin:0}
.lencana{display:inline-block;margin-top:.5rem;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:#fef3c7;color:#92400e;padding:.25rem .75rem;border-radius:999px}
.kartu{background:#fff;border:1px solid #EAE5DC;border-radius:1rem;padding:1.5rem;margin-top:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.baris{display:flex;justify-content:space-between;gap:1rem;font-size:.9rem;margin-bottom:.75rem}
.baris dt{color:#78716c;margin:0}.baris dd{margin:0;font-weight:600;text-align:right}
.kode{font-family:ui-monospace,Menlo,Consolas,monospace;font-weight:700}
.total{display:flex;justify-content:space-between;align-items:baseline;border-top:1px solid #EAE5DC;padding-top:.75rem;margin:0}
.total dd{margin:0;font-size:1.6rem;font-weight:700;color:#993A3C}
.total dt{color:#78716c;font-size:.9rem}
.btn{width:100%;border:0;background:#993A3C;color:#fff;font-size:1rem;font-weight:700;padding:.9rem;border-radius:999px;margin-top:.25rem}
.btn:disabled{opacity:.6}
.kecil{font-size:.75rem;color:#78716c;text-align:center;margin:.75rem 0}
.err{background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:.75rem;padding:.75rem 1rem;font-size:.85rem;margin-bottom:1rem}
.ok{width:4rem;height:4rem;border-radius:50%;background:#d1fae5;color:#047857;display:flex;align-items:center;justify-content:center;margin:1.25rem auto 0}
.lunas{font-size:1.25rem;font-weight:700;color:#047857;margin:.75rem 0 0}
</style>
</head>
<body>
<main>

  <div class="tengah">
    <h1>Kueku Marinda</h1>
    <span class="lencana">Pembayaran QRIS</span>
  </div>

  <div class="kartu">

    @if (session('qris_error'))
      <div class="err">{{ session('qris_error') }}</div>
    @endif

    <dl style="margin:0">
      <div class="baris"><dt>Kode pesanan</dt><dd class="kode">{{ $pesanan->kode_pesanan }}</dd></div>
      <div class="baris"><dt>Pembeli</dt><dd>{{ $pesanan->pembeli->nama ?? '-' }}</dd></div>
      <div class="baris"><dt>Metode</dt><dd>QRIS</dd></div>
      <div class="total"><dt>Nominal</dt><dd>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd></div>
    </dl>

    @if ($lunas)
      <div class="tengah">
        <div class="ok"><svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"/></svg></div>
        <p class="lunas">Pembayaran Berhasil</p>
        <p class="kecil" style="font-size:.9rem">Status pesanan: <b>LUNAS</b>@if ($pesanan->waktu_bayar) pada {{ $pesanan->waktu_bayar->format('d M Y, H:i') }}@endif.</p>
        <p class="kecil">Anda boleh menutup halaman ini dan kembali ke website di perangkat pembeli.</p>
      </div>
    @elseif ($batal)
      <p class="err" style="margin:1.25rem 0 0;text-align:center;font-weight:600">Pesanan ini sudah dibatalkan, pembayaran tidak dapat diproses.</p>
    @else
      <form method="POST" action="{{ $konfirmasiUrl }}" style="margin-top:1.25rem" onsubmit="this.querySelector('button').disabled = true;">
        @csrf
        <p class="kecil">Periksa nominal dan kode pesanan di atas, lalu tekan tombol di bawah.</p>
        <button type="submit" class="btn">Bayar Sekarang</button>
      </form>
    @endif
  </div>

</main>
</body>
</html>
