<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresenceDetail;
use Illuminate\Support\Facades\Storage;
use Exception;

class PresenceDetailApiController extends Controller
{
    // ✅ GET /api/presence-detail
    // Fitur: tampilkan semua + search + filter
    public function index(Request $request)
    {
        $query = PresenceDetail::query();

        // 🔍 Fitur search berdasarkan nama atau jabatan
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%');
            });
        }

        // 🎯 Filter berdasarkan presence_id (id kegiatan)
        if ($request->has('presence_id') && !empty($request->presence_id)) {
            $query->where('presence_id', $request->presence_id);
        }

        // 🏢 Filter berdasarkan asal instansi
        if ($request->has('asal_instansi') && !empty($request->asal_instansi)) {
            $query->where('asal_instansi', 'like', '%' . $request->asal_instansi . '%');
        }

        // Urutkan dari yang terbaru
        $details = $query->orderBy('created_at', 'desc')->get();

        return response()->json($details);
    }

    // ✅ POST /api/presence-detail
    public function store(Request $request)
    {
        $request->validate([
            'presence_id' => 'required|exists:presences,id',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'asal_instansi' => 'required|string|max:150',
            'signature' => 'nullable|string', // base64 opsional
        ]);

        try {
            $detail = new PresenceDetail();
            $detail->presence_id = $request->presence_id;
            $detail->nama = $request->nama;
            $detail->jabatan = $request->jabatan;
            $detail->asal_instansi = $request->asal_instansi;

            // ✍️ Simpan tanda tangan base64 jika dikirim
            if ($request->filled('signature')) {
                $base64_image = $request->signature;
                @list($type, $file_data) = explode(';', $base64_image);
                @list(, $file_data) = explode(',', $file_data);
                $uniqChar = date('YmdHis') . uniqid();
                $signature = "tanda-tangan/{$uniqChar}.png";

                // Pastikan folder ada
                if (!Storage::disk('public_uploads')->exists('tanda-tangan')) {
                    Storage::disk('public_uploads')->makeDirectory('tanda-tangan');
                }

                Storage::disk('public_uploads')->put($signature, base64_decode($file_data));
                $detail->tanda_tangan = $signature;
            }

            $detail->save();

            return response()->json([
                'message' => 'Peserta berhasil ditambahkan',
                'data' => $detail
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan data',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // ✅ PUT/PATCH /api/presence-detail/{id}
public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'sometimes|required|string|max:100',
        'jabatan' => 'sometimes|required|string|max:100',
        'asal_instansi' => 'sometimes|required|string|max:150',
        'signature' => 'nullable|string', // base64 opsional
    ]);

    try {
        $detail = PresenceDetail::findOrFail($id);

        $detail->nama = $request->nama ?? $detail->nama;
        $detail->jabatan = $request->jabatan ?? $detail->jabatan;
        $detail->asal_instansi = $request->asal_instansi ?? $detail->asal_instansi;

        // ✍️ Update tanda tangan jika dikirim
        if ($request->filled('signature')) {
            // Hapus tanda tangan lama jika ada
            if ($detail->tanda_tangan && Storage::disk('public_uploads')->exists($detail->tanda_tangan)) {
                Storage::disk('public_uploads')->delete($detail->tanda_tangan);
            }

            $base64_image = $request->signature;
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $uniqChar = date('YmdHis') . uniqid();
            $signature = "tanda-tangan/{$uniqChar}.png";

            if (!Storage::disk('public_uploads')->exists('tanda-tangan')) {
                Storage::disk('public_uploads')->makeDirectory('tanda-tangan');
            }

            Storage::disk('public_uploads')->put($signature, base64_decode($file_data));
            $detail->tanda_tangan = $signature;
        }

        $detail->save();

        return response()->json([
            'message' => 'Peserta berhasil diperbarui',
            'data' => $detail
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'error' => 'Terjadi kesalahan saat memperbarui data',
            'message' => $e->getMessage()
        ], 500);
    }
}


    // ✅ DELETE /api/presence-detail/{id}
    public function destroy($id)
    {
        $detail = PresenceDetail::findOrFail($id);

        if ($detail->tanda_tangan && Storage::disk('public_uploads')->exists($detail->tanda_tangan)) {
            Storage::disk('public_uploads')->delete($detail->tanda_tangan);
        }

        $detail->delete();

        return response()->json(['message' => 'Peserta berhasil dihapus']);
    }
}
