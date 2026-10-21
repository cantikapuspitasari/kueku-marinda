<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerLaporanController extends Controller
{
    // Menentukan rentang tanggal + label untuk tiap periode
    private function periode(string $p): array
    {
        $now = now();
        if ($p === 'tahun') {
            return [$now->copy()->startOfYear(), $now->copy()->endOfYear(), $now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear(),
                'Tahun ' . $now->year, 'tahun ini', 'tahun lalu'];
        }
        if ($p === 'bulan') {
            return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(), $now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth(),
                $now->locale('id')->translatedFormat('F Y'), 'bulan ini', 'bulan lalu'];
        }
        return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek(), $now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek(),
            $now->copy()->startOfWeek()->locale('id')->translatedFormat('d M') . ' - ' . $now->copy()->endOfWeek()->locale('id')->translatedFormat('d M Y'), 'minggu ini', 'minggu lalu'];
    }

    // Pesanan tidak dibatalkan pada rentang tertentu
    private function ambil(Carbon $dari, Carbon $sampai)
    {
        return Pesanan::whereBetween('tanggal_pesan', [$dari, $sampai])
            ->where('status_pesanan', '!=', 'DIBATALKAN')
            ->get(['id_pesanan', 'tanggal_pesan', 'total_harga']);
    }

    public function index(Request $request)
    {
        $p = in_array($request->query('periode'), ['minggu', 'bulan', 'tahun'], true) ? $request->query('periode') : 'minggu';
        [$dari, $sampai, $dariLalu, $sampaiLalu, $label, $namaIni, $namaLalu] = $this->periode($p);

        $data = $this->ambil($dari, $sampai);
        $total = (int) $data->sum('total_harga');
        $jumlah = $data->count();
        $totalLalu = (int) $this->ambil($dariLalu, $sampaiLalu)->sum('total_harga');
        $pertumbuhan = $totalLalu > 0 ? round((($total - $totalLalu) / $totalLalu) * 100) : null;

        // Batang grafik
        $batang = [];
        if ($p === 'tahun') {
            $bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            foreach ($bln as $i => $nama) {
                $batang[] = ['label' => $nama, 'nilai' => (int) $data->filter(fn ($d) => Carbon::parse($d->tanggal_pesan)->month === $i + 1)->sum('total_harga')];
            }
        } elseif ($p === 'bulan') {
            $akhir = $dari->daysInMonth;
            for ($mulai = 1; $mulai <= $akhir; $mulai += 7) {
                $selesai = min($mulai + 6, $akhir);
                $batang[] = ['label' => $mulai . '-' . $selesai, 'nilai' => (int) $data->filter(function ($d) use ($mulai, $selesai) {
                    $h = Carbon::parse($d->tanggal_pesan)->day;
                    return $h >= $mulai && $h <= $selesai;
                })->sum('total_harga')];
            }
        } else {
            $hari = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
            foreach ($hari as $i => $nama) {
                $batang[] = ['label' => $nama, 'nilai' => (int) $data->filter(fn ($d) => Carbon::parse($d->tanggal_pesan)->dayOfWeekIso === $i + 1)->sum('total_harga')];
            }
        }
        $maks = max(1, collect($batang)->max('nilai'));
        $puncak = collect($batang)->max('nilai') > 0 ? collect($batang)->search(fn ($b) => $b['nilai'] === collect($batang)->max('nilai')) : null;

        $terlaris = DB::table('detail_pesanan')
            ->join('pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.id_pesanan')
            ->join('produk', 'produk.id_produk', '=', 'detail_pesanan.id_produk')
            ->whereBetween('pesanan.tanggal_pesan', [$dari, $sampai])
            ->where('pesanan.status_pesanan', '!=', 'DIBATALKAN')
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.foto_produk')
            ->orderByDesc('terjual')
            ->limit(5)
            ->get(['produk.id_produk', 'produk.nama_produk', 'produk.foto_produk', DB::raw('SUM(detail_pesanan.jumlah) as terjual'), DB::raw('SUM(detail_pesanan.subtotal) as omzet')]);

        $ringkas = [
            'total' => $total, 'jumlah' => $jumlah, 'pertumbuhan' => $pertumbuhan,
            'rata' => $jumlah > 0 ? (int) round($total / $jumlah) : 0,
        ];

        return view('owner.laporan', compact('p', 'label', 'namaIni', 'namaLalu', 'batang', 'maks', 'puncak', 'terlaris', 'ringkas'));
    }

    // Unduh rekap pesanan periode terpilih sebagai CSV (bisa dibuka di Excel)
    public function unduh(Request $request)
    {
        $p = in_array($request->query('periode'), ['minggu', 'bulan', 'tahun'], true) ? $request->query('periode') : 'minggu';
        [$dari, $sampai] = $this->periode($p);

        $baris = Pesanan::with(['pembeli', 'detailPesanan.produk'])
            ->whereBetween('tanggal_pesan', [$dari, $sampai])
            ->orderBy('tanggal_pesan')
            ->get();

        $nama = 'laporan-penjualan-' . $p . '-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($baris) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // agar Excel membaca UTF-8
            fputcsv($out, ['No. Pesanan', 'Tanggal', 'Pelanggan', 'Produk', 'Jenis', 'Status', 'Total (Rp)']);
            foreach ($baris as $b) {
                fputcsv($out, [
                    $b->kode_pesanan,
                    Carbon::parse($b->tanggal_pesan)->format('Y-m-d H:i'),
                    $b->pembeli->nama ?? $b->pembeli->name ?? '-',
                    $b->detailPesanan->map(fn ($d) => ($d->produk->nama_produk ?? '-') . ' x' . $d->jumlah)->implode('; '),
                    $b->jenis_pengambilan,
                    $b->status_pesanan,
                    (int) $b->total_harga,
                ]);
            }
            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
