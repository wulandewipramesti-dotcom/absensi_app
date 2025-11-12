<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\PresenceDetail;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{

   

    $query = Presence::query();

    if ($request->filled('search')) {
        $query->where('nama_kegiatan', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('tgl_kegiatan')) {
    $query->whereDate('tgl_kegiatan', $request->tgl_kegiatan);
}


     

    $presences = $query->orderBy('tgl_kegiatan', 'desc')->get();

    return view('pages.presence.index', compact('presences'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.presence.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    
    {

        $request->validate([
            'nama_kegiatan' => 'required',
            'tgl_kegiatan' => 'required',
            'waktu_mulai' => 'required',
        ]);

            $presence = new Presence();
            $presence->nama_kegiatan = $request->nama_kegiatan;
            $presence->slug  =  Str::slug($request->nama_kegiatan);
            $presence->tgl_kegiatan = Carbon::createFromFormat('Y-m-d H:i', $request->tgl_kegiatan . ' ' . $request->waktu_mulai);
            $presence->save();
            $presence->save();

     return redirect()->route('presence.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $presence = Presence::findOrFail($id);
        $presenceDetails = PresenceDetail::where('presence_id', $id)->get();
        return view('pages.presence.detail.index', compact('presence', 'presenceDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $presence = Presence::findOrFail($id);
        return view('pages.presence.edit', compact('presence'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $request->validate([
            'nama_kegiatan' => 'required',
            'tgl_kegiatan' => 'required',
            'waktu_mulai' => 'required',
        ]);

        $presence = Presence::findOrFail($id);
            $presence->nama_kegiatan = $request->nama_kegiatan;
            $presence->slug  =  Str::slug($request->nama_kegiatan);
            $presence->tgl_kegiatan = Carbon::createFromFormat('Y-m-d H:i', $request->tgl_kegiatan . ' ' . $request->waktu_mulai);
            $presence->save();
            $presence->save();

            

         return redirect()->route('presence.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Presence::destroy($id);

        return redirect()->route('presence.index');
    }
}
