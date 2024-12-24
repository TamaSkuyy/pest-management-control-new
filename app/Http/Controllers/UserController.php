<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
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
        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Total records without filtering
        $totalRecords = User::count();

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,user',
            'password' => 'required',
        ]);

        if (!$validated) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak valid']);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Email sudah pernah digunakan']);
        }

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role ?? 'user';
            $user->password = Hash::make($request->password);
            $user->save();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan user: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'User berhasil dibuat']);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,user',
        ]);

        if (!$validated) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak valid']);
        }

        $user = User::findOrFail($id);
        if ($user === null) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan']);
        }

        try {
            if ($request->password) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role ?? 'user',
                    'password' => Hash::make($request->password),
                ]);
            } else {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role ?? 'user',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate user: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'User berhasil diupdate']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user === null) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan']);
        }

        try {
            $user->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus user: ' . $e->getMessage()]);
        }

        return response()->json(['status' => 'success', 'message' => 'User berhasil dihapus']);
    }
}
