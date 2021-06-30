<?php
    $check_face = file_get_contents("http://127.0.0.1:5000/predict_face?file=".$file_predict."");
    if($check_face != "Tidak Terdeteksi"){
        $$indentity = DB::table('datasets')->where('kode', 'like', "%".$check_face."%")->first();
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
?>