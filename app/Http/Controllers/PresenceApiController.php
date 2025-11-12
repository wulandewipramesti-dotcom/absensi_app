<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use Illuminate\Support\Str;

class PresenceApiController extends Controller
{
    // ✅ GET /api/presence
    public function index(Request $request)
    {
       $query = Presence::with('details'); // 🔹 Ambil relasi details


        // 🔍 Fitur pencarian berdasarkan nama kegiatan
        if ($request->has('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
        }

        // 📅 Fitur filter berdasarkan tanggal kegiatan
        if ($request->has('tgl_kegiatan')) {
            $query->whereDate('tgl_kegiatan', $request->tgl_kegiatan);
        }

        // Urutkan hasil dari tanggal terbaru
        $query->orderBy('tgl_kegiatan', 'desc');

        // Ambil data hasil query
        $data = $query->get();

        // Kembalikan hasil dalam format JSON
        return response()->json($data);
    }

    // ✅ POST /api/presence
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tgl_kegiatan' => 'required'
        ]);

        $presence = Presence::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'slug' => Str::slug($request->nama_kegiatan),
            'tgl_kegiatan' => $request->tgl_kegiatan
        ]);

        return response()->json([
            'message' => 'Presence created successfully',
            'data' => $presence
        ], 201);
    }

    // ✅ GET /api/presence/{id}
    public function show($id)
    {
        $presence = Presence::findOrFail($id);
        return response()->json($presence);
    }

    // ✅ PUT/PATCH /api/presence/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tgl_kegiatan' => 'required',
        ]);

        $presence = Presence::findOrFail($id);
        $presence->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'slug' => Str::slug($request->nama_kegiatan),
            'tgl_kegiatan' => $request->tgl_kegiatan,
        ]);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'data' => $presence
        ]);
    }

    // ✅ DELETE /api/presence/{id}
    public function destroy($id)
    {
        $presence = Presence::findOrFail($id);
        $presence->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
