<?php
    $id = auth()->user()->id;
    $results = Result::where('id_akun', $id)->latest()->SimplePaginate(6);
    return view('pages.result',compact('results'))->with('i', (request()->input('page', 1) - 1) * 5);
?>