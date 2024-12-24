<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasi = Lokasi::paginate(10);
        return view('lokasi.index', compact('lokasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('metode.create');
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
        $query = Lokasi::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('lokasi_kode', 'like', "%$search%")
                    ->orWhere('lokasi_nama', 'like', "%$search%");
            });
        }

        // Total records without filtering
        $totalRecords = Lokasi::count();

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

        $data->transform(function ($item) {
            $item->lokasi_jenis = $item->lokasi_jenis == 1 ? 'Indoor' : 'Outdoor';
            return $item;
        });

        // Prepare the response
        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ]);
    }

    /**
     * Data for select2
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Data(Request $request)
    {
        $search = $request->input('search');
        $metode_value = $request->input('metode_value');
        $query = Lokasi::query()
            ->select('id', 'lokasi_nama')
        // ->where('metode_id', $metode_value)
            ->where(function ($q) use ($search) {
                $q->where('lokasi_nama', 'like', "%$search%");
            });

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $d) {
            $formattedData[] = [
                'id' => $d->id,
                'text' => $d->lokasi_nama,
            ];
        }

        return response()->json($formattedData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'lokasi_kode' => 'required',
            'lokasi_nama' => 'required',
            'lokasi_jenis' => 'required|integer',
        ]);

        try {
            $lokasi = new Lokasi;
            $lokasi->lokasi_kode = $request->lokasi_kode;
            $lokasi->lokasi_nama = $request->lokasi_nama;
            $lokasi->lokasi_jenis = $request->lokasi_jenis;
            $lokasi->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan lokasi: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Lokasi berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lokasi $lokasi)
    {
        // return view('lokasi.show', compact('lokasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        // return view('lokasi.edit', compact('lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'lokasi_kode' => 'required',
            'lokasi_nama' => 'required',
            'lokasi_jenis' => 'required',
        ]);

        $lokasi = Lokasi::findOrFail($id);
        if ($lokasi === null) {
            return response()->json(['status' => 'error', 'message' => 'Lokasi tidak ditemukan']);
        }

        try {
            $lokasi->update([
                'lokasi_kode' => $validate['lokasi_kode'],
                'lokasi_nama' => $validate['lokasi_nama'],
                'lokasi_jenis' => $validate['lokasi_jenis'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate lokasi: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Lokasi berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        if ($lokasi === null) {
            return response()->json(['status' => 'error', 'message' => 'Lokasi tidak ditemukan']);
        }

        try {
            $lokasi->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus lokasi: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Lokasi berhasil dihapus']);
    }
}
