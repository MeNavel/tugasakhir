<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShieldController extends Controller
{
    public function index()
    {
        return view('pages.shield');
    }

    public function predict_file(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:JPG,JPEG,PNG,jpeg,png,jpg,gif,svg',
        ]);
        $id = (auth()->user()->id);

        //Simpan Foto
        $fileName = uniqid() . '.png';
        $request->file->storeAs('public',$fileName);

        //Deteksi
        date_default_timezone_set('Asia/Jakarta');
        $tgl=date("Y/m/d H:i:s");

        $file_predict = "/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/public/".$fileName;
        
        $check_mask = file_get_contents("http://127.0.0.1:5000/predict_shield?file=".$file_predict."");
        if($check_mask == "shield"){
            $result = "Menggunakan FaceShield";
            $nama = "Orang";
            $info = "Orang Tersebut Telah Menaati Protokol Kesehatan";
            DB::table('results')->insert([
                'nama' => $nama,
                'status' => $result,
                'image' => $fileName,
                'created_at' => $tgl,
                'id_akun' => $id
            ]);
            return view('pages.result_positive', ['foto' => $fileName, 'result' => $result, 'tgl' => $tgl, 'info'=>$info]);
        }
        else{
            $result = "TIDAK menggunakan FaceShield";
            $check_face = file_get_contents("http://127.0.0.1:5000/predict_face?file=".$file_predict."");
            if($check_face == "Tidak Terdeteksi"){
                $nama = "Orang";
                $info = "Wajah tidak tersedia pada dataset";
                DB::table('results')->insert([
                    'nama' => $nama,
                    'status' => $result,
                    'image' => $fileName,
                    'created_at' => $tgl,
                    'id_akun' => $id
                ]);
                return view('pages.result_positive', ['foto' => $fileName, 'result' => $result, 'tgl' => $tgl, 'info' => $info]);
            }
            else{
                $indentity = DB::table('datasets')->where('kode', 'like', "%".$check_face."%")->first();
                DB::table('results')->insert([
                    'nama' => $indentity->nama,
                    'status' => $result,
                    'kode' => $check_face,
                    'image' => $fileName,
                    'created_at' => $tgl,
                    'id_akun' => $id
                ]);
                return view('pages.result_negatif', ['id' => $indentity ,'tgl' => $tgl ,'foto' => $fileName ,'result' => $result]);
            }
        }
    }
}
