<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    public function index()
    {
        $status = [
            'app'      => 'OK',
            'database' => 'UNKNOWN',
            'timestamp'=> now()->toDateTimeString(),
        ];
        $httpCode = 200;

        try {
            DB::connection()->getPdo();
            $status['database'] = 'CONNECTED';
            $status['database_name'] = DB::connection()->getDatabaseName();

            // Cek juga jumlah tabel utama biar makin yakin schema-nya ada
            $status['jumlah_produk'] = DB::table('produk')->count();
            $status['jumlah_user'] = DB::table('users')->count();

        } catch (\Exception $e) {
            $status['database'] = 'FAILED';
            $status['error'] = $e->getMessage();
            $httpCode = 500;
        }

        return response()->json($status, $httpCode);
    }
}