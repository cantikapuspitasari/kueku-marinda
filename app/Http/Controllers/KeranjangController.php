<?php

namespace App\Http\Controllers;

class KeranjangController extends Controller
{
    public function index()
    {
        return view('keranjang.index', ['produkJs' => ProdukController::katalogJs()]);
    }
}
