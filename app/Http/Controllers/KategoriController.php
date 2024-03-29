<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    public function count(){
        $countKategori = Kategori::count();
        $countBarang = Barang::count();
        $countBarangKel = BarangKeluar::count();
        $countBarangMas = BarangMasuk::count();
        return view('dashboard',compact('countKategori', 'countBarang', 'countBarangKel', 'countBarangMas'));
    }
    public function index(Request $request)
    {
    /**
    * Display a listing of the resource.
    */

    // $rsetKategori = Kategori::infoKategori();
    // return $rsetKategori;

        // $rsetKategori = DB::table('kategori')->select('id','kategori',DB::raw('ketKategori(jenis) as ketkategori'))->get();
        // return $rsetKategori;

        // return DB::table('kategori')->get();

    // memanggil store procedure
        // return DB::select('CALL getKategoriAll()');

    // memanggil store procedure dengan view
        // $rsetKategori = DB::select('CALL getKategoriAll()');
        // return view('v_smartkoding.index',compact('rsetKategori'));

    $rsetKategori = Kategori::getKategoriAll()->paginate(10);
    return view('kategori.index',compact('rsetKategori'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    
    // $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('ketKategorik(kategori) as ketkategorik'))->paginate(10);
    // return view('kategori.index',compact('rsetKategori'))
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = array('blank'=>'Pilih Kategori',
                            'M'=>'Barang Modal',
                            'A'=>'Alat',
                            'BHP'=>'Bahan Habis Pakai',
                            'BTHP'=>'Bahan Tidak Habis Pakai'
                            );
        return view('kategori.create',compact('akategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'deskripsi'   => 'required',
            'kategori'     => 'required | in:M,A,BHP,BTHP',
        ]);


        // create post
        Kategori::create([
            'deskripsi'  => $request->deskripsi,
	        'kategori'   => $request->kategori,
        ]);

        //redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);

        // $rsetKategori = Kategori::select('id','deskripsi','kategori',
        //     \DB::raw('(CASE
        //         WHEN kategori = "M" THEN "Modal"
        //         WHEN kategori = "A" THEN "Alat"
        //         WHEN kategori = "BHP" THEN "Bahan Habis Pakai"
        //         ELSE "Bahan Tidak Habis Pakai"
        //         END) AS ketKategori'))->where('id', '=', $id);

        return view('kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $akategori = Kategori::all();
    $rsetKategori = Kategori::find($id);

    return view('kategori.edit', compact('rsetKategori', 'akategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'deskripsi'   => 'required',
            'kategori'     => 'required | in:M,A,BHP,BTHP',
        ]);

        $rsetKategori = Kategori::find($id);

            //update post without image
            $rsetKategori->update([
                'deskripsi'  => $request->deskripsi,
	            'kategori'   => $request->kategori,
            ]);

        // Redirect to the index page with a success message
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)

    {

        //cek apakah kategori_id ada di tabel barang.kategori_id ?

        if (DB::table('barang')->where('kategori_id', $id)->exists()){

            return redirect()->route('kategori.index')->with(['Gagal' => 'Data Gagal Dihapus!']);


        } else {

            $rsetKategori = Kategori::find($id);

            $rsetKategori->delete();

            return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);

        }

    }
}