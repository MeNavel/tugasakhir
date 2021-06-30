<?php
    $find = $result->created_at;
    $output = DB::table('results')->where('created_at', $find)->first();
    $tgl = $output->created_at;
    $foto = $output->image;
    $hasil = $output->status;
    $indentity = DB::table('datasets')->where('kode', $result->kode)->first();
    return view('pages.result_negatif', ['id' => $indentity ,'tgl' => $tgl ,'foto' => $foto ,'result' => $hasil]);
?>