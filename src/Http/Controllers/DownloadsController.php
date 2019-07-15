<?php

namespace LWS\ExportActions\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

class DownloadsController extends Controller
{
    public function csv(Request $request)
    {
        
        $file = storage_path()."/app/csvfile.csv";

        //dd($file);

        

        return response()->download($file);
    }
}
