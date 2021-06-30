<?php
    $request->file->storeAs('public',$fileName);    
    $file_predict = "/Applications/XAMPP/xamppfiles/htdocs/tugasakhir/storage/app/public/".$fileName;
    $check_mask = file_get_contents("http://127.0.0.1:5000/predict_mask?file=".$file_predict."");
    if($check_mask == "mask"){
        $result = "Menggunakan Masker";
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
?>