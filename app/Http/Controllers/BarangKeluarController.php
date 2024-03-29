<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rsetBarangKeluar = BarangKeluar::with('barang')->latest()->paginate(10);

        return view('barangkeluar.index', compact('rsetBarangKeluar'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Barang = Barang::all();
        $Keluar = BarangKeluar::all();
        return view('barangkeluar.create',compact('Keluar', 'Barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date'          => 'required',
            'qty'          => 'required',
            'barang_id'   => 'required',
        ]);

        //create post
        BarangKeluar::create([
            'tgl_keluar'             => $request->date,
            'qty_keluar'             => $request->qty,
            'barang_id'      => $request->barang_id,
        ]);

        //redirect to index
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barangall = Barang::all();
        $rsetBarangKel = BarangKeluar::find($id);
        $selectedBarang = Barang::find($rsetBarangKel->barang_id);
        return view('barangkeluar.edit', compact('rsetBarangKel', 'barangall', 'selectedBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'date'        => 'required',
            'qty_keluar' => 'required',
            'barang_id'        => 'required',
        ]);

            $rsetBarangKel = BarangKeluar::find($id);

            //update post without image
            $rsetBarangKel->update([
                'tgl_keluar'          => $request->date,
                'qty_keluar'           => $request->qty_keluar,
                'barang_id'          => $request->barang_id,
            ]);

        // Redirect to the index page with a success message
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diubah!']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarangKel = BarangKeluar::find($id);

        //delete post
        $rsetBarangKel->delete();

        //redirect to index
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}
