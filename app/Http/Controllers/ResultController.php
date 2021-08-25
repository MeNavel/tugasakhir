<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Result;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $id = auth()->user()->id;
        $results = Result::where('id_akun', $id)->latest()->SimplePaginate(6);
        return view('pages.result',compact('results'))->with('i', (request()->input('page', 1) - 1) * 5);

        // $results = Result::latest()->SimplePaginate(6);
        // return view('pages.result',compact('results'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function show(Result $result)
    {
        $find = $result->created_at;
        $output = DB::table('results')->where('created_at', $find)->first();
        $tgl = $output->created_at;
        $foto = $output->image;
        $hasil = $output->status;
        if($hasil == "TIDAK menggunakan Masker"){
            if($result->kode == NULL){
                $info = "Wajah tidak tersedia pada dataset";
                return view('pages.result_positive', ['tgl' => $tgl ,'foto' => $foto ,'result' => $hasil, 'info' => $info]);
            }
            else{
                $indentity = DB::table('datasets')->where('kode', $result->kode)->first();
                return view('pages.result_negatif', ['id' => $indentity ,'tgl' => $tgl ,'foto' => $foto ,'result' => $hasil]);
            }
        }
        else{
            $info = "Orang Tersebut Telah Menaati Protokol Kesehatan";
            return view('pages.result_positive', ['tgl' => $tgl ,'foto' => $foto ,'result' => $hasil, 'info' => $info]);
        }
    }

    public function destroy($id)
    {
        $result = Result::find($id);
        $image = "/Users/drajad/Mac/Website/website-masker/storage/app/public/".$result->image;
        unlink($image);
        $result->delete();
        return back();
    }
}
