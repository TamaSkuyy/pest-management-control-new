<?php
namespace App\Http\Controllers;

use App\Models\Inspeksi;
use App\Models\Inspeksidetail;
use App\Models\Lokasi;
use App\Models\Metode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function linechart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data       = [];
        foreach ($categories as $month) {
            $count = Inspeksi::whereMonth('tanggal', date('m', strtotime($month)))
                ->whereYear('tanggal', date('Y'))
                ->count();
            $data[] = $count;
        }

        return response()->json([
            'categories' => $categories,
            'data'       => $data,
        ]);
    }

    public function linechartbyyear($year = null)
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data       = [];
        foreach ($categories as $month) {
            $count = Inspeksi::whereMonth('tanggal', date('m', strtotime($month)))
                ->whereYear('tanggal', $year ?? date('Y'))
                ->count();
            $data[] = $count;
        }

        return response()->json([
            'categories' => $categories,
            'data'       => $data,
        ]);
    }

    public function areachart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data       = [];
        $metodes    = Metode::all();

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
            'data'       => $data,
        ]);
    }

    public function donutchart(Request $request)
    {
        $bulan = $request->get('bulan') ?? '';
        $tahun = $request->get('tahun') ?? '';

        $metodes     = Metode::all();
        $counts      = [];
        $total       = 0;
        $metodeIds   = [];
        $metodeNames = [];
        $values      = [];
        $colors      = ['#696cff', '#8592a3', '#71dd37', '#ff3e1d', '#03c3ec', '#ffab00', '#7987a1', '#5c5edc', '#4b4da8', '#3f4174'];

        // Single pass: collect counts and calculate total
        foreach ($metodes as $metode) {
            $count = Inspeksi::where('metode_id', $metode->id)
                ->when(! in_array($bulan, ['all', '']), function ($q) use ($bulan) {
                    return $q->whereMonth('tanggal', $bulan);
                })
                ->when($tahun, function ($q) use ($tahun) {
                    return $q->whereYear('tanggal', $tahun);
                })
                ->count();
            $counts[$metode->id] = $count;
            $total += $count;
        }

        // Calculate percentages using the collected counts
        foreach ($metodes as $metode) {
            $percentage    = $total > 0 ? round(($counts[$metode->id] / $total) * 100, 2) : 0;
            $metodeIds[]   = $metode->id;
            $metodeNames[] = $metode->metode_nama;
            $values[]      = $percentage;
        }

        return response()->json([
            'metodeIds' => $metodeIds,
            'metode'    => $metodeNames,
            'value'     => $values,
            'colors'    => $colors,
        ]);
    }

    public function barchart()
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data       = [];

        $conditions = [
            'OK'     => 'Ok',
            'RUSAK'  => 'Rusak',
            'HILANG' => 'Hilang',
        ];

        foreach ($conditions as $condition => $label) {
            $monthlyData = [];
            foreach ($categories as $month) {
                $count = Inspeksidetail::join('inspeksi', 'inspeksi.id', '=', 'inspeksi_detail.inspeksi_id')
                    ->whereMonth('inspeksi.tanggal', date('m', strtotime($month)))
                    ->whereYear('inspeksi.tanggal', date('Y'))
                    ->where('inspeksi_detail.kondisi_rbs', $condition)
                    ->count();
                $monthlyData[] = $count;
            }

            $data[] = ['name' => $label, 'data' => $monthlyData];
        }

        return response()->json([
            'categories' => $categories,
            'data'       => $data,
        ]);
    }

    public function barchartbyyear($year = null)
    {
        $categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $data       = [];

        $conditions = [
            'OK'     => 'Ok',
            'RUSAK'  => 'Rusak',
            'HILANG' => 'Hilang',
        ];

        foreach ($conditions as $condition => $label) {
            $monthlyData = [];
            foreach ($categories as $month) {
                $count = Inspeksidetail::join('inspeksi', 'inspeksi.id', '=', 'inspeksi_detail.inspeksi_id')
                    ->whereMonth('inspeksi.tanggal', date('m', strtotime($month)))
                    ->whereYear('inspeksi.tanggal', $year ?? date('Y'))
                    ->where('inspeksi_detail.kondisi_rbs', $condition)
                    ->count();
                $monthlyData[] = $count;
            }

            $data[] = ['name' => $label, 'data' => $monthlyData];
        }

        return response()->json([
            'categories' => $categories,
            'data'       => $data,
        ]);
    }

    public function piecharttotal(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year  = $request->get('year') ?? date('Y');

        $datalabels = [];
        $dataseries = [];

        $locations = Lokasi::all();

        foreach ($locations as $location) {
            $count = Inspeksi::where('lokasi_id', $location->id)
                ->where('metode_id', '<>', 3)
                ->whereYear('tanggal', $year)
                ->when($month !== 'all', function ($query) use ($month) {
                    return $query->whereMonth('tanggal', $month);
                })
                ->count();
            $datalabels[] = $location->lokasi_nama;
            $dataseries[] = $count;
        }

        return response()->json([
            'labels' => $datalabels,
            'series' => $dataseries,
        ]);
    }

    public function piechartpgt(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year  = $request->get('year') ?? date('Y');

        $datalabels = [];
        $dataseries = [];

        $locations = Lokasi::all();

        foreach ($locations as $location) {
            $count = Inspeksi::where('lokasi_id', $location->id)
                ->where('metode_id', 1)
                ->whereYear('tanggal', $year)
                ->when($month !== 'all', function ($query) use ($month) {
                    return $query->whereMonth('tanggal', $month);
                })
                ->count();
            $datalabels[] = $location->lokasi_nama;
            $dataseries[] = $count;
        }

        return response()->json([
            'labels' => $datalabels,
            'series' => $dataseries,
        ]);
    }

    public function piechartfs(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year  = $request->get('year') ?? date('Y');

        $datalabels = [];
        $dataseries = [];

        $locations = Lokasi::all();

        foreach ($locations as $location) {
            $count = Inspeksi::where('lokasi_id', $location->id)
                ->where('metode_id', 2)
                ->whereYear('tanggal', $year)
                ->when($month !== 'all', function ($query) use ($month) {
                    return $query->whereMonth('tanggal', $month);
                })
                ->count();
            $datalabels[] = $location->lokasi_nama;
            $dataseries[] = $count;
        }

        return response()->json([
            'labels' => $datalabels,
            'series' => $dataseries,
        ]);
    }
}
