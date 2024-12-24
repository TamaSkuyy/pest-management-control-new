<?php

namespace App\Http\Controllers;

use App\Models\Inspeksi;
use App\Models\Metode;

class DashboardController extends Controller
{

    public function linechart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        foreach ($categories as $month) {
            $count = Inspeksi::whereMonth('tanggal', date('m', strtotime($month)))
                ->whereYear('tanggal', date('Y'))
                ->count();
            $data[] = $count;
        }

        return response()->json([
            'categories' => $categories,
            'data' => $data,
        ]);
    }

    public function areachart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data = [];
        $metodes = Metode::all();

        foreach ($metodes as $metode) {
            $monthlyData = [];
            foreach ($categories as $month) {
                $count = Inspeksi::where('metode_id', $metode->id)
                    ->whereMonth('tanggal', date('m', strtotime($month)))
                    ->whereYear('tanggal', date('Y'))
                    ->count();
                $monthlyData[] = $count;
            }
            $data[] = ['name' => $metode->metode_nama, 'data' => $monthlyData];
        }

        return response()->json([
            'categories' => $categories,
            'data' => $data,
        ]);
    }
}
