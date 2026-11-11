<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HealthCheckController extends Controller
{
    public function index()
    {
        $status = [
            'app'       => 'OK',
            'database'  => 'UNKNOWN',
            'timestamp' => now()->toDateTimeString(),
        ];
        $httpCode = 200;

        try {
            DB::connection()->getPdo();
            $status['database'] = 'CONNECTED';

            // Detail hanya untuk lingkungan development
            if (config('app.debug')) {
                $status['database_name'] = DB::connection()->getDatabaseName();
                $status['jumlah_produk'] = DB::table('produk')->count();
                $status['jumlah_user'] = DB::table('users')->count();
            }
        } catch (\Throwable $e) {
            Log::error('Healthcheck: koneksi database gagal - ' . $e->getMessage());

            $status['database'] = 'FAILED';
            if (config('app.debug')) {
                $status['error'] = $e->getMessage();
            }
            $httpCode = 503;
        }

        return response()->json($status, $httpCode);
    }
}