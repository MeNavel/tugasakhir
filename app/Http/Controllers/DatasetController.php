<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dataset;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::latest()->SimplePaginate(7);
        return view('pages.dataset',compact('datasets'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
