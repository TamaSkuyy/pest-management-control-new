<?php
namespace App\Http\Controllers;

use App\Models\Hama;
use App\Models\Metode;
use Illuminate\Http\Request;

class HamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hama   = Hama::paginate(10);
        $metode = Metode::all();
        return view('hama.index', compact('hama', 'metode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('hama.create');
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
        $query = Hama::query()->with('metode');

        // Search functionality
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('hama_kode', 'like', "%$search%")
                    ->orWhere('hama_nama', 'like', "%$search%");
            });
        }

        // Total records without filtering
        $totalRecords = Hama::count();

        // Records with filtering
        $totalFiltered = $query->count();

        // Sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderColumn      = $request->columns[$orderColumnIndex]['data'];
            $orderDir         = $request->order[0]['dir'];
            $query->orderBy($orderColumn, $orderDir);
        }

        // Pagination
        $start  = $request->start ?? 0;
        $length = $request->length ?? 10;
        $data   = $query->offset($start)->limit($length)->get();

        // Prepare the response
        return response()->json([
            "draw"            => intval($request->draw),
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            "data"            => $data,
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
        $search       = $request->input('search');
        $metode_value = $request->input('metode_value');
        $query        = Hama::query()
            ->select('id', 'hama_nama')
            ->where('metode_id', $metode_value)
            ->where(function ($q) use ($search) {
                $q->where('hama_nama', 'like', "%$search%");
            });

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $d) {
            $formattedData[] = [
                'id'   => $d->id,
                'text' => $d->hama_nama,
            ];
        }

        return response()->json($formattedData);
    }

    /**
     * Data for select2 initial
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2DataInitial($id)
    {
        $query = Hama::query()
            ->select('id', 'hama_nama')
            ->where('id', $id);

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $d) {
            $formattedData[] = [
                'id'   => $d->id,
                'text' => $d->hama_nama,
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
            'hama_kode' => 'required',
            'hama_nama' => 'required',
            'metode_id' => 'required|integer',
        ]);

        try {
            $hama            = new Hama;
            $hama->hama_kode = $request->hama_kode;
            $hama->hama_nama = $request->hama_nama;
            $hama->metode_id = $request->metode_id;
            $hama->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan hama: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Hama berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hama $hama)
    {
        // return view('metode.show', compact('metode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hama $hama)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'hama_kode' => 'required',
            'hama_nama' => 'required',
            'metode_id' => 'required',
        ]);

        $hama = Hama::findOrFail($id);
        if ($hama === null) {
            return response()->json(['status' => 'error', 'message' => 'Hama tidak ditemukan']);
        }

        try {
            $hama->update([
                'hama_kode' => $validate['hama_kode'],
                'hama_nama' => $validate['hama_nama'],
                'metode_id' => $validate['metode_id'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate hama: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Hama berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hama = Hama::findOrFail($id);
        if ($hama === null) {
            return response()->json(['status' => 'error', 'message' => 'Hama tidak ditemukan']);
        }

        try {
            $hama->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus hama: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Hama berhasil dihapus']);
    }
}
