<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Laporan penjualan owner: filter rentang tanggal, ringkasan, produk terlaris, rincian pesanan.
// Layar = ada grafik tren pendapatan. Cetak & Excel = semuanya tabel (tanpa grafik).
class OwnerLaporanController extends Controller
{
    private const JENIS = ['PICKUP' => 'Pengambilan', 'DELIVERY' => 'Pengantaran'];
    private const STATUS = [
        'PENDING' => 'Menunggu', 'DIKONFIRMASI' => 'Dikonfirmasi', 'DIPROSES' => 'Diproses',
        'SELESAI' => 'Selesai', 'DIBATALKAN' => 'Dibatalkan',
    ];

    // ---------- Rentang tanggal dari query ?dari=&sampai= (default: minggu ini) ----------
    private function rentang(Request $request): array
    {
        $aturan = [
            'dari'   => 'nullable|date_format:Y-m-d',
            'sampai' => 'nullable|date_format:Y-m-d',
            'top'    => 'nullable|integer|min:1|max:100',
        ];
        if ($request->filled('dari')) {
            $aturan['sampai'] .= '|after_or_equal:dari';
        }
        $request->validate($aturan, [
            'dari.date_format'      => 'Format "Dari Tanggal" tidak valid.',
            'sampai.date_format'    => 'Format "Sampai Tanggal" tidak valid.',
            'sampai.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
            'top.integer'           => 'Jumlah produk terlaris harus berupa angka.',
            'top.min'               => 'Jumlah produk terlaris minimal 1.',
            'top.max'               => 'Jumlah produk terlaris maksimal 100.',
        ]);

        $dari   = $request->filled('dari')   ? Carbon::createFromFormat('Y-m-d', $request->query('dari'))->startOfDay()   : now()->startOfWeek();
        $sampai = $request->filled('sampai') ? Carbon::createFromFormat('Y-m-d', $request->query('sampai'))->endOfDay()   : now()->endOfWeek();
        if ($request->filled('dari') && !$request->filled('sampai')) {
            $sampai = $dari->copy()->endOfDay();
        }
        $top = (int) ($request->query('top') ?: 10);

        return [$dari, $sampai, $top];
    }

    private function labelPeriode(Carbon $dari, Carbon $sampai): string
    {
        return $dari->copy()->locale('id')->translatedFormat('d M Y') . ' s/d ' . $sampai->copy()->locale('id')->translatedFormat('d M Y');
    }

    // ---------- Semua data laporan (dipakai layar, cetak, dan Excel) ----------
    private function kumpul(Carbon $dari, Carbon $sampai, int $top): array
    {
        // Hanya pesanan SELESAI yang dihitung (pesanan batal / belum selesai tidak dihitung)
        $selesai = DB::table('pesanan')
            ->whereBetween('tanggal_pesan', [$dari, $sampai])
            ->where('status_pesanan', 'SELESAI')
            ->get(['id_pesanan', 'tanggal_pesan', 'total_harga']);

        $totalPesanan = $selesai->count();
        $totalPendapatan = (int) round($selesai->sum('total_harga'));

        // Pembanding: periode sebelumnya dengan jumlah hari yang sama
        $hari = (int) round($dari->copy()->startOfDay()->diffInDays($sampai->copy()->startOfDay(), true)) + 1;
        $akhirLalu = $dari->copy()->subDay()->endOfDay();
        $awalLalu = $akhirLalu->copy()->subDays($hari - 1)->startOfDay();
        $totalLalu = (int) round(DB::table('pesanan')
            ->whereBetween('tanggal_pesan', [$awalLalu, $akhirLalu])
            ->where('status_pesanan', 'SELESAI')->sum('total_harga'));
        $pertumbuhan = $totalLalu > 0 ? (int) round((($totalPendapatan - $totalLalu) / $totalLalu) * 100) : null;

        $ringkas = [
            'pesanan'     => $totalPesanan,
            'pendapatan'  => $totalPendapatan,
            'rata'        => $totalPesanan > 0 ? (int) round($totalPendapatan / $totalPesanan) : 0,
            'pertumbuhan' => $pertumbuhan,
        ];

        // Produk terlaris: jumlah terjual terbanyak; kalau sama, pendapatan tertinggi
        $terlaris = DB::table('detail_pesanan as d')
            ->join('pesanan as p', 'p.id_pesanan', '=', 'd.id_pesanan')
            ->join('produk as pr', 'pr.id_produk', '=', 'd.id_produk')
            ->leftJoin('kategori as k', 'k.id_kategori', '=', 'pr.id_kategori')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->where('p.status_pesanan', 'SELESAI')
            ->groupBy('pr.id_produk', 'pr.nama_produk', 'k.nama_kategori')
            ->orderByDesc('terjual')
            ->orderByDesc('pendapatan')
            ->limit($top)
            ->get([
                'pr.nama_produk', 'k.nama_kategori',
                DB::raw('SUM(d.jumlah) as terjual'),
                DB::raw('SUM(d.subtotal) as pendapatan'),
            ]);

        // Rincian pesanan (semua status, kolom status menjelaskan)
        $rincian = DB::table('pesanan as p')
            ->leftJoin('pembeli as b', 'b.id_pembeli', '=', 'p.id_pembeli')
            ->whereBetween('p.tanggal_pesan', [$dari, $sampai])
            ->orderBy('p.tanggal_pesan')->orderBy('p.id_pesanan')
            ->get(['p.tanggal_pesan', 'p.kode_pesanan', 'b.nama as pelanggan', 'p.jenis_pengambilan', 'p.status_pesanan', 'p.total_harga'])
            ->map(function ($r) {
                $r->tanggal_fmt = Carbon::parse($r->tanggal_pesan)->format('d-m-Y H:i');
                $r->jenis_fmt   = self::JENIS[$r->jenis_pengambilan] ?? $r->jenis_pengambilan;
                $r->status_fmt  = self::STATUS[$r->status_pesanan] ?? $r->status_pesanan;
                return $r;
            });

        return [
            'ringkas'  => $ringkas,
            'terlaris' => $terlaris,
            'rincian'  => $rincian,
            'selesai'  => $selesai,
            'hari'     => $hari,
        ];
    }

    // ---------- Batang grafik tren pendapatan (hanya dipakai di layar) ----------
    private function batang($selesai, Carbon $dari, Carbon $sampai, int $hari): array
    {
        $bucket = []; // [label, awal, akhir]
        if ($hari === 1) {
            for ($j = 0; $j < 24; $j += 3) {
                $bucket[] = [sprintf('%02d-%02d', $j, $j + 2), $dari->copy()->setTime($j, 0), $dari->copy()->setTime($j + 2, 59, 59)];
            }
        } elseif ($hari <= 14) {
            for ($d = $dari->copy()->startOfDay(); $d->lte($sampai); $d->addDay()) {
                $bucket[] = [$d->copy()->locale('id')->translatedFormat('d M'), $d->copy()->startOfDay(), $d->copy()->endOfDay()];
            }
        } elseif ($hari <= 92) {
            for ($d = $dari->copy()->startOfDay(); $d->lte($sampai); $d->addDays(7)) {
                $akhir = $d->copy()->addDays(6)->endOfDay();
                $bucket[] = [$d->copy()->locale('id')->translatedFormat('d M'), $d->copy(), $akhir->gt($sampai) ? $sampai->copy() : $akhir];
            }
        } else {
            $lintasTahun = $dari->year !== $sampai->year;
            for ($m = $dari->copy()->startOfMonth(); $m->lte($sampai); $m->addMonthNoOverflow()) {
                $bucket[] = [$m->copy()->locale('id')->translatedFormat($lintasTahun ? 'M y' : 'M'), $m->copy()->startOfMonth(), $m->copy()->endOfMonth()];
            }
        }

        $rows = $selesai->map(fn ($s) => [Carbon::parse($s->tanggal_pesan), (float) $s->total_harga]);
        $batang = [];
        foreach ($bucket as [$label, $awal, $akhir]) {
            $nilai = (int) round($rows->filter(fn ($r) => $r[0]->gte($awal) && $r[0]->lte($akhir))->sum(fn ($r) => $r[1]));
            $batang[] = ['label' => $label, 'nilai' => $nilai];
        }

        return $batang;
    }

    // ---------- Halaman laporan (layar) ----------
    public function index(Request $request)
    {
        [$dari, $sampai, $top] = $this->rentang($request);
        $data = $this->kumpul($dari, $sampai, $top);

        $batang = $this->batang($data['selesai'], $dari, $sampai, $data['hari']);
        $nilaiMaks = collect($batang)->max('nilai');
        $maks = max(1, $nilaiMaks);
        $puncak = $nilaiMaks > 0 ? collect($batang)->search(fn ($b) => $b['nilai'] === $nilaiMaks) : null;

        $now = now();
        $cepat = [
            'Hari Ini'   => [$now->copy()->toDateString(), $now->copy()->toDateString()],
            'Minggu Ini' => [$now->copy()->startOfWeek()->toDateString(), $now->copy()->endOfWeek()->toDateString()],
            'Bulan Ini'  => [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()],
            'Tahun Ini'  => [$now->copy()->startOfYear()->toDateString(), $now->copy()->endOfYear()->toDateString()],
        ];

        return view('owner.laporan', [
            'dari'     => $dari->toDateString(),
            'sampai'   => $sampai->toDateString(),
            'top'      => $top,
            'label'    => $this->labelPeriode($dari, $sampai),
            'ringkas'  => $data['ringkas'],
            'terlaris' => $data['terlaris'],
            'rincian'  => $data['rincian'],
            'batang'   => $batang,
            'maks'     => $maks,
            'puncak'   => $puncak,
            'cepat'    => $cepat,
        ]);
    }

    // ---------- Cetak (PDF lewat dialog print) dan Export Excel: isi sama, semuanya tabel ----------
    public function unduh(Request $request)
    {
        [$dari, $sampai, $top] = $this->rentang($request);
        $data = $this->kumpul($dari, $sampai, $top);
        $format = $request->query('format') === 'cetak' ? 'cetak' : 'excel';

        $html = view('owner.laporan-cetak', [
            'mode'     => $format,
            'label'    => $this->labelPeriode($dari, $sampai),
            'top'      => $top,
            'ringkas'  => $data['ringkas'],
            'terlaris' => $data['terlaris'],
            'rincian'  => $data['rincian'],
        ])->render();

        if ($format === 'cetak') {
            return response($html);
        }

        $nama = 'laporan-pesanan-' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.xls';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nama . '"',
        ]);
    }
}
