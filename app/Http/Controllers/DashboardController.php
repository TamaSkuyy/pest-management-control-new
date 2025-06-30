<?php
namespace App\Http\Controllers;

use App\Models\Hama;
use App\Models\Inspeksi;
use App\Models\Inspeksidetail;
use App\Models\Lokasi;
use App\Models\Metode;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function linechart()
    {
        // Generate categories for bi-weekly periods
        $categories = $this->generateBiWeeklyCategories();
        $data = [];
        $metodes = Metode::all();

        foreach ($metodes as $metode) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getInspectionCountForBiWeeklyPeriod($metode->id, $category, date('Y'));
                $biWeeklyData[] = $count;
            }
            
            $data[] = ['name' => $metode->metode_nama, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
        ]);
    }

    public function linechartbyyear($year = null)
    {
        $year = $year ?? date('Y');
        $categories = $this->generateBiWeeklyCategories();
        $data = [];
        $metodes = Metode::all();

        foreach ($metodes as $metode) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getInspectionCountForBiWeeklyPeriod($metode->id, $category, $year);
                $biWeeklyData[] = $count;
            }
            
            $data[] = ['name' => $metode->metode_nama, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
        ]);
    }

    public function areachart()
    {
        $categories = $this->generateBiWeeklyCategories();
        $data = [];
        $metodes = Metode::all();

        foreach ($metodes as $metode) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getInspectionCountForBiWeeklyPeriod($metode->id, $category, date('Y'));
                $biWeeklyData[] = $count;
            }
            
            $data[] = ['name' => $metode->metode_nama, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
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
        $categories = $this->generateBiWeeklyCategories();
        $data = [];

        $conditions = [
            'OK'     => 'Ok',
            'RUSAK'  => 'Rusak',
            'HILANG' => 'Hilang',
        ];

        foreach ($conditions as $condition => $label) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getInspectionDetailCountForBiWeeklyPeriod($condition, $category, date('Y'));
                $biWeeklyData[] = $count;
            }

            $data[] = ['name' => $label, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
        ]);
    }

    public function barchartbyyear($year = null)
    {
        $year = $year ?? date('Y');
        $categories = $this->generateBiWeeklyCategories();
        $data = [];

        $conditions = [
            'OK'     => 'Ok',
            'RUSAK'  => 'Rusak',
            'HILANG' => 'Hilang',
        ];

        foreach ($conditions as $condition => $label) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getInspectionDetailCountForBiWeeklyPeriod($condition, $category, $year);
                $biWeeklyData[] = $count;
            }

            $data[] = ['name' => $label, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
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

    public function piechartrbs(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year  = $request->get('year') ?? date('Y');

        $datalabels = [];
        $dataseries = [];

        $locations = Lokasi::all();

        foreach ($locations as $location) {
            $count = Inspeksi::where('lokasi_id', $location->id)
                ->where('metode_id', 3) // RBS method
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

    public function getMetodeName($metodeId)
    {
        $metode = Metode::find($metodeId);
        return response()->json([
            'metode_nama' => $metode ? $metode->metode_nama : 'Unknown',
            'metode_kode' => $metode ? $metode->metode_kode : 'N/A'
        ]);
    }

    public function getAllMetodes()
    {
        $metodes = Metode::all(['id', 'metode_kode', 'metode_nama']);
        return response()->json($metodes);
    }

    public function linecharthama(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year = $request->get('year') ?? date('Y');
        $lapisanPengaman = $request->get('lapisan_pengaman') ?? 'all';
        
        // Generate categories for bi-weekly periods
        $categories = $this->generateBiWeeklyCategories();
        $data = [];
        
        // Get locations based on security layer filter
        $locations = Lokasi::when($lapisanPengaman !== 'all', function($query) use ($lapisanPengaman) {
            return $query->where('lokasi_lapisan_pengaman', $lapisanPengaman);
        })->get();

        foreach ($locations as $location) {
            $biWeeklyData = [];
            
            foreach ($categories as $category) {
                $count = $this->getLocationInspectionCountForBiWeeklyPeriod($location->id, $category, $year, $month);
                $biWeeklyData[] = $count;
            }
            
            $data[] = ['name' => $location->lokasi_nama, 'data' => $biWeeklyData];
        }

        return response()->json([
            'categories' => $this->generateDisplayCategories(),
            'data' => $data,
        ]);
    }

    /**
     * Get location inspection count for bi-weekly period
     */
    private function getLocationInspectionCountForBiWeeklyPeriod($locationId, $category, $year, $month = 'all')
    {
        list($monthName, $period) = explode('-', $category);
        $monthNumber = date('m', strtotime($monthName));
        
        // Skip if filtering by specific month and this category is not that month
        if ($month !== 'all' && $monthNumber != $month) {
            return 0;
        }
        
        // Determine date range for the period
        if ($period == '1') {
            // First half: days 1-15
            $startDate = \Carbon\Carbon::create($year, $monthNumber, 1)->startOfDay();
            $endDate = \Carbon\Carbon::create($year, $monthNumber, 15)->endOfDay();
        } else {
            // Second half: days 16-end of month
            $startDate = \Carbon\Carbon::create($year, $monthNumber, 16)->startOfDay();
            $endDate = \Carbon\Carbon::create($year, $monthNumber, 1)->endOfMonth()->endOfDay();
        }

        return Inspeksi::where('lokasi_id', $locationId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah'); // Sum the quantity found at this location
    }

    /**
     * Get all security layers
     */
    public function getSecurityLayers()
    {
        $layers = Lokasi::select('lokasi_lapisan_pengaman')
            ->distinct()
            ->whereNotNull('lokasi_lapisan_pengaman')
            ->pluck('lokasi_lapisan_pengaman')
            ->sort()
            ->values();
        
        return response()->json($layers);
    }

    public function donuthama(Request $request)
    {
        $month = $request->get('month') ?? 'all';
        $year  = $request->get('year') ?? date('Y');
        $lapisanPengaman = $request->get('lapisan_pengaman') ?? 'all';

        $hamaLabels = [];
        $hamaSeries = [];
        $colors = [
            '#696cff', '#8592a3', '#71dd37', '#ff3e1d', '#03c3ec', '#ffab00', 
            '#7987a1', '#5c5edc', '#4b4da8', '#3f4174', '#FF6B35', '#9C27B0'
        ];

        // Get hama data with filtering
        $hamaQuery = Inspeksi::with(['hama', 'lokasi'])
            ->whereYear('tanggal', $year)
            ->when($month !== 'all', function ($query) use ($month) {
                return $query->whereMonth('tanggal', $month);
            })
            ->when($lapisanPengaman !== 'all', function ($query) use ($lapisanPengaman) {
                return $query->whereHas('lokasi', function ($q) use ($lapisanPengaman) {
                    $q->where('lokasi_lapisan_pengaman', $lapisanPengaman);
                });
            })
            ->whereNotNull('hama_id');

        // Group by hama and sum the quantities
        $hamaData = $hamaQuery->get()
            ->groupBy('hama_id')
            ->map(function ($inspections) {
                return [
                    'nama' => $inspections->first()->hama->hama_nama ?? 'Unknown',
                    'total' => $inspections->sum('jumlah')
                ];
            })
            ->filter(function ($item) {
                return $item['total'] > 0; // Only include hama with counts > 0
            })
            ->sortByDesc('total')
            ->take(10); // Limit to top 10 pests

        foreach ($hamaData as $data) {
            $hamaLabels[] = $data['nama'];
            $hamaSeries[] = $data['total'];
        }

        return response()->json([
            'labels' => $hamaLabels,
            'series' => $hamaSeries,
            'colors' => array_slice($colors, 0, count($hamaLabels))
        ]);
    }

    /**
     * Generate bi-weekly categories for internal processing
     */
    private function generateBiWeeklyCategories()
    {
        $categories = [];
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        foreach ($months as $month) {
            $categories[] = $month . '-1'; // First half of month (days 1-15)
            $categories[] = $month . '-2'; // Second half of month (days 16-end)
        }

        return $categories;
    }

    /**
     * Generate display categories for chart x-axis
     */
    private function generateDisplayCategories()
    {
        return [
            'Jan', '', 'Feb', '', 'Mar', '',
            'Apr', '', 'May', '', 'Jun', '',
            'Jul', '', 'Aug', '', 'Sep', '',
            'Oct', '', 'Nov', '', 'Dec', ''
        ];
    }

    /**
     * Get inspection count for bi-weekly period
     */
    private function getInspectionCountForBiWeeklyPeriod($metodeId, $category, $year)
    {
        list($monthName, $period) = explode('-', $category);
        $monthNumber = date('m', strtotime($monthName));
        
        // Determine date range for the period
        if ($period == '1') {
            // First half: days 1-15
            $startDate = Carbon::create($year, $monthNumber, 1)->startOfDay();
            $endDate = Carbon::create($year, $monthNumber, 15)->endOfDay();
        } else {
            // Second half: days 16-end of month
            $startDate = Carbon::create($year, $monthNumber, 16)->startOfDay();
            $endDate = Carbon::create($year, $monthNumber, 1)->endOfMonth()->endOfDay();
        }

        return Inspeksi::where('metode_id', $metodeId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get inspection detail count for bi-weekly period
     */
    private function getInspectionDetailCountForBiWeeklyPeriod($condition, $category, $year)
    {
        list($monthName, $period) = explode('-', $category);
        $monthNumber = date('m', strtotime($monthName));
        
        // Determine date range for the period
        if ($period == '1') {
            // First half: days 1-15
            $startDate = Carbon::create($year, $monthNumber, 1)->startOfDay();
            $endDate = Carbon::create($year, $monthNumber, 15)->endOfDay();
        } else {
            // Second half: days 16-end of month
            $startDate = Carbon::create($year, $monthNumber, 16)->startOfDay();
            $endDate = Carbon::create($year, $monthNumber, 1)->endOfMonth()->endOfDay();
        }

        return Inspeksidetail::join('inspeksi', 'inspeksi.id', '=', 'inspeksi_detail.inspeksi_id')
            ->whereBetween('inspeksi.tanggal', [$startDate, $endDate])
            ->where('inspeksi_detail.kondisi_rbs', $condition)
            ->count();
    }
}
