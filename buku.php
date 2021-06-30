<?php
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
?>