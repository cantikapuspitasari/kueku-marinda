<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Support\XlsxWriter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OwnerLaporanController extends Controller
{
    private const PERIODE = ['hari', 'minggu', 'bulan'];

    private const NAMA_PERIODE = ['hari' => 'Harian', 'minggu' => 'Mingguan', 'bulan' => 'Bulanan'];

    private const HARI_SINGKAT = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

    /**
     * Membaca ?periode=hari|minggu|bulan dan ?tanggal=YYYY-MM-DD,
     * lalu menghitung rentang waktu laporan + periode pembandingnya.
     */
    private function tentukan(Request $request): array
    {
        $p = in_array($request->query('periode'), self::PERIODE, true) ? $request->query('periode') : 'hari';

        $acuan = now();
        if ($request->filled('tanggal')) {
            try {
                $acuan = Carbon::createFromFormat('Y-m-d', (string) $request->query('tanggal'))->startOfDay();
            } catch (\Throwable $e) {
                $acuan = now();
            }
        }

        if ($p === 'hari') {
            $dari = $acuan->copy()->startOfDay();
            $sampai = $acuan->copy()->endOfDay();
            $dariLalu = $dari->copy()->subDay();
            $sampaiLalu = $dariLalu->copy()->endOfDay();
            $label = $dari->copy()->locale('id')->translatedFormat('l, d F Y');
            $namaLalu = 'hari sebelumnya';
            $prev = $dari->copy()->subDay();
            $next = $dari->copy()->addDay();
        } elseif ($p === 'minggu') {
            $dari = $acuan->copy()->startOfWeek();
            $sampai = $acuan->copy()->endOfWeek();
            $dariLalu = $dari->copy()->subWeek();
            $sampaiLalu = $dariLalu->copy()->endOfWeek();
            $label = $dari->copy()->locale('id')->translatedFormat('d M') . ' - ' . $sampai->copy()->locale('id')->translatedFormat('d M Y');
            $namaLalu = 'minggu sebelumnya';
            $prev = $dari->copy()->subWeek();
            $next = $dari->copy()->addWeek();
        } else {
            $dari = $acuan->copy()->startOfMonth();
            $sampai = $acuan->copy()->endOfMonth();
            $dariLalu = $dari->copy()->subMonthNoOverflow()->startOfMonth();
            $sampaiLalu = $dariLalu->copy()->endOfMonth();
            $label = $dari->copy()->locale('id')->translatedFormat('F Y');
            $namaLalu = 'bulan sebelumnya';
            $prev = $dari->copy()->subMonthNoOverflow();
            $next = $dari->copy()->addMonthNoOverflow();
        }

        return compact('p', 'dari', 'sampai', 'dariLalu', 'sampaiLalu', 'label', 'namaLalu', 'prev', 'next');
    }

    /** Pesanan yang tidak dibatalkan pada rentang tertentu. */
    private function query(Carbon $dari, Carbon $sampai)
    {
        return Pesanan::whereBetween('tanggal_pesan', [$dari, $sampai])
            ->where('status_pesanan', '!=', 'DIBATALKAN');
    }

    /**
     * Mengolah semua angka laporan: ringkasan, jumlah pesanan per waktu, dan produk terlaris.
     */
    private function kumpulkan(string $p, Carbon $dari, Carbon $sampai, Carbon $dariLalu, Carbon $sampaiLalu): array
    {
        $pesanan = $this->query($dari, $sampai)
            ->with(['pembeli', 'detailPesanan.produk'])
            ->orderBy('tanggal_pesan')
            ->orderBy('id_pesanan')
            ->get();

        // ----- Kelompok waktu (batang grafik & baris rekap) -----
        $waktu = [];
        if ($p === 'hari') {
            for ($j = 0; $j < 24; $j += 2) {
                $waktu[] = ['label' => sprintf('%02d-%02d', $j, $j + 1), 'judul' => sprintf('%02d:00 - %02d:59', $j, $j + 1)];
            }
            $urut = fn (Carbon $t) => intdiv($t->hour, 2);
        } elseif ($p === 'minggu') {
            for ($i = 0; $i < 7; $i++) {
                $tgl = $dari->copy()->addDays($i);
                $waktu[] = ['label' => self::HARI_SINGKAT[$i] . ' ' . $tgl->day, 'judul' => $tgl->locale('id')->translatedFormat('l, d F Y')];
            }
            $urut = fn (Carbon $t) => $t->dayOfWeekIso - 1;
        } else {
            for ($d = 1; $d <= $dari->daysInMonth; $d++) {
                $tgl = $dari->copy()->day($d);
                $waktu[] = ['label' => (string) $d, 'judul' => $tgl->locale('id')->translatedFormat('l, d F Y')];
            }
            $urut = fn (Carbon $t) => $t->day - 1;
        }
        foreach ($waktu as &$w) {
            $w += ['pesanan' => 0, 'item' => 0, 'pendapatan' => 0];
        }
        unset($w);

        // ----- Hitung per pesanan & per produk -----
        $produk = [];
        $totalItem = 0;
        foreach ($pesanan as $ps) {
            $i = $urut(Carbon::parse($ps->tanggal_pesan));
            $itemPesanan = 0;

            foreach ($ps->detailPesanan as $d) {
                $jumlah = (int) $d->jumlah;
                $itemPesanan += $jumlah;

                $id = $d->id_produk;
                if (! isset($produk[$id])) {
                    $produk[$id] = [
                        'id'      => $id,
                        'nama'    => $d->produk->nama_produk ?? 'Produk (sudah dihapus)',
                        'foto'    => $d->produk->foto_produk ?? null,
                        'terjual' => 0,
                        'pesanan' => [],
                        'omzet'   => 0,
                    ];
                }
                $produk[$id]['terjual'] += $jumlah;
                $produk[$id]['pesanan'][$ps->id_pesanan] = true;
                $produk[$id]['omzet'] += (float) $d->subtotal;
            }

            $totalItem += $itemPesanan;
            if (isset($waktu[$i])) {
                $waktu[$i]['pesanan']++;
                $waktu[$i]['item'] += $itemPesanan;
                $waktu[$i]['pendapatan'] += (float) $ps->total_harga;
            }
        }

        $produk = collect($produk)->map(function ($x) {
            $x['pesanan'] = count($x['pesanan']);
            $x['omzet'] = (int) round($x['omzet']);

            return $x;
        })->sortBy([['terjual', 'desc'], ['omzet', 'desc'], ['nama', 'asc']])->values()->all();

        $total = (int) round($pesanan->sum('total_harga'));
        $jumlah = $pesanan->count();

        $jumlahLalu = $this->query($dariLalu, $sampaiLalu)->count();
        $totalLalu = (int) round($this->query($dariLalu, $sampaiLalu)->sum('total_harga'));

        $tumbuh = fn ($now, $lalu) => $lalu > 0 ? (int) round((($now - $lalu) / $lalu) * 100) : null;

        $ringkas = [
            'jumlah'              => $jumlah,
            'item'                => $totalItem,
            'total'               => $total,
            'rata'                => $jumlah > 0 ? (int) round($total / $jumlah) : 0,
            'jumlah_lalu'         => $jumlahLalu,
            'total_lalu'          => $totalLalu,
            'pertumbuhan_pesanan' => $tumbuh($jumlah, $jumlahLalu),
            'pertumbuhan_omzet'   => $tumbuh($total, $totalLalu),
        ];

        return compact('pesanan', 'waktu', 'produk', 'ringkas');
    }

    public function index(Request $request)
    {
        $t = $this->tentukan($request);
        $data = $this->kumpulkan($t['p'], $t['dari'], $t['sampai'], $t['dariLalu'], $t['sampaiLalu']);

        $p = $t['p'];
        $label = $t['label'];
        $namaLalu = $t['namaLalu'];
        $namaPeriode = self::NAMA_PERIODE[$p];
        $ringkas = $data['ringkas'];

        // Grafik 1: jumlah pesanan per waktu (BUKAN nominal rupiah)
        $batang = collect($data['waktu'])->map(fn ($w) => ['label' => $w['label'], 'nilai' => $w['pesanan']])->all();
        $nilaiMaks = max(array_column($batang, 'nilai'));
        $skala = max(2, $nilaiMaks + ($nilaiMaks % 2)); // genap, supaya garis tengah bernilai bulat
        $puncak = $nilaiMaks > 0 ? array_search($nilaiMaks, array_column($batang, 'nilai'), true) : null;

        // Grafik 2: 5 produk terlaris berdasarkan jumlah terjual
        $terlaris = array_slice($data['produk'], 0, 5);
        $terlarisMaks = max(1, (int) ($terlaris[0]['terjual'] ?? 0));

        // Navigasi periode & tanggal terpilih
        $tanggal = $t['dari']->toDateString();
        $tanggalPrev = $t['prev']->toDateString();
        $tanggalNext = $t['next']->toDateString();
        $tanggalDiminta = $request->filled('tanggal') ? $tanggal : null;

        return view('owner.laporan', compact(
            'p', 'label', 'namaLalu', 'namaPeriode', 'ringkas',
            'batang', 'skala', 'puncak', 'terlaris', 'terlarisMaks',
            'tanggal', 'tanggalPrev', 'tanggalNext', 'tanggalDiminta'
        ));
    }

    /**
     * Unduh laporan sebagai file Excel (.xlsx) berbentuk tabel — tanpa grafik.
     * Isi: Ringkasan, Produk Terlaris, Rekap per Waktu, Detail Pesanan.
     */
    public function unduh(Request $request)
    {
        $t = $this->tentukan($request);
        $p = $t['p'];
        $dari = $t['dari'];
        $sampai = $t['sampai'];
        $data = $this->kumpulkan($p, $dari, $sampai, $t['dariLalu'], $t['sampaiLalu']);

        $r = $data['ringkas'];
        $namaPeriode = self::NAMA_PERIODE[$p];
        $sel = [XlsxWriter::class, 'sel'];

        $rentang = $p === 'hari'
            ? $dari->copy()->locale('id')->translatedFormat('d F Y')
            : $dari->copy()->locale('id')->translatedFormat('d F Y') . ' s/d ' . $sampai->copy()->locale('id')->translatedFormat('d F Y');
        $terlarisPertama = $data['produk'][0] ?? null;

        // ===== Sheet 1: Ringkasan =====
        $ringkasan = [
            [$sel('Laporan Penjualan Kueku Marinda', 'judul')],
            ['Jenis Laporan', $namaPeriode],
            ['Periode', $t['label']],
            ['Rentang Tanggal', $rentang],
            ['Dicetak Pada', now()->locale('id')->translatedFormat('d F Y H:i')],
            ['Catatan', 'Pesanan berstatus DIBATALKAN tidak dihitung.'],
            [],
            [$sel('Indikator', 'header'), $sel('Nilai', 'header')],
            [$sel('Total Pesanan', 'teks'), $sel($r['jumlah'], 'angka')],
            [$sel('Total Produk Terjual (porsi)', 'teks'), $sel($r['item'], 'angka')],
            [$sel('Total Pendapatan', 'teks'), $sel($r['total'], 'rupiah')],
            [$sel('Rata-rata per Pesanan', 'teks'), $sel($r['rata'], 'rupiah')],
            [$sel('Produk Terlaris', 'teks'), $sel($terlarisPertama['nama'] ?? '-', 'teks')],
            [$sel('Jumlah Terjual Produk Terlaris', 'teks'), $sel((int) ($terlarisPertama['terjual'] ?? 0), 'angka')],
            [$sel('Total Pesanan ' . $t['namaLalu'], 'teks'), $sel($r['jumlah_lalu'], 'angka')],
            [$sel('Total Pendapatan ' . $t['namaLalu'], 'teks'), $sel($r['total_lalu'], 'rupiah')],
        ];

        // ===== Sheet 2: Produk Terlaris =====
        $produkBaris = [[
            $sel('Peringkat', 'header'), $sel('Nama Produk', 'header'), $sel('Jumlah Terjual (porsi)', 'header'),
            $sel('Muncul di (Pesanan)', 'header'), $sel('Pendapatan', 'header'), $sel('Porsi Terjual (%)', 'header'),
        ]];
        foreach ($data['produk'] as $i => $x) {
            $produkBaris[] = [
                $sel($i + 1, 'angka'), $sel($x['nama'], 'teks'), $sel($x['terjual'], 'angka'),
                $sel($x['pesanan'], 'angka'), $sel($x['omzet'], 'rupiah'),
                $sel($r['item'] > 0 ? $x['terjual'] / $r['item'] : 0, 'persen'),
            ];
        }
        if (empty($data['produk'])) {
            $produkBaris[] = [$sel('', 'teks'), $sel('Belum ada penjualan pada periode ini.', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks')];
        } else {
            $produkBaris[] = [
                $sel('', 'teks_tebal'), $sel('TOTAL', 'teks_tebal'), $sel($r['item'], 'angka_tebal'),
                $sel($r['jumlah'], 'angka_tebal'), $sel(array_sum(array_column($data['produk'], 'omzet')), 'rupiah_tebal'),
                $sel(1, 'persen_tebal'),
            ];
        }

        // ===== Sheet 3: Rekap per Waktu =====
        $judulWaktu = ['hari' => 'Jam', 'minggu' => 'Hari', 'bulan' => 'Tanggal'][$p];
        $rekap = [[
            $sel($judulWaktu, 'header'), $sel('Jumlah Pesanan', 'header'), $sel('Produk Terjual (porsi)', 'header'), $sel('Pendapatan', 'header'),
        ]];
        foreach ($data['waktu'] as $w) {
            $rekap[] = [$sel($w['judul'], 'teks'), $sel($w['pesanan'], 'angka'), $sel($w['item'], 'angka'), $sel((int) round($w['pendapatan']), 'rupiah')];
        }
        $rekap[] = [$sel('TOTAL', 'teks_tebal'), $sel($r['jumlah'], 'angka_tebal'), $sel($r['item'], 'angka_tebal'), $sel($r['total'], 'rupiah_tebal')];

        // ===== Sheet 4: Detail Pesanan =====
        $detail = [[
            $sel('No', 'header'), $sel('Kode Pesanan', 'header'), $sel('Tanggal & Jam', 'header'), $sel('Pelanggan', 'header'),
            $sel('Produk', 'header'), $sel('Jumlah Item', 'header'), $sel('Jenis', 'header'), $sel('Status', 'header'), $sel('Total', 'header'),
        ]];
        foreach ($data['pesanan'] as $i => $b) {
            $detail[] = [
                $sel($i + 1, 'angka'),
                $sel($b->kode_pesanan, 'teks'),
                $sel(Carbon::parse($b->tanggal_pesan)->format('d-m-Y H:i'), 'teks'),
                $sel($b->pembeli->nama ?? '-', 'teks'),
                $sel($b->detailPesanan->map(fn ($d) => ($d->produk->nama_produk ?? '-') . ' x' . $d->jumlah)->implode(', '), 'teks'),
                $sel((int) $b->detailPesanan->sum('jumlah'), 'angka'),
                $sel($b->jenis_pengambilan, 'teks'),
                $sel($b->status_pesanan, 'teks'),
                $sel((int) round($b->total_harga), 'rupiah'),
            ];
        }
        if ($data['pesanan']->isEmpty()) {
            $detail[] = [$sel('', 'teks'), $sel('Belum ada pesanan pada periode ini.', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks'), $sel('', 'teks')];
        } else {
            $detail[] = [
                $sel('', 'teks_tebal'), $sel('TOTAL', 'teks_tebal'), $sel('', 'teks_tebal'), $sel('', 'teks_tebal'), $sel('', 'teks_tebal'),
                $sel($r['item'], 'angka_tebal'), $sel('', 'teks_tebal'), $sel('', 'teks_tebal'), $sel($r['total'], 'rupiah_tebal'),
            ];
        }

        $xlsx = (new XlsxWriter())
            ->tambahSheet('Ringkasan', $ringkasan, [34, 40])
            ->tambahSheet('Produk Terlaris', $produkBaris, [11, 38, 20, 20, 18, 18])
            ->tambahSheet('Rekap per ' . $judulWaktu, $rekap, [28, 18, 24, 18])
            ->tambahSheet('Detail Pesanan', $detail, [6, 22, 18, 22, 55, 13, 12, 15, 16]);

        $akhiran = match ($p) {
            'hari'   => $dari->format('Ymd'),
            'minggu' => $dari->format('Ymd') . '-' . $sampai->format('Ymd'),
            default  => $dari->format('Y-m'),
        };
        $nama = 'laporan-penjualan-' . strtolower($namaPeriode) . '-' . $akhiran . '.xlsx';

        return response()->download($xlsx->simpan(), $nama, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
