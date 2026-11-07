<?php

namespace App\Http\Controllers;

use App\Models\InfaqHistory;
use App\Models\InfaqSetting;

class InfaqController extends Controller
{
    /**
     * Halaman publik infaq: QRIS, ringkasan kas, dan histori transaksi.
     */
    public function index()
    {
        $setting = InfaqSetting::current();
        $totals = InfaqHistory::totals();

        return view('pages.infaq', compact('setting', 'totals'));
    }
}
