<?php

namespace App\Http\Controllers;

use App\Models\Hama;
use App\Models\Inspeksi;
use App\Models\Inspeksidetail;
use App\Models\Lokasi;
use App\Models\Metode;
use App\Models\Tindakan;
use Illuminate\Http\Request;

class InspeksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspeksi = Inspeksi::all();
        $metode = Metode::all();
        $lokasi = Lokasi::all();
        $hama = Hama::all();
        $tindakans = Tindakan::all();
        return view('inspeksi.index', compact('inspeksi', 'metode', 'lokasi', 'hama', 'tindakans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tindakans = Tindakan::all();

        return view('inspeksi.create', compact('tindakans'));
    }

    /**
     * Data for datatables
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        // Fetch data from the model
        $query = Inspeksi::query()->with(['metode', 'lokasi', 'hama']);

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('metode_kode', 'like', "%$search%")
                    ->orWhere('metode_nama', 'like', "%$search%")
                    ->orWhere('lokasi_kode', 'like', "%$search%")
                    ->orWhere('lokasi_nama', 'like', "%$search%")
                    ->orWhere('hama_kode', 'like', "%$search%")
                    ->orWhere('hama_nama', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhere('pegawai', 'like', "%$search%")
                    ->orWhere('jumlah', 'like', "%$search%")
                ;
            });
        }

        // Total records without filtering
        $totalRecords = Inspeksi::count();

        // Records with filtering
        $totalFiltered = $query->count();

        // Sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderColumn = $request->columns[$orderColumnIndex]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumn, $orderDir);
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $data = $query->offset($start)->limit($length)->get();

        // Prepare the response
        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $inspeksi = Inspeksi::create([
            'metode_id' => $request->metode_id,
            'lokasi_id' => $request->lokasi_id,
            'hama_id' => $request->hama_id,
            'tanggal' => $request->tanggal,
            'pegawai' => $request->pegawai,
            'jumlah' => $request->jumlah,
        ]);

        foreach ($request->tindakan as $data) {
            Inspeksidetail::create([
                'inspeksi_id' => $inspeksi->id,
                'tindakan_id' => $data['id'],
                'check' => $data['checked'] ?? 0,
            ]);
        }

        return response()->json([
            'message' => 'Data inspeksi berhasil disimpan.',
            'data' => $inspeksi,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspeksi $inspeksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspeksi $inspeksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inspeksi $inspeksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $inspeksi = Inspeksi::findOrFail($id);
        if ($inspeksi === null) {
            return response()->json(['status' => 'error', 'message' => 'Inspeksi tidak ditemukan']);
        }

        // try {
        //     Inspeksidetail::where('inspeksi_id', $inspeksi->id)->delete();
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 'error', 'message' => 'Gagal menghapus inspeksi detail: ' . $e->getMessage()]);
        // }

        try {
            $inspeksi->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus inspeksi: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Inspeksi berhasil dihapus']);
    }
}
