<?php

namespace App\Http\Controllers;

use App\Models\Hama;
use App\Models\Metode;
use App\Models\Tindakan;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tindakan = Tindakan::paginate(10);
        $metode = Metode::all();
        $hama = Hama::all();
        return view('tindakan.index', compact('tindakan', 'metode', 'hama'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('tindakan.create');
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
        $query = Tindakan::query()->with(['metode', 'hama']);

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('tindakan_kode', 'like', "%$search%")
                    ->orWhere('tindakan_nama', 'like', "%$search%");
            });
        }

        // Total records without filtering
        $totalRecords = Tindakan::count();

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

    public function dataInputTable($hama_id = null)
    {
        $query = Tindakan::query();
        if ($hama_id) {
            $query->where('hama_id', $hama_id);
        }
        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'tindakan_kode' => 'required',
            'tindakan_nama' => 'required',
            'hama_id' => 'required|integer',
        ]);

        try {
            $tindakan = new Tindakan;
            $tindakan->tindakan_kode = $request->tindakan_kode;
            $tindakan->tindakan_nama = $request->tindakan_nama;
            $tindakan->metode_id = $request->metode_id ?? null;
            $tindakan->hama_id = $request->hama_id ?? null;
            $tindakan->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan tindakan: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Tindakan berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tindakan $tindakan)
    {
        // return view('metode.show', compact('metode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tindakan $tindakan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'tindakan_kode' => 'required',
            'tindakan_nama' => 'required',
            'hama_id' => 'required',
        ]);

        $tindakan = Tindakan::findOrFail($id);
        if ($tindakan === null) {
            return response()->json(['status' => 'error', 'message' => 'Tindakan tidak ditemukan']);
        }

        try {
            $tindakan->update([
                'tindakan_kode' => $request->tindakan_kode,
                'tindakan_nama' => $request->tindakan_nama,
                'metode_id' => $request->metode_id ?? null,
                'hama_id' => $request->hama_id ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate tindakan: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Tindakan berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tindakan = Tindakan::findOrFail($id);
        if ($tindakan === null) {
            return response()->json(['status' => 'error', 'message' => 'Tindakan tidak ditemukan']);
        }

        try {
            $tindakan->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus tindakan: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Tindakan berhasil dihapus']);
    }
}
