<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Tanggapan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lastPengaduan = Pengaduan::latest()->first();
        $nextId = $lastPengaduan ? $lastPengaduan->id + 1 : 1;

        return view('form', ['nextId' => $nextId]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tujuan_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'telpon' => 'required',
            'alamat' => 'required',
            'tanggal' => 'required',
            'isi' => 'required',
            'gambar' => 'required|image',
            'active' => 'boolean',
        ]);

        $input = $request->all();

        if ($image = $request->file('gambar')) {
            $desiredFileName = $request->input('name');
            $imageName = $desiredFileName . now()->format('Y-m-d') . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images/pengaduan');
            $image->move($destinationPath, $imageName);
            $input['gambar'] = $imageName;
        }
        $input['active'] = 0;

        pengaduan::create($input);
        return redirect('/histori');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return \Illuminate\Http\Response
     */
    public function show(Pengaduan $pengaduan, Request $request)
    {
        $search = $request->input('search');

        $pengaduan = Pengaduan::when($search, function ($query) use ($search) {
            return $query->where('id', $search);
        })->latest()->get();

        return view('histori', compact('pengaduan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengaduan  $pengaduan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pengaduan::findorfail($id)->delete();
        return redirect('/pengaduan');
    }

    public function showCMS(Pengaduan $pengaduan, Request $request)
    {
        $idSearch = $request->input('search');
        $tujuanSearch = $request->input('tujuan_id');
        $activeSearch = $request->input('active');

        $pengaduan = Pengaduan::when($idSearch, function ($query) use ($idSearch) {
            return $query->where('id', $idSearch);
        })->when($tujuanSearch, function ($query) use ($tujuanSearch) {
            return $query->where('tujuan_id', $tujuanSearch);
        })->when(isset($activeSearch), function ($query) use ($activeSearch) {
            return $query->where('active', (bool)$activeSearch);
        })->latest()->get();

        $active = 'table';

        return view('admin.pengaduan', compact('pengaduan', 'active'));
    }

    public function Dashboard(Pengaduan $pengaduan, Request $request)
    {
        $idSearch = $request->input('search');
        $tujuanSearch = $request->input('tujuan_id');
        $activeSearch = $request->input('active');

        $pengaduan = Pengaduan::when($idSearch, function ($query) use ($idSearch) {
            return $query->where('id', $idSearch);
        })->when($tujuanSearch, function ($query) use ($tujuanSearch) {
            return $query->where('tujuan_id', $tujuanSearch);
        })->when(isset($activeSearch), function ($query) use ($activeSearch) {
            return $query->where('active', (bool)$activeSearch);
        })->latest()->get();

        $active = 'dashboard';
        return view('admin.dashboard', compact('pengaduan', 'active'));
    }


    public function reply(Request $request, $pengaduanId)
    {
        $request->validate([
            'isi' => 'required',
        ]);

        $pengaduan = Pengaduan::findOrFail($pengaduanId);
        $tanggapan = new Tanggapan([
            'isi' => $request->input('isi'),
        ]);

        $pengaduan->tanggapan()->save($tanggapan);
        $pengaduan->update(['active' => 1]);

        return redirect('/pengaduan');
    }

    public function showTanggapan(Tanggapan $tanggapan)
    {
        $tanggapan = Tanggapan::all();
        $active = 'tanggapan';
        return view('admin.tanggapan', compact('tanggapan', 'active'));
    }

    public function tanggapanDestroy($id)
    {

        $tanggapan = Tanggapan::findOrFail($id);
        $pengaduan = $tanggapan->pengaduan;
        $pengaduan->update(['active' => 0]);
        $tanggapan->delete();

        return redirect('/tanggapan');
    }
}
