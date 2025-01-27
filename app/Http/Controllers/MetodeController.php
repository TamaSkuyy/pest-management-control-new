<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Metode;
use Illuminate\Http\Request;

class MetodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metode = Metode::paginate(10);
        return view('metode.index', compact('metode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('metode.create');
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
        $query = Metode::query();

        // Search functionality
        if ($request->has('search') && ! empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('metode_kode', 'like', "%$search%")
                    ->orWhere('metode_nama', 'like', "%$search%");
            });
        }

        // Total records without filtering
        $totalRecords = Metode::count();

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

        $data->transform(function ($item) {
            $item->metode_jenis = $item->metode_jenis == 1 ? 'Indoor' : 'Outdoor';
            return $item;
        });

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
        $search = $request->input('search');
        $query  = Metode::query()
            ->select('id', 'metode_nama')
            ->where(function ($q) use ($search) {
                $q->where('metode_nama', 'like', "%$search%");
            });

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $d) {
            $formattedData[] = [
                'id'   => $d->id,
                'text' => $d->metode_nama,
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
        $query = Metode::query()
            ->select('id', 'metode_nama')
            ->where('id', $id);

        $data = $query->get();

        $formattedData = [];
        foreach ($data as $d) {
            $formattedData[] = [
                'id'   => $d->id,
                'text' => $d->metode_nama,
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
            'metode_kode'  => 'required',
            'metode_nama'  => 'required',
            'metode_jenis' => 'required|integer',
        ]);

        try {
            $metode               = new Metode;
            $metode->metode_kode  = $request->metode_kode;
            $metode->metode_nama  = $request->metode_nama;
            $metode->metode_jenis = $request->metode_jenis;
            $metode->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan metode: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Metode berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Metode $metode)
    {
        // return view('metode.show', compact('metode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'metode_kode'  => 'required',
            'metode_nama'  => 'required',
            'metode_jenis' => 'required',
        ]);

        $metode = Metode::findOrFail($id);
        if ($metode === null) {
            return response()->json(['status' => 'error', 'message' => 'Metode tidak ditemukan']);
        }

        try {
            $metode->update([
                'metode_kode'  => $validate['metode_kode'],
                'metode_nama'  => $validate['metode_nama'],
                'metode_jenis' => $validate['metode_jenis'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate metode: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Metode berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $metode = Metode::findOrFail($id);
        if ($metode === null) {
            return response()->json(['status' => 'error', 'message' => 'Metode tidak ditemukan']);
        }

        try {
            $metode->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus metode: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'Metode berhasil dihapus']);
    }
}
